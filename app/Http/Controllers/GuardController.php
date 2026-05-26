<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\Resident;
use App\Models\Flate;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class GuardController extends Controller
{
    public function dashboard(Request $request)
    {
        $societyId = auth()->user()->society_id;
        $search = $request->search;

        $visitors = Visitor::where('society_id', $societyId)
            ->when($search, function($q) use ($search) {
                $q->where('visitor_name', 'LIKE', "%{$search}%")
                  ->orWhere('mobile', 'LIKE', "%{$search}%")
                  ->orWhere('vehicle_number', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->paginate(15);

        $residents = Resident::where('society_id', $societyId)->with('flats')->get();

        $activeCheckedIn = Visitor::where('society_id', $societyId)->where('status', 'Checked In')->count();
        $pendingApprovals = Visitor::where('society_id', $societyId)->where('status', 'Pending Approval')->count();

        return view('guard.dashboard', compact('visitors', 'residents', 'activeCheckedIn', 'pendingApprovals', 'search'));
    }

    public function storeVisitor(Request $request)
    {
        $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'visitor_name' => 'required|string|max:255',
            'mobile' => 'required|string',
            'purpose' => 'required|string',
            'vehicle_number' => 'nullable|string',
            'delivery_company' => 'nullable|string',
            'photo' => 'nullable|string', // Base64 data from webcam
        ]);

        $societyId = auth()->user()->society_id;

        // Auto-generate verification code and mock OTP for visitor
        $visitorCode = 'V-' . rand(100000, 999999);
        $otp = rand(1000, 9999);

        $photoPath = null;
        if ($request->photo) {
            // Save webcam simulated photo
            $imageData = $request->photo;
            $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
            $imageData = str_replace('data:image/png;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            $imageName = 'visitor_' . time() . '.png';
            \Storage::disk('public')->put('visitors/' . $imageName, base64_decode($imageData));
            $photoPath = '/storage/visitors/' . $imageName;
        }

        $visitor = Visitor::create([
            'society_id' => $societyId,
            'resident_id' => $request->resident_id,
            'visitor_name' => $request->visitor_name,
            'visitor_code' => $visitorCode,
            'mobile' => $request->mobile,
            'vehicle_number' => $request->vehicle_number,
            'purpose' => $request->purpose,
            'delivery_company' => $request->delivery_company,
            'visit_date' => date('Y-m-d'),
            'entry_time' => date('H:i:s'),
            'status' => 'Pending Approval', // Initially waiting for resident approval
            'photo' => $photoPath,
            'otp' => $otp,
        ]);

        ActivityLog::log('Visitor Logged', "Guard registered visitor {$visitor->visitor_name} for resident ID {$request->resident_id}");

        return response()->json([
            'status' => true,
            'message' => 'Visitor entry logged. Waiting for resident approval.',
            'visitor' => $visitor
        ]);
    }

    public function checkVisitorStatus($id)
    {
        $visitor = Visitor::findOrFail($id);
        
        return response()->json([
            'status' => true,
            'visitor_status' => $visitor->status,
        ]);
    }

    public function checkInVisitor(Request $request, $id)
    {
        $visitor = Visitor::findOrFail($id);
        $visitor->update([
            'status' => 'Checked In',
            'entry_time' => date('H:i:s')
        ]);

        ActivityLog::log('Visitor Checked In', "Visitor {$visitor->visitor_name} checked in at gate.");

        return response()->json([
            'status' => true,
            'message' => 'Visitor successfully checked in!'
        ]);
    }

    public function checkoutVisitor($id)
    {
        $visitor = Visitor::findOrFail($id);
        $visitor->update([
            'status' => 'Checked Out',
            'exit_time' => date('H:i:s')
        ]);

        ActivityLog::log('Visitor Checked Out', "Visitor {$visitor->visitor_name} checked out.");

        return back()->with('success', 'Visitor checked out successfully.');
    }

    // Pre-approved pass scanning or code verification
    public function verifyPass(Request $request)
    {
        $request->validate([
            'pass_code' => 'required|string',
        ]);

        $societyId = auth()->user()->society_id;
        
        $visitor = Visitor::where('society_id', $societyId)
            ->where('visitor_code', $request->pass_code)
            ->where('status', 'Approved')
            ->first();

        if ($visitor) {
            $visitor->update([
                'status' => 'Checked In',
                'entry_time' => date('H:i:s')
            ]);

            ActivityLog::log('Visitor Pass Scanned', "Pre-approved pass {$request->pass_code} checked in.");

            return response()->json([
                'status' => true,
                'message' => "Welcome! Pre-approved Visitor {$visitor->visitor_name} has been Checked In.",
                'visitor' => $visitor
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid or unauthorized Pass Code.'
        ]);
    }

    // Emergency Panic Broadcast
    public function triggerAlert(Request $request)
    {
        $request->validate([
            'type' => 'required|string', // Fire, Medical, Security
            'description' => 'required|string',
        ]);

        $societyId = auth()->user()->society_id;
        
        // Simulating emergency push notification / system log
        ActivityLog::log('EMERGENCY TRIGGERED', "Guard triggered {$request->type} panic alert: {$request->description}");

        // Save emergency state in session or DB log for active polling
        session()->flash('emergency_alert', [
            'type' => $request->type,
            'description' => $request->description,
            'timestamp' => date('h:i A')
        ]);

        return response()->json([
            'status' => true,
            'message' => 'EMERGENCY BROADCAST TRIGGERED! All residents notified.'
        ]);
    }
}
