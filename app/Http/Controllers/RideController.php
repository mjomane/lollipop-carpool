<?php

namespace App\Http\Controllers;

use App\Models\Ride;

class RideController extends Controller
{
    public function status($rideId)
    {
        $ride = Ride::with(['driver', 'vehicle', 'rideRequest.child.school', 'locations'])
            ->findOrFail($rideId);

        return response()->json([
            'ride_id' => $ride->id,
            'status' => $ride->status,
            'eta' => $ride->eta,
            'driver' => $ride->driver ? [
                'name' => $ride->driver->name,
                'phone' => $ride->driver->phone,
            ] : null,
            'vehicle' => $ride->vehicle ? [
                'make' => $ride->vehicle->make,
                'model' => $ride->vehicle->model,
                'plate_number' => $ride->vehicle->plate_number,
            ] : null,
            'locations' => $ride->locations,
        ]);
    }
}
