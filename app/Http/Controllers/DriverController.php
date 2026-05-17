<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Ride;
use App\Models\RideLocation;

class DriverController extends Controller
{
    public function profile(Request $request)
    {
        return response()->json($request->user());
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:25',
        ]);

        $request->user()->update($data);
        return response()->json($request->user());
    }

    public function vehicles(Request $request)
    {
        return response()->json($request->user()->vehicles()->get());
    }

    public function storeVehicle(Request $request)
    {
        $data = $request->validate([
            'make' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1990|max:'.(date('Y') + 1),
            'plate_number' => 'required|string|max:50',
            'capacity' => 'required|integer|min:1',
            'child_seat_count' => 'required|integer|min:0',
            'insurance_status' => 'nullable|string|max:100',
            'registration_status' => 'nullable|string|max:100',
        ]);

        $data['driver_id'] = $request->user()->id;
        $vehicle = Vehicle::create($data);
        return response()->json($vehicle, 201);
    }

    public function updateVehicle(Request $request, Vehicle $vehicle)
    {
        if ($vehicle->driver_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $vehicle->update($request->only(['make', 'model', 'year', 'plate_number', 'capacity', 'child_seat_count', 'insurance_status', 'registration_status']));
        return response()->json($vehicle);
    }

    public function availableRides(Request $request)
    {
        return response()->json(Ride::where('status', 'requested')->with('rideRequest.child.school')->get());
    }

    public function acceptRide(Request $request, Ride $ride)
    {
        $ride->update([
            'driver_id' => $request->user()->id,
            'status' => 'accepted',
        ]);

        return response()->json($ride);
    }

    public function startRide(Request $request, Ride $ride)
    {
        $ride->update(['status' => 'active', 'started_at' => now()]);
        return response()->json($ride);
    }

    public function completeRide(Request $request, Ride $ride)
    {
        $ride->update(['status' => 'completed', 'ended_at' => now()]);
        return response()->json($ride);
    }

    public function updateLocation(Request $request, Ride $ride)
    {
        $data = $request->validate([
            'driver_lat' => 'required|numeric',
            'driver_lng' => 'required|numeric',
        ]);

        $location = RideLocation::create(array_merge($data, ['ride_id' => $ride->id, 'timestamp' => now()]));
        return response()->json($location, 201);
    }

    public function activeRides(Request $request)
    {
        return response()->json(Ride::where('driver_id', $request->user()->id)->where('status', 'active')->with('rideRequest.child.school', 'locations')->get());
    }
}
