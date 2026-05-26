<?php

namespace App\Http\Controllers;

use App\Models\Society;
use App\Models\User;
use App\Models\Complaint;
use App\Models\Payment;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        $totalSocieties = Society::count();
        $totalUsers = User::count();
        $activePlans = Society::where('status', 'active')->count();
        
        $totalRevenue = Payment::where('status', 'success')->sum('amount');
        
        $societies = Society::latest()->take(5)->get();
        $logs = ActivityLog::with('user')->latest()->take(10)->get();

        return view('super-admin.dashboard', compact(
            'totalSocieties',
            'totalUsers',
            'activePlans',
            'totalRevenue',
            'societies',
            'logs'
        ));
    }

    public function societies()
    {
        $societies = Society::withCount(['users', 'buildings'])->paginate(10);
        return view('super-admin.societies.index', compact('societies'));
    }

    public function createSociety()
    {
        return view('super-admin.societies.create');
    }

    public function storeSociety(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:societies,code|max:50',
            'address' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'subscription_plan' => 'required|in:Basic,Premium,Elite',
            'expires_at' => 'required|date',
            
            // Admin user details
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|min:6',
        ]);

        $society = Society::create($request->only(
            'name', 'code', 'address', 'email', 'phone', 'subscription_plan', 'expires_at'
        ));

        // Create the primary Society Admin
        User::create([
            'society_id' => $society->id,
            'name' => $request->admin_name,
            'email' => $request->admin_email,
            'password' => bcrypt($request->admin_password),
            'role' => 'admin',
            'status' => 'active'
        ]);

        ActivityLog::log('Create Society', "Created new society: {$society->name} with primary admin.");

        return redirect()->route('super-admin.societies')->with('success', 'Society and Admin account created successfully!');
    }

    public function editSociety($id)
    {
        $society = Society::findOrFail($id);
        return view('super-admin.societies.edit', compact('society'));
    }

    public function updateSociety(Request $request, $id)
    {
        $society = Society::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:societies,code,' . $society->id,
            'address' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'subscription_plan' => 'required|in:Basic,Premium,Elite',
            'status' => 'required|in:active,inactive',
            'expires_at' => 'required|date',
        ]);

        $society->update($request->all());

        ActivityLog::log('Update Society', "Updated society: {$society->name}");

        return redirect()->route('super-admin.societies')->with('success', 'Society updated successfully!');
    }

    public function activityLogs()
    {
        $logs = ActivityLog::with('user')->latest()->paginate(15);
        return view('super-admin.logs', compact('logs'));
    }
}
