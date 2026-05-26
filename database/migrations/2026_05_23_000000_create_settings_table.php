<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            
            // Branding details
            $table->string('website_title')->default('Smart Society Management');
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            
            // Dynamic Accent Engine
            $table->string('primary_color')->default('#566a7f');
            $table->string('secondary_color')->default('#697a8d');
            $table->string('sidebar_bg_color')->default('#111c2b');
            
            // SMTP Configurations
            $table->string('mail_host')->nullable();
            $table->string('mail_port')->default('587');
            $table->string('mail_username')->nullable();
            $table->string('mail_password')->nullable();
            $table->string('mail_encryption')->default('tls');
            $table->string('mail_from_address')->nullable();
            
            // Dynamic Announcements / Board
            $table->text('announcement_text')->nullable();
            $table->boolean('announcement_status')->default(false);
            
            $table->timestamps();
        });

        // Seed Default Configurations
        DB::table('settings')->insert([
            'website_title' => 'Smart Society Management',
            'logo' => null,
            'favicon' => null,
            'contact_email' => 'admin@smartsociety.com',
            'contact_phone' => '+919876543210',
            'primary_color' => '#566a7f',
            'secondary_color' => '#697a8d',
            'sidebar_bg_color' => '#111c2b',
            'mail_host' => 'smtp.mailtrap.io',
            'mail_port' => '2525',
            'mail_username' => 'test_user',
            'mail_password' => 'test_password',
            'mail_encryption' => 'tls',
            'mail_from_address' => 'noreply@smartsociety.com',
            'announcement_text' => '⚠️ Emergency Notice: Annual general society meeting scheduled on Sunday, June 1st at 10:00 AM.',
            'announcement_status' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
