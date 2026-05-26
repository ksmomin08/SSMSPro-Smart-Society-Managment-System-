<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class SettingController extends Controller
{
    /**
     * Show the settings control panel.
     */
    public function index()
    {
        $settings = Setting::first();
        
        // Seed default if settings are empty for some reason
        if (!$settings) {
            $settings = Setting::create([
                'website_title' => 'Smart Society Management',
                'primary_color' => '#566a7f',
                'secondary_color' => '#697a8d',
                'sidebar_bg_color' => '#111c2b',
                'announcement_text' => '⚠️ Emergency Notice: Annual general society meeting scheduled on Sunday.',
                'announcement_status' => true
            ]);
        }

        return view('admin.settings', compact('settings'));
    }

    /**
     * Update settings and upload logo/favicon files.
     */
    public function update(Request $request)
    {
        $settings = Setting::first() ?? new Setting();

        $request->validate([
            'website_title' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,ico|max:1024',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'primary_color' => 'required|string|max:10',
            'secondary_color' => 'required|string|max:10',
            'sidebar_bg_color' => 'required|string|max:10',
            'mail_host' => 'nullable|string|max:255',
            'mail_port' => 'nullable|string|max:10',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|string|max:20',
            'mail_from_address' => 'nullable|email|max:255',
            'announcement_text' => 'nullable|string',
        ]);

        $data = $request->except(['logo', 'favicon', 'announcement_status']);

        // Handle Logo Uploader
        if ($request->hasFile('logo')) {
            if ($settings->logo) {
                Storage::disk('public')->delete($settings->logo);
            }
            $data['logo'] = $request->file('logo')->store('branding', 'public');
        }

        // Handle Favicon Uploader
        if ($request->hasFile('favicon')) {
            if ($settings->favicon) {
                Storage::disk('public')->delete($settings->favicon);
            }
            $data['favicon'] = $request->file('favicon')->store('branding', 'public');
        }

        // Handle Announcement Toggle Status
        $data['announcement_status'] = $request->has('announcement_status');

        $settings->fill($data);
        $settings->save();

        return redirect()->back()->with('success', 'Smart settings successfully updated and dynamic themes deployed!');
    }

    /**
     * Send a real-time SMTP test email by overriding configurations.
     */
    public function sendTestEmail(Request $request)
    {
        $request->validate([
            'mail_host' => 'required|string',
            'mail_port' => 'required|string',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'required|string',
            'mail_from_address' => 'required|email',
            'test_email_recipient' => 'required|email',
        ]);

        $settings = Setting::first();

        // Override Laravel Mail Transport values in memory
        config([
            'mail.mailers.smtp.host' => $request->mail_host,
            'mail.mailers.smtp.port' => $request->mail_port,
            'mail.mailers.smtp.username' => $request->mail_username,
            'mail.mailers.smtp.password' => $request->mail_password,
            'mail.mailers.smtp.encryption' => $request->mail_encryption,
            'mail.from.address' => $request->mail_from_address,
            'mail.from.name' => $settings->website_title ?? 'Smart Society Management System',
        ]);

        try {
            Mail::raw("📧 Smart Society Management System — Connection Test Email\n\nCongratulations!\nYour custom SMTP Mail Server parameters are fully and correctly aligned. The connection has been established successfully on Port " . $request->mail_port . " with " . strtoupper($request->mail_encryption) . " encryption.", function ($message) use ($request) {
                $message->to($request->test_email_recipient)
                    ->subject('📧 Mail Server SMTP Connection Test Successful');
            });

            return response()->json([
                'success' => true,
                'message' => 'Success! Connection established and test email fired to ' . $request->test_email_recipient
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'SMTP Connection Refused: ' . $e->getMessage()
            ], 500);
        }
    }
}
