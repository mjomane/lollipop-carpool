<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users')->cascadeOnDelete();
            $table->string('make');
            $table->string('model');
            $table->integer('year');
            $table->string('plate_number');
            $table->integer('capacity');
            $table->integer('child_seat_count')->default(0);
            $table->string('insurance_status')->nullable();
            $table->string('registration_status')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
};
