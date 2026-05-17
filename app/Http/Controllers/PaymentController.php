<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Payment;
use App\Models\Ride;
use App\Models\User;

class PaymentController extends Controller
{
    private $payfastMerchantId;
    private $payfastMerchantKey;
    private $payfastPassphrase;
    private $payfastReturnUrl;
    private $payfastCancelUrl;
    private $payfastNotifyUrl;

    public function __construct()
    {
        $this->payfastMerchantId = env('PAYFAST_MERCHANT_ID');
        $this->payfastMerchantKey = env('PAYFAST_MERCHANT_KEY');
        $this->payfastPassphrase = env('PAYFAST_PASSPHRASE');
        $this->payfastReturnUrl = env('PAYFAST_RETURN_URL', env('APP_URL') . '/payment/success');
        $this->payfastCancelUrl = env('PAYFAST_CANCEL_URL', env('APP_URL') . '/payment/cancel');
        $this->payfastNotifyUrl = env('PAYFAST_NOTIFY_URL', env('APP_URL') . '/api/payments/notify');
    }

    /**
     * Create a payment request for a ride
     */
    public function create(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'ride_id' => 'required|exists:rides,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $ride = Ride::findOrFail($request->ride_id);
        $user = $request->user();

        // Check if user owns this ride (is the parent)
        if ($ride->rideRequest->parent_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Check if payment already exists
        $existingPayment = Payment::where('ride_id', $ride->id)->first();
        if ($existingPayment) {
            return response()->json(['payment' => $existingPayment], 200);
        }

        // Create payment record
        $payment = Payment::create([
            'ride_id' => $ride->id,
            'user_id' => $user->id,
            'amount' => $ride->fare_estimate,
            'currency' => 'ZAR',
            'status' => 'pending',
            'provider' => 'payfast',
        ]);

        // Generate PayFast payment data
        $payfastData = $this->generatePayFastData($payment, $user);

        return response()->json([
            'payment' => $payment,
            'payfast_data' => $payfastData,
            'payfast_url' => 'https://www.payfast.co.za/eng/process'
        ]);
    }

    /**
     * Handle PayFast return (success page)
     */
    public function success(Request $request): JsonResponse
    {
        // This is called when user returns from PayFast
        // Payment confirmation happens via notify/webhook
        return response()->json(['message' => 'Payment processing...']);
    }

    /**
     * Handle PayFast cancel
     */
    public function cancel(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Payment cancelled']);
    }

    /**
     * Handle PayFast ITN (Instant Transaction Notification)
     */
    public function notify(Request $request): JsonResponse
    {
        Log::info('PayFast ITN received', $request->all());

        // Validate the ITN data
        if (!$this->validatePayFastITN($request->all())) {
            Log::error('Invalid PayFast ITN signature');
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $paymentStatus = $request->input('payment_status');
        $mPaymentId = $request->input('m_payment_id');

        // Find the payment
        $payment = Payment::find($mPaymentId);
        if (!$payment) {
            Log::error('Payment not found', ['payment_id' => $mPaymentId]);
            return response()->json(['error' => 'Payment not found'], 404);
        }

        // Update payment status
        if ($paymentStatus === 'COMPLETE') {
            $payment->update([
                'status' => 'completed',
                'provider_payment_id' => $request->input('pf_payment_id'),
                'provider_data' => $request->all(),
                'paid_at' => now(),
            ]);

            // Update ride status
            $payment->ride->update(['status' => 'paid']);
        } elseif (in_array($paymentStatus, ['FAILED', 'CANCELLED'])) {
            $payment->update([
                'status' => strtolower($paymentStatus),
                'provider_data' => $request->all(),
            ]);
        }

        return response()->json(['message' => 'ITN processed']);
    }

    /**
     * Get payment status
     */
    public function status(Request $request, $paymentId): JsonResponse
    {
        $payment = Payment::findOrFail($paymentId);

        // Check if user owns this payment
        if ($payment->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json(['payment' => $payment]);
    }

    /**
     * Generate PayFast payment data
     */
    private function generatePayFastData(Payment $payment, User $user): array
    {
        $data = [
            'merchant_id' => $this->payfastMerchantId,
            'merchant_key' => $this->payfastMerchantKey,
            'return_url' => $this->payfastReturnUrl,
            'cancel_url' => $this->payfastCancelUrl,
            'notify_url' => $this->payfastNotifyUrl,
            'name_first' => explode(' ', $user->name)[0] ?? $user->name,
            'name_last' => explode(' ', $user->name)[1] ?? '',
            'email_address' => $user->email,
            'm_payment_id' => $payment->id,
            'amount' => number_format($payment->amount, 2, '.', ''),
            'item_name' => 'Lollipop Ride Payment - Ride #' . $payment->ride_id,
            'item_description' => 'Carpool ride payment',
        ];

        // Generate signature
        $data['signature'] = $this->generatePayFastSignature($data);

        return $data;
    }

    /**
     * Generate PayFast signature
     */
    private function generatePayFastSignature(array $data): string
    {
        // Remove signature from data if it exists
        unset($data['signature']);

        // Sort the array by key
        ksort($data);

        // Create the parameter string
        $paramString = '';
        foreach ($data as $key => $value) {
            if (!empty($value)) {
                $paramString .= $key . '=' . urlencode($value) . '&';
            }
        }
        $paramString = rtrim($paramString, '&');

        // Add passphrase if provided
        if (!empty($this->payfastPassphrase)) {
            $paramString .= '&passphrase=' . urlencode($this->payfastPassphrase);
        }

        return md5($paramString);
    }

    /**
     * Validate PayFast ITN
     */
    private function validatePayFastITN(array $data): bool
    {
        $signature = $data['signature'] ?? '';
        unset($data['signature']);

        $generatedSignature = $this->generatePayFastSignature($data);

        return hash_equals($generatedSignature, $signature);
    }
}