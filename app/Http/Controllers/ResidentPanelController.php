<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use App\Models\Maintenance;
use App\Models\Complaint;
use App\Models\Visitor;
use App\Models\ParkingSlot;
use App\Models\ParkingRequest;
use App\Models\Amenity;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ResidentPanelController extends Controller
{
    protected function getResident()
    {
        $resident = auth()->user()->resident;
        if (!$resident) {
            abort(403, 'Resident profile not associated with this user account.');
        }
        return $resident;
    }

    public function dashboard()
    {
        $resident = $this->getResident();
        $societyId = $resident->society_id;

        $notices = Notice::where('society_id', $societyId)
            ->where(function($q) {
                $q->whereNull('scheduled_at')->orWhere('scheduled_at', '<=', now());
            })
            ->latest()->take(5)->get();

        $maintenances = Maintenance::where('resident_id', $resident->id)->latest()->take(5)->get();
        $complaints = Complaint::where('resident_id', $resident->id)->latest()->take(5)->get();
        $visitors = Visitor::where('resident_id', $resident->id)->latest()->take(5)->get();
        $bookings = Booking::where('resident_id', $resident->id)->with('amenity')->latest()->take(5)->get();

        $activeParkingRequests = ParkingRequest::where('resident_id', $resident->id)->latest()->get();
        $allocatedSlots = ParkingSlot::where('flate_id', $resident->flate_id)->get();
        $amenities = Amenity::where('society_id', $societyId)->where('status', 'Active')->get();

        return view('resident.dashboard', compact(
            'resident',
            'notices',
            'maintenances',
            'complaints',
            'visitors',
            'bookings',
            'activeParkingRequests',
            'allocatedSlots',
            'amenities'
        ));
    }

    // Raise Complaint with Mock AI Helper
    public function raiseComplaint(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'attachment' => 'nullable|image|max:2048'
        ]);

        $resident = $this->getResident();
        
        // AI Category and Priority heuristic classifier
        $text = strtolower($request->title . ' ' . $request->description);
        $category = 'General';
        $priority = 'Medium';

        if (preg_match('/pipe|leak|plumb|water|tap|sink|toilet/', $text)) {
            $category = 'Plumbing';
            $priority = 'High';
        } elseif (preg_match('/wire|power|light|fuse|spark|electricity|switch|fan/', $text)) {
            $category = 'Electrical';
            $priority = 'High';
        } elseif (preg_match('/gate|guard|theft|rob|steal|stranger|camera|cctv|lock/', $text)) {
            $category = 'Security';
            $priority = 'Critical';
        } elseif (preg_match('/dust|sweep|clean|trash|garbage|dirt|smell/', $text)) {
            $category = 'Cleaning';
            $priority = 'Low';
        }

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('complaints', 'public');
        }

        $complaint = Complaint::create([
            'society_id' => $resident->society_id,
            'resident_id' => $resident->id,
            'title' => $request->title,
            'category' => $category,
            'priority' => $priority,
            'description' => $request->description,
            'attachment' => $attachmentPath,
            'status' => 'pending'
        ]);

        ActivityLog::log('Raise Complaint', "Resident raised ticket: {$complaint->title}. AI Categorized: {$category}");

        return response()->json([
            'status' => true,
            'message' => 'Complaint raised successfully!',
            'suggested_category' => $category,
            'suggested_priority' => $priority,
            'complaint' => $complaint
        ]);
    }

    // Pre-approve Visitor
    public function preApproveVisitor(Request $request)
    {
        $request->validate([
            'visitor_name' => 'required|string|max:255',
            'mobile' => 'required|string',
            'purpose' => 'required|string',
            'visit_date' => 'required|date',
            'vehicle_number' => 'nullable|string',
        ]);

        $resident = $this->getResident();
        $visitorCode = 'PASS-' . rand(100000, 999999);

        $visitor = Visitor::create([
            'society_id' => $resident->society_id,
            'resident_id' => $resident->id,
            'visitor_name' => $request->visitor_name,
            'visitor_code' => $visitorCode,
            'mobile' => $request->mobile,
            'vehicle_number' => $request->vehicle_number,
            'purpose' => $request->purpose,
            'visit_date' => $request->visit_date,
            'entry_time' => '00:00:00',
            'status' => 'Approved', // Pre-approved is already Approved
        ]);

        ActivityLog::log('Pre-approve Visitor', "Resident pre-approved visitor {$visitor->visitor_name} with code {$visitorCode}");

        return response()->json([
            'status' => true,
            'message' => 'Visitor pre-approved successfully! Share the Pass Code.',
            'visitor_code' => $visitorCode
        ]);
    }

    // Poll for pending visitor requests at gate
    public function checkPendingVisitor()
    {
        $resident = $this->getResident();
        
        $visitor = Visitor::where('resident_id', $resident->id)
            ->where('status', 'Pending Approval')
            ->first();

        if ($visitor) {
            return response()->json([
                'status' => true,
                'has_visitor' => true,
                'visitor' => $visitor
            ]);
        }

        return response()->json([
            'status' => true,
            'has_visitor' => false
        ]);
    }

    // Action on visitor (Approve/Reject)
    public function actionVisitor(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:Approved,Rejected'
        ]);

        $resident = $this->getResident();
        $visitor = Visitor::where('resident_id', $resident->id)->findOrFail($id);

        $visitor->update([
            'status' => $request->action
        ]);

        ActivityLog::log('Gate Approval Decision', "Resident {$request->action} visitor {$visitor->visitor_name}");

        return response()->json([
            'status' => true,
            'message' => "Visitor entry has been {$request->action}."
        ]);
    }

    // Simulated Razorpay bill payment
    public function payBill(Request $request, $id)
    {
        $request->validate([
            'payment_method' => 'required|string',
            'transaction_id' => 'required|string'
        ]);

        $resident = $this->getResident();
        $bill = Maintenance::where('resident_id', $resident->id)->findOrFail($id);

        $bill->update([
            'payment_status' => 'paid'
        ]);

        $receiptNumber = 'REC-' . time() . '-' . rand(10, 99);

        $payment = Payment::create([
            'maintenance_id' => $bill->id,
            'resident_id' => $resident->id,
            'amount' => $bill->amount + $bill->late_fee,
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'status' => 'success',
            'receipt_number' => $receiptNumber
        ]);

        ActivityLog::log('Bill Paid', "Resident paid bill for {$bill->month} via {$request->payment_method}. Receipt: {$receiptNumber}");

        return response()->json([
            'status' => true,
            'message' => 'Bill paid successfully! Receipt generated.',
            'payment' => $payment
        ]);
    }

    // Invoice/Receipt PDF Download via DomPDF
    public function downloadReceipt($id)
    {
        $payment = Payment::with(['maintenance', 'resident.flats.building'])->findOrFail($id);
        
        $pdf = Pdf::loadView('pdf.receipt', compact('payment'));
        
        return $pdf->download("Receipt_{$payment->receipt_number}.pdf");
    }

    // Parking request raise
    public function requestParking(Request $request)
    {
        $request->validate([
            'vehicle_name' => 'required|string|max:100',
            'vehicle_number' => 'required|string|max:50',
            'vehicle_type' => 'required|in:2-wheeler,4-wheeler',
        ]);

        $resident = $this->getResident();

        $parkingRequest = ParkingRequest::create([
            'society_id' => $resident->society_id,
            'resident_id' => $resident->id,
            'vehicle_name' => $request->vehicle_name,
            'vehicle_number' => $request->vehicle_number,
            'vehicle_type' => $request->vehicle_type,
            'purpose' => 'Resident',
            'status' => 'Pending'
        ]);

        ActivityLog::log('Parking Request Raised', "Resident requested parking for {$request->vehicle_number}");

        return response()->json([
            'status' => true,
            'message' => 'Parking slot request submitted successfully!'
        ]);
    }

    // Amenity Booking raise
    public function bookAmenity(Request $request)
    {
        $request->validate([
            'amenity_id' => 'required|exists:amenities,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        $resident = $this->getResident();

        // Check if there is an overlapping approved booking for this slot
        $overlap = Booking::where('amenity_id', $request->amenity_id)
            ->where('booking_date', $request->booking_date)
            ->where('status', 'Approved')
            ->where(function($q) use ($request) {
                $q->whereBetween('start_time', [$request->start_time, $request->end_time])
                  ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                  ->orWhere(function($sub) use ($request) {
                      $sub->where('start_time', '<=', $request->start_time)
                          ->where('end_time', '>=', $request->end_time);
                  });
            })->exists();

        if ($overlap) {
            return response()->json([
                'status' => false,
                'message' => 'The selected timeslot is already booked by another resident.'
            ]);
        }

        Booking::create([
            'society_id' => $resident->society_id,
            'amenity_id' => $request->amenity_id,
            'resident_id' => $resident->id,
            'booking_date' => $request->booking_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => 'Pending'
        ]);

        ActivityLog::log('Amenity Booked', "Resident requested facility booking ID {$request->amenity_id}");

        return response()->json([
            'status' => true,
            'message' => 'Amenity booking submitted! Waiting for admin approval.'
        ]);
    }
}
