<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\Resident;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    public function index(Request $request)
    {
        $societyId = auth()->user()->society_id;
        $search = $request->search;

        $visitors = Visitor::where('society_id', $societyId)
            ->when($search, function($query) use ($search){
                $query->where('visitor_name', 'LIKE', "%{$search}%")
                      ->orWhere('mobile', 'LIKE', "%{$search}%")
                      ->orWhere('status', 'LIKE', "%{$search}%");
            })
            ->with('resident')
            ->latest()
            ->paginate(10);

        return view('admin.visitors.index', compact('visitors', 'search'));
    }

    public function create()
    {
        $societyId = auth()->user()->society_id;
        $residents = Resident::where('society_id', $societyId)->get();
        return view('admin.visitors.create', compact('residents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'resident_id'  => 'required|exists:residents,id',
            'visitor_name' => 'required|string|max:255',
            'mobile'       => 'required|string|max:20',
            'purpose'      => 'required|string|max:255',
            'visit_date'   => 'required|date',
            'entry_time'   => 'required',
            'exit_time'    => 'nullable',
        ]);

        $societyId = auth()->user()->society_id;

        $visitorCode = 'V-' . rand(100000, 999999);

        $visitor = Visitor::create([
            'society_id'   => $societyId,
            'resident_id'  => $request->resident_id,
            'visitor_name' => $request->visitor_name,
            'visitor_code' => $visitorCode,
            'mobile'       => $request->mobile,
            'purpose'      => $request->purpose,
            'visit_date'   => $request->visit_date,
            'entry_time'   => $request->entry_time,
            'exit_time'    => $request->exit_time,
            'status'       => 'Checked In'
        ]);

        \App\Models\ActivityLog::log('Create Visitor manually', "Admin registered visitor {$visitor->visitor_name} for resident ID {$request->resident_id}");

        return redirect()->route('visitors.index')->with('success', 'Visitor registered successfully.');
    }

    public function qrCode($id)
    {
        $societyId = auth()->user()->society_id;
        $visitor = Visitor::where('society_id', $societyId)->findOrFail($id);

        return view('admin.visitors.qr', compact('visitor'));
    }

    public function toggleBlacklist($id)
    {
        $societyId = auth()->user()->society_id;
        $visitor = Visitor::where('society_id', $societyId)->findOrFail($id);

        if ($visitor->status === 'Rejected') {
            $visitor->update(['status' => 'Checked Out']);
            \App\Models\ActivityLog::log('Unblacklist Visitor', "Removed visitor {$visitor->visitor_name} from blacklist.");
            return back()->with('success', 'Visitor removed from blacklist.');
        } else {
            $visitor->update(['status' => 'Rejected']);
            \App\Models\ActivityLog::log('Blacklist Visitor', "Blacklisted visitor {$visitor->visitor_name}.");
            return back()->with('success', 'Visitor blacklisted successfully!');
        }
    }
}
