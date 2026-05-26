<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flate;
use App\Models\Building;

class FlateController extends Controller
{
   public function index(Request $request)
   {
        $societyId = auth()->user()->society_id;
        $search = $request->search;

        $flats = Flate::where('society_id', $societyId)
            ->when($search, function($query) use ($search){
                $query->where('flate_number', 'LIKE', "%{$search}%")
                      ->orWhere('floor', 'LIKE', "%{$search}%")
                      ->orWhere('owner_name', 'LIKE', "%{$search}%");
            })
            ->with('building')
            ->paginate(10);

        return view('admin.flats.index', compact('flats', 'search'));
   }

    public function create()
    {
        $societyId = auth()->user()->society_id;
        $buildings = Building::where('society_id', $societyId)->get();
        return view('admin.flats.create', compact('buildings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'flate_number' => 'required|string|max:255',
            'floor' => 'required|integer',
            'owner_name' => 'nullable|string|max:255',
            'owner_phone' => 'nullable|string|max:50',
            'owner_email' => 'nullable|email|max:255',
            'status' => 'required|in:occupied,vacant,self-occupied',
        ]);

        $societyId = auth()->user()->society_id;

        $flat = Flate::create([
            'society_id' => $societyId,
            'building_id'   => $request->building_id,
            'flate_number' => $request->flate_number,
            'floor'        => $request->floor,
            'owner_name' => $request->owner_name,
            'owner_phone' => $request->owner_phone,
            'owner_email' => $request->owner_email,
            'status' => $request->status,
        ]);

        \App\Models\ActivityLog::log('Create Flat', "Created flat {$flat->flate_number} in building ID {$flat->building_id}");

        return redirect()->route('flats.index')->with('success', 'Flat registered successfully!');
    }

    public function edit($id)
    {
        $societyId = auth()->user()->society_id;
        $flate = Flate::where('society_id', $societyId)->findOrFail($id);
        $buildings = Building::where('society_id', $societyId)->get();
        return view('admin.flats.edit', compact('flate', 'buildings'));
    }

    public function update(Request $request, $id)
    {
        $societyId = auth()->user()->society_id;
        $flate = Flate::where('society_id', $societyId)->findOrFail($id);

        $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'flate_number' => 'required|string|max:255',
            'floor' => 'required|integer',
            'owner_name' => 'nullable|string|max:255',
            'owner_phone' => 'nullable|string|max:50',
            'owner_email' => 'nullable|email|max:255',
            'status' => 'required|in:occupied,vacant,self-occupied',
        ]);

        $flate->update($request->all());

        \App\Models\ActivityLog::log('Update Flat', "Updated flat profile: {$flate->flate_number}");

        return redirect()->route('flats.index')->with('success', 'Flat updated successfully!');
    }

    public function destroy($id)
    {
        $societyId = auth()->user()->society_id;
        $flate = Flate::where('society_id', $societyId)->findOrFail($id);
        $flate->delete();

        \App\Models\ActivityLog::log('Delete Flat', "Deleted flat from system.");

        return redirect()->route('flats.index')->with('success', 'Flat deleted!');
    }
}
