<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;

class SchoolSeeder extends Seeder
{
    public function run()
    {
        School::upsert([
            [
                'name' => 'Lollipop Elementary',
                'address' => '100 Sunshine Way',
                'city' => 'Sample City',
                'state' => 'State',
                'postal_code' => '12345',
                'phone' => '+1234567890',
                'email' => 'info@lollipop-school.edu',
                'type' => 'public',
                'zone' => 'North',
                'notes' => 'Sample school for development',
            ],
        ], ['name'], ['address', 'city', 'state', 'postal_code', 'phone', 'email', 'type', 'zone', 'notes']);
    }
}
