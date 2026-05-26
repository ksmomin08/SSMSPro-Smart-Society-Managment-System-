<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\Flate;
use App\Models\User;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
    public function index(Request $request)
    {
        $societyId = auth()->user()->society_id;
        $search = $request->search;

        $residents = Resident::where('society_id', $societyId)
            ->when($search, function($query) use ($search){
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%");
            })
            ->with('flats.building')
            ->paginate(10);

        return view('admin.residents.index', compact('residents', 'search'));
    }

    public function create()
    {
        $societyId = auth()->user()->society_id;
        $flats = Flate::where('society_id', $societyId)->with('building')->get();

        return view('admin.residents.create', compact('flats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'flate_id' => 'required|exists:flates,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|unique:residents,email',
            'phone' => 'required|string|max:20',
            'family_members' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $societyId = auth()->user()->society_id;

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('residents', 'public');
        }

        // 1. Create User account for the resident
        $user = User::create([
            'society_id' => $societyId,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt('password123'), // Default temporary password
            'role' => 'resident',
            'status' => 'active'
        ]);

        // 2. Create Resident profile linking user and flat
        $resident = Resident::create([
            'society_id' => $societyId,
            'flate_id' => $request->flate_id,
            'user_id' => $user->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'family_members' => $request->family_members,
            'image' => $imagePath
        ]);

        // 3. Mark the Flat status as occupied
        $flat = Flate::findOrFail($request->flate_id);
        $flat->update(['status' => 'occupied']);

        \App\Models\ActivityLog::log('Register Resident', "Created resident: {$resident->name} and linked flat {$flat->flate_number}. Temp password: password123");

        return redirect()->route('residents.index')->with('success', 'Resident registered and login account created successfully!');
    }

    public function edit($id)
    {
        $societyId = auth()->user()->society_id;
        $resident = Resident::where('society_id', $societyId)->findOrFail($id);
        $flats = Flate::where('society_id', $societyId)->get();

        return view('admin.residents.edit', compact('resident', 'flats'));
    }

    public function update(Request $request, $id)
    {
        $societyId = auth()->user()->society_id;
        $resident = Resident::where('society_id', $societyId)->findOrFail($id);

        $request->validate([
            'flate_id' => 'required|exists:flates,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:residents,email,' . $id . '|unique:users,email,' . $resident->user_id,
            'phone' => 'required|string|max:20',
            'family_members' => 'nullable|integer|min:0',
        ]);

        // Update resident profile
        $resident->update([
            'flate_id' => $request->flate_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'family_members' => $request->family_members
        ]);

        // Update corresponding User account
        if ($resident->user) {
            $resident->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);
        }

        \App\Models\ActivityLog::log('Update Resident', "Updated resident profile: {$resident->name}");

        return redirect()->route('residents.index')->with('success', 'Resident updated successfully!');
    }

    public function destroy($id)
    {
        $societyId = auth()->user()->society_id;
        $resident = Resident::where('society_id', $societyId)->findOrFail($id);
        
        // Also delete the linked User account
        if ($resident->user) {
            $resident->user->delete();
        }

        // Reset flat status to vacant
        if ($resident->flats) {
            $resident->flats->update(['status' => 'vacant']);
        }

        $resident->delete();

        \App\Models\ActivityLog::log('Delete Resident', "Deleted resident profile.");

        return redirect()->route('residents.index')->with('success', 'Resident deleted!');
    }
}
