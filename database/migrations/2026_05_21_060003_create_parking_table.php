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
        Schema::create('parking_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('society_id')->constrained()->onDelete('cascade');
            $table->string('slot_number');
            $table->string('vehicle_type'); // 2-wheeler, 4-wheeler
            $table->string('status')->default('Available'); // Available, Allocated, Reserved
            $table->foreignId('flate_id')->nullable()->constrained()->onDelete('set null'); // allocated flat
            $table->timestamps();
        });

        Schema::create('parking_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('society_id')->constrained()->onDelete('cascade');
            $table->foreignId('resident_id')->constrained()->onDelete('cascade');
            $table->string('vehicle_name');
            $table->string('vehicle_number');
            $table->string('vehicle_type'); // 2-wheeler, 4-wheeler
            $table->string('purpose'); // Resident, Guest
            $table->string('status')->default('Pending'); // Pending, Approved, Rejected
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_requests');
        Schema::dropIfExists('parking_slots');
    }
};
