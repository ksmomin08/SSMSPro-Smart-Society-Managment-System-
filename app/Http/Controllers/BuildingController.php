<?php

namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    public function index(Request $request)
    {
        $societyId = auth()->user()->society_id;
        $search = $request->search;

        $buildings = Building::where('society_id', $societyId)
            ->when($search, function($query) use ($search){
                $query->where('building_name', 'LIKE', "%{$search}%")
                      ->orWhere('building_code', 'LIKE', "%{$search}%");
            })->paginate(10);

        return view('admin.buildings.index', compact('buildings', 'search'));
    }

    public function create()
    {
        return view('admin.buildings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'building_name' => 'required|max:255',
            'building_code' => 'required|max:100',
        ]);

        $societyId = auth()->user()->society_id;

        $building = Building::create([
            'society_id' => $societyId,
            'building_name' => $request->building_name,
            'building_code'  => $request->building_code
        ]);

        \App\Models\ActivityLog::log('Create Building', "Created building {$building->building_name}");

        return response()->json([
            'status' => true,
            'message' => 'Building Added Successfully'
        ]);
    }

    public function edit($id)
    {
        $societyId = auth()->user()->society_id;
        $building = Building::where('society_id', $societyId)->findOrFail($id);

        return view('admin.buildings.edit', compact('building'));
    }

    public function update(Request $request, $id)
    {
        $societyId = auth()->user()->society_id;
        $building = Building::where('society_id', $societyId)->findOrFail($id);

        $request->validate([
            'building_name' => 'required|max:255',
            'building_code' => 'required|max:100',
        ]);

        $building->update([
            'building_name' => $request->building_name,
            'building_code' => $request->building_code,
        ]);

        \App\Models\ActivityLog::log('Update Building', "Updated building: {$building->building_name}");

        return redirect()->route('buildings.index')->with('success', 'Building updated successfully!');
    }

    public function destroy($id)
    {
        $societyId = auth()->user()->society_id;
        $building = Building::where('society_id', $societyId)->findOrFail($id);
        $building->delete();

        \App\Models\ActivityLog::log('Delete Building', "Deleted building.");

        return redirect()->route('buildings.index')->with('success', 'Building deleted successfully!');
    }
}
