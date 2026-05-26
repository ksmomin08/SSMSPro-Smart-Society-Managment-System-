<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Flate;
use App\Models\Resident;
use App\Models\Complaint;
use App\Models\Maintenance;
use App\Models\Visitor;
use App\Models\ParkingSlot;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $societyId = auth()->user()->society_id;

        $totalBuildings = Building::where('society_id', $societyId)->count();
        $totalFlats = Flate::where('society_id', $societyId)->count();
        $totalResidents = Resident::where('society_id', $societyId)->count();
        
        $totalComplaints = Complaint::where('society_id', $societyId)->count();
        $pendingComplaints = Complaint::where('society_id', $societyId)->where('status', 'pending')->count();
        
        $totalMaintenances = Maintenance::where('society_id', $societyId)->count();
        
        $totalRevenue = Payment::whereHas('resident', function($q) use ($societyId) {
            $q->where('society_id', $societyId);
        })->where('status', 'success')->sum('amount');

        $totalPendingBills = Maintenance::where('society_id', $societyId)->where('payment_status', 'pending')->sum('amount');
        
        $totalVisitors = Visitor::where('society_id', $societyId)->count();
        $activeVisitors = Visitor::where('society_id', $societyId)->whereIn('status', ['Pending Approval', 'Checked In'])->count();

        $allocatedParking = ParkingSlot::where('society_id', $societyId)->where('status', 'Allocated')->count();
        $totalParking = ParkingSlot::where('society_id', $societyId)->count();

        $recentComplaints = Complaint::where('society_id', $societyId)->with('resident')->latest()->take(5)->get();
        $recentVisitors = Visitor::where('society_id', $societyId)->with('resident')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalBuildings',
            'totalFlats',
            'totalResidents',
            'totalComplaints',
            'pendingComplaints',
            'totalMaintenances',
            'totalRevenue',
            'totalPendingBills',
            'totalVisitors',
            'activeVisitors',
            'allocatedParking',
            'totalParking',
            'recentComplaints',
            'recentVisitors'
        ));
    }

    // Guard Staff Management CRUD
    public function guards()
    {
        $societyId = auth()->user()->society_id;
        $guards = User::where('society_id', $societyId)->where('role', 'guard')->paginate(10);
        return view('admin.guards.index', compact('guards'));
    }

    public function createGuard()
    {
        return view('admin.guards.create');
    }

    public function storeGuard(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string',
            'password' => 'required|min:6',
        ]);

        User::create([
            'society_id' => auth()->user()->society_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'role' => 'guard',
            'status' => 'active'
        ]);

        \App\Models\ActivityLog::log('Create Guard', "Registered guard staff: {$request->name}");

        return redirect()->route('admin.guards')->with('success', 'Security Guard registered successfully!');
    }

    public function editGuard($id)
    {
        $guard = User::where('society_id', auth()->user()->society_id)->where('role', 'guard')->findOrFail($id);
        return view('admin.guards.edit', compact('guard'));
    }

    public function updateGuard(Request $request, $id)
    {
        $guard = User::where('society_id', auth()->user()->society_id)->where('role', 'guard')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $guard->id,
            'phone' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        $guard->update($request->only('name', 'email', 'phone', 'status'));

        if ($request->password) {
            $guard->update(['password' => bcrypt($request->password)]);
        }

        \App\Models\ActivityLog::log('Update Guard', "Updated guard staff account: {$guard->name}");

        return redirect()->route('admin.guards')->with('success', 'Guard account updated successfully!');
    }

    public function destroyGuard($id)
    {
        $guard = User::where('society_id', auth()->user()->society_id)->where('role', 'guard')->findOrFail($id);
        $guard->delete();

        \App\Models\ActivityLog::log('Delete Guard', "Deleted guard account.");

        return redirect()->route('admin.guards')->with('success', 'Guard account deleted!');
    }
}
