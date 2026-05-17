<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Child;
use App\Models\RideRequest;
use App\Models\Notification;

class ParentController extends Controller
{
    public function children(Request $request)
    {
        return response()->json($request->user()->children()->with('school', 'authorizedContacts')->get());
    }

    public function storeChild(Request $request)
    {
        $data = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
            'grade' => 'nullable|string|max:50',
            'dob' => 'nullable|date',
            'photo_url' => 'nullable|url',
            'special_needs' => 'nullable|string',
            'pickup_notes' => 'nullable|string',
        ]);

        $data['parent_id'] = $request->user()->id;
        $child = Child::create($data);
        return response()->json($child, 201);
    }

    public function updateChild(Request $request, Child $child)
    {
        if ($child->parent_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $child->update($request->only(['school_id', 'name', 'grade', 'dob', 'photo_url', 'special_needs', 'pickup_notes']));
        return response()->json($child);
    }

    public function storeRideRequest(Request $request)
    {
        $data = $request->validate([
            'child_id' => 'required|exists:children,id',
            'school_id' => 'required|exists:schools,id',
            'type' => 'required|in:to_school,from_school,other',
            'pickup_address' => 'required|string|max:500',
            'pickup_lat' => 'nullable|numeric',
            'pickup_lng' => 'nullable|numeric',
            'dropoff_address' => 'nullable|string|max:500',
            'dropoff_lat' => 'nullable|numeric',
            'dropoff_lng' => 'nullable|numeric',
            'scheduled_at' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $data['parent_id'] = $request->user()->id;
        $rideRequest = RideRequest::create($data);
        return response()->json($rideRequest, 201);
    }

    public function rideRequests(Request $request)
    {
        return response()->json($request->user()->children()->with('rideRequests')->get());
    }

    public function rides(Request $request)
    {
        return response()->json($request->user()->rides()->with('rideRequest.child.school', 'vehicle')->get());
    }

    public function showRide(Request $request, $ride)
    {
        $ride = $request->user()->rides()->with('rideRequest.child.school', 'vehicle', 'locations')->findOrFail($ride);
        return response()->json($ride);
    }

    public function notifications(Request $request)
    {
        return response()->json(Notification::where('user_id', $request->user()->id)->get());
    }
}
