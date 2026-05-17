<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('children', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('school_id')->constrained('schools')->nullOnDelete();
            $table->string('name');
            $table->string('grade')->nullable();
            $table->date('dob')->nullable();
            $table->string('photo_url')->nullable();
            $table->text('special_needs')->nullable();
            $table->text('pickup_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('children');
    }
};
