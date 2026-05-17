<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ride_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ride_id')->constrained('rides')->cascadeOnDelete();
            $table->decimal('driver_lat', 10, 7);
            $table->decimal('driver_lng', 10, 7);
            $table->dateTime('timestamp');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ride_locations');
    }
};
