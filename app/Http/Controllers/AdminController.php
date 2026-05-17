<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\School;

class AdminController extends Controller
{
    public function pendingDrivers()
    {
        return response()->json(User::where('role', 'driver')->where('is_verified', false)->get());
    }

    public function approveDriver(Request $request, User $driver)
    {
        $driver->update(['is_verified' => true]);
        return response()->json($driver);
    }

    public function rejectDriver(Request $request, User $driver)
    {
        $driver->update(['is_verified' => false]);
        return response()->json($driver);
    }

    public function storeSchool(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:25',
            'email' => 'nullable|email|max:255',
            'type' => 'nullable|string|max:100',
            'zone' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $school = School::create($data);
        return response()->json($school, 201);
    }

    public function updateSchool(Request $request, School $school)
    {
        $school->update($request->only(['name', 'address', 'city', 'state', 'postal_code', 'phone', 'email', 'type', 'zone', 'notes']));
        return response()->json($school);
    }
}
