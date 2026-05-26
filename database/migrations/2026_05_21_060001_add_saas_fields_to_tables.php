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
        // 1. Add society_id to users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('society_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->string('phone')->nullable()->after('email');
            $table->string('avatar')->nullable()->after('password');
            $table->string('otp')->nullable()->after('avatar');
            $table->timestamp('otp_expires_at')->nullable()->after('otp');
            $table->string('status')->default('active')->after('role'); // active, inactive
        });

        // 2. Add society_id to buildings
        Schema::table('buildings', function (Blueprint $table) {
            $table->foreignId('society_id')->nullable()->after('id')->constrained()->onDelete('cascade');
        });

        // 3. Add society_id to flates (flats)
        Schema::table('flates', function (Blueprint $table) {
            $table->foreignId('society_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->string('owner_name')->nullable()->after('flate_number');
            $table->string('owner_phone')->nullable()->after('owner_name');
            $table->string('owner_email')->nullable()->after('owner_phone');
            $table->string('status')->default('vacant')->after('floor'); // occupied, vacant, self-occupied
        });

        // 4. Add fields to residents
        Schema::table('residents', function (Blueprint $table) {
            $table->foreignId('society_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->after('flate_id')->constrained()->onDelete('cascade');
        });

        // 5. Add fields to complaints
        Schema::table('complaints', function (Blueprint $table) {
            $table->foreignId('society_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->string('category')->default('General')->after('title');
            $table->string('priority')->default('Medium')->after('category'); // Low, Medium, High
            $table->string('attachment')->nullable()->after('description');
            $table->text('admin_reply')->nullable()->after('status');
        });

        // 6. Add fields to maintenances
        Schema::table('maintenances', function (Blueprint $table) {
            $table->foreignId('society_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->decimal('late_fee', 10, 2)->default(0.00)->after('amount');
            $table->string('invoice_pdf')->nullable()->after('payment_status');
        });

        // 7. Add fields to notices
        Schema::table('notices', function (Blueprint $table) {
            $table->foreignId('society_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->string('category')->default('Announcement')->after('title'); // Announcement, Emergency, Event
            $table->string('attachment')->nullable()->after('description');
            $table->timestamp('scheduled_at')->nullable()->after('notice_date');
        });

        // 8. Add fields to visitors
        Schema::table('visitors', function (Blueprint $table) {
            $table->foreignId('society_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->string('visitor_code')->nullable()->after('visitor_name');
            $table->string('vehicle_number')->nullable()->after('mobile');
            $table->string('delivery_company')->nullable()->after('purpose'); // Amazon, FedEx, etc.
            $table->string('status')->default('Pending Approval')->after('exit_time'); // Pending Approval, Approved, Checked In, Checked Out, Rejected
            $table->string('photo')->nullable()->after('status');
            $table->string('otp')->nullable()->after('photo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse visitors
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropForeign(['society_id']);
            $table->dropColumn(['society_id', 'visitor_code', 'vehicle_number', 'delivery_company', 'status', 'photo', 'otp']);
        });

        // Reverse notices
        Schema::table('notices', function (Blueprint $table) {
            $table->dropForeign(['society_id']);
            $table->dropColumn(['society_id', 'category', 'attachment', 'scheduled_at']);
        });

        // Reverse maintenances
        Schema::table('maintenances', function (Blueprint $table) {
            $table->dropForeign(['society_id']);
            $table->dropColumn(['society_id', 'late_fee', 'invoice_pdf']);
        });

        // Reverse complaints
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropForeign(['society_id']);
            $table->dropColumn(['society_id', 'category', 'priority', 'attachment', 'admin_reply']);
        });

        // Reverse residents
        Schema::table('residents', function (Blueprint $table) {
            $table->dropForeign(['society_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['society_id', 'user_id']);
        });

        // Reverse flates
        Schema::table('flates', function (Blueprint $table) {
            $table->dropForeign(['society_id']);
            $table->dropColumn(['society_id', 'owner_name', 'owner_phone', 'owner_email', 'status']);
        });

        // Reverse buildings
        Schema::table('buildings', function (Blueprint $table) {
            $table->dropForeign(['society_id']);
            $table->dropColumn(['society_id']);
        });

        // Reverse users
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['society_id']);
            $table->dropColumn(['society_id', 'phone', 'avatar', 'otp', 'otp_expires_at', 'status']);
        });
    }
};
