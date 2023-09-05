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
        Schema::create('passengers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('photo')->nullable();
            $table->string('phone')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('status', ['expired', 'active', 'searching', 'riding', 'arrived']);
            $table->enum('accountStatus', ['ideal', 'disabled']);
            $table->string('current_latitude');
            $table->string('current_longitude');
            $table->rememberToken();
            $table->string('role')->default('passenger');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passengers');
    }
};
