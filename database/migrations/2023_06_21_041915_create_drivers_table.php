<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('lincenceNo');
            $table->string('phone');
            $table->string('email');
            $table->enum('status', ['offline', 'active', 'looking', 'driving', 'arrived']);
            $table->enum('accountStatus', ['ideal', 'disabled']);
            $table->string('photo');
            $table->string('password');
            $table->string('drivingLincence');
            $table->double('current_latitude');
            $table->double('current_longitude');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
