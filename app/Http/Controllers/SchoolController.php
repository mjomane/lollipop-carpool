<?php

namespace App\Http\Controllers;

use App\Models\School;

class SchoolController extends Controller
{
    public function index()
    {
        return response()->json(School::all());
    }

    public function show(School $school)
    {
        return response()->json($school);
    }
}
