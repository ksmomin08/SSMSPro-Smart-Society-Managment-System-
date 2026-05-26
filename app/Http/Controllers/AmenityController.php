<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use App\Models\Booking;
use Illuminate\Http\Request;

class AmenityController extends Controller
{
    public function index()
    {
        $societyId = auth()->user()->society_id;
        $amenities = Amenity::where('society_id', $societyId)->withCount('bookings')->get();
        return view('admin.amenities.index', compact('amenities'));
    }

    public function create()
    {
        return view('admin.amenities.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'capacity' => 'nullable|integer|min:1',
            'status' => 'required|in:Active,Maintenance',
        ]);

        $societyId = auth()->user()->society_id;

        $amenity = Amenity::create([
            'society_id' => $societyId,
            'name' => $request->name,
            'description' => $request->description,
            'capacity' => $request->capacity,
            'status' => $request->status,
        ]);

        \App\Models\ActivityLog::log('Create Amenity', "Created amenity: {$amenity->name}");

        return redirect()->route('admin.amenities')->with('success', 'Amenity added successfully!');
    }

    public function edit($id)
    {
        $societyId = auth()->user()->society_id;
        $amenity = Amenity::where('society_id', $societyId)->findOrFail($id);
        return view('admin.amenities.edit', compact('amenity'));
    }

    public function update(Request $request, $id)
    {
        $societyId = auth()->user()->society_id;
        $amenity = Amenity::where('society_id', $societyId)->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'capacity' => 'nullable|integer|min:1',
            'status' => 'required|in:Active,Maintenance',
        ]);

        $amenity->update($request->all());

        \App\Models\ActivityLog::log('Update Amenity', "Updated amenity: {$amenity->name}");

        return redirect()->route('admin.amenities')->with('success', 'Amenity updated successfully!');
    }

    public function destroy($id)
    {
        $societyId = auth()->user()->society_id;
        $amenity = Amenity::where('society_id', $societyId)->findOrFail($id);
        $amenity->delete();

        \App\Models\ActivityLog::log('Delete Amenity', "Deleted amenity.");

        return redirect()->route('admin.amenities')->with('success', 'Amenity deleted!');
    }

    // Bookings View and Approval
    public function bookings()
    {
        $societyId = auth()->user()->society_id;
        $bookings = Booking::where('society_id', $societyId)->with(['amenity', 'resident.flats'])->latest()->paginate(15);
        return view('admin.amenities.bookings', compact('bookings'));
    }

    public function approveBooking($id)
    {
        $societyId = auth()->user()->society_id;
        $booking = Booking::where('society_id', $societyId)->findOrFail($id);
        
        $booking->update(['status' => 'Approved']);

        \App\Models\ActivityLog::log('Approve Booking', "Approved amenity booking ID {$booking->id}");

        return back()->with('success', 'Booking approved successfully!');
    }

    public function rejectBooking($id)
    {
        $societyId = auth()->user()->society_id;
        $booking = Booking::where('society_id', $societyId)->findOrFail($id);

        $booking->update(['status' => 'Cancelled']);

        \App\Models\ActivityLog::log('Cancel Booking', "Cancelled amenity booking ID {$booking->id}");

        return back()->with('success', 'Booking cancelled/rejected.');
    }
}
