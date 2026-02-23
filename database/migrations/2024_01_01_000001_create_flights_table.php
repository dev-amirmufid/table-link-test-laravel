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
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->string('airline_name');
            $table->string('flight_number');
            $table->time('departure_time');
            $table->decimal('price', 10, 2);
            $table->string('departure_airport', 10);
            $table->string('arrival_airport', 10);
            $table->string('flight_type')->default('one-way');
            $table->string('class_type')->default('economy');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
