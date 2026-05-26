<?php

namespace App\Http\Controllers;

use App\Models\ParkingSlot;
use App\Models\ParkingRequest;
use App\Models\Flate;
use Illuminate\Http\Request;

class ParkingController extends Controller
{
    public function index()
    {
        $societyId = auth()->user()->society_id;
        $slots = ParkingSlot::where('society_id', $societyId)->with('flat.building')->get();
        $requests = ParkingRequest::where('society_id', $societyId)->with('resident.flats')->where('status', 'Pending')->get();
        $flats = Flate::where('society_id', $societyId)->get();

        return view('admin.parking.index', compact('slots', 'requests', 'flats'));
    }

    public function storeSlot(Request $request)
    {
        $request->validate([
            'slot_number' => 'required|string|max:50',
            'vehicle_type' => 'required|in:2-wheeler,4-wheeler',
        ]);

        $societyId = auth()->user()->society_id;

        ParkingSlot::create([
            'society_id' => $societyId,
            'slot_number' => $request->slot_number,
            'vehicle_type' => $request->vehicle_type,
            'status' => 'Available'
        ]);

        \App\Models\ActivityLog::log('Create Parking Slot', "Created slot {$request->slot_number}");

        return redirect()->route('admin.parking')->with('success', 'Parking slot added successfully!');
    }

    public function allocateSlot(Request $request, $id)
    {
        $request->validate([
            'flate_id' => 'required|exists:flates,id'
        ]);

        $societyId = auth()->user()->society_id;
        $slot = ParkingSlot::where('society_id', $societyId)->findOrFail($id);

        $slot->update([
            'flate_id' => $request->flate_id,
            'status' => 'Allocated'
        ]);

        \App\Models\ActivityLog::log('Allocate Parking Slot', "Allocated slot {$slot->slot_number} to flat ID {$request->flate_id}");

        return redirect()->route('admin.parking')->with('success', 'Parking slot allocated successfully!');
    }

    public function releaseSlot($id)
    {
        $societyId = auth()->user()->society_id;
        $slot = ParkingSlot::where('society_id', $societyId)->findOrFail($id);

        $slot->update([
            'flate_id' => null,
            'status' => 'Available'
        ]);

        \App\Models\ActivityLog::log('Release Parking Slot', "Released parking slot {$slot->slot_number}");

        return redirect()->route('admin.parking')->with('success', 'Parking slot released.');
    }

    public function approveRequest($id)
    {
        $societyId = auth()->user()->society_id;
        $parkingRequest = ParkingRequest::where('society_id', $societyId)->findOrFail($id);

        $parkingRequest->update(['status' => 'Approved']);

        \App\Models\ActivityLog::log('Approve Parking Request', "Approved parking request for vehicle: {$parkingRequest->vehicle_number}");

        return redirect()->route('admin.parking')->with('success', 'Parking request approved successfully!');
    }

    public function rejectRequest($id)
    {
        $societyId = auth()->user()->society_id;
        $parkingRequest = ParkingRequest::where('society_id', $societyId)->findOrFail($id);

        $parkingRequest->update(['status' => 'Rejected']);

        \App\Models\ActivityLog::log('Reject Parking Request', "Rejected parking request for vehicle: {$parkingRequest->vehicle_number}");

        return redirect()->route('admin.parking')->with('success', 'Parking request rejected.');
    }
}
