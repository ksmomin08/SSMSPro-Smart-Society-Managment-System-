<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $societyId = auth()->user()->society_id;
        $search = $request->search;

        // Auto-calculate and update late fees for all unpaid past-due maintenance records
        $overdueBills = Maintenance::where('society_id', $societyId)
            ->where('payment_status', 'unpaid')
            ->where('due_date', '<', now()->toDateString())
            ->get();

        foreach ($overdueBills as $bill) {
            // Auto calculate late fee (10% of primary bill)
            $expectedLateFee = $bill->amount * 0.10;
            if ($bill->late_fee != $expectedLateFee) {
                $bill->update(['late_fee' => $expectedLateFee]);
            }
        }

        $maintenances = Maintenance::where('society_id', $societyId)
            ->when($search, function($query) use ($search){
                $query->where('month', 'LIKE', "%{$search}%")
                      ->orWhere('payment_status', 'LIKE', "%{$search}%");
            })
            ->with('resident.flats')
            ->paginate(10);

        return view('admin.maintenance.index', compact('maintenances', 'search'));
    }

    public function create()
    {
        $societyId = auth()->user()->society_id;
        $residents = Resident::where('society_id', $societyId)->with('flats')->get();
        return view('admin.maintenance.create', compact('residents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'month' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'due_date' => 'required|date',
            'payment_status' => 'required|in:paid,unpaid',
        ]);

        $societyId = auth()->user()->society_id;
        $lateFee = 0.00;

        // If created as overdue, apply late fee immediately
        if ($request->payment_status === 'unpaid' && $request->due_date < now()->toDateString()) {
            $lateFee = $request->amount * 0.10;
        }

        $maintenance = Maintenance::create([
            'society_id'     => $societyId,
            'resident_id'    => $request->resident_id,
            'month'          => $request->month,
            'amount'         => $request->amount,
            'late_fee'       => $lateFee,
            'due_date'       => $request->due_date,
            'payment_status' => $request->payment_status,
        ]);

        \App\Models\ActivityLog::log('Generate Bill', "Generated monthly bill for resident ID {$request->resident_id} of amount {$request->amount} for month {$request->month}");

        return redirect()->route('maintenances.index')->with('success', 'Maintenance record created successfully.');
    }

    public function changeStatus($id)
    {
        $societyId = auth()->user()->society_id;
        $maintenance = Maintenance::where('society_id', $societyId)->findOrFail($id);

        if ($maintenance->payment_status === 'unpaid') {
            $maintenance->payment_status = 'paid';
            \App\Models\ActivityLog::log('Bill Paid manually', "Admin marked bill ID {$id} as Paid.");
        } else {
            $maintenance->payment_status = 'unpaid';
            \App\Models\ActivityLog::log('Bill Unpaid manually', "Admin marked bill ID {$id} as Unpaid.");
        }

        $maintenance->save();

        return redirect()->back()->with('success', 'Payment Status Updated Successfully!');
    }

    public function downloadPdf()
    {
        $societyId = auth()->user()->society_id;
        $maintenances = Maintenance::where('society_id', $societyId)->with('resident.flats')->get();

        $pdf = Pdf::loadView(
            'admin.maintenance.pdf',
            compact('maintenances')
        );

        return $pdf->download('maintenance-report.pdf');
    }

    public function edit($id)
    {
        $societyId = auth()->user()->society_id;
        $maintenance = Maintenance::where('society_id', $societyId)->findOrFail($id);
        $residents = Resident::where('society_id', $societyId)->get();

        return view('admin.maintenance.edit', compact('maintenance', 'residents'));
    }

    public function update(Request $request, $id)
    {
        $societyId = auth()->user()->society_id;
        $maintenance = Maintenance::where('society_id', $societyId)->findOrFail($id);

        $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'month' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'due_date' => 'required|date',
            'payment_status' => 'required|in:paid,unpaid',
        ]);

        $lateFee = 0.00;
        if ($request->payment_status === 'unpaid' && $request->due_date < now()->toDateString()) {
            $lateFee = $request->amount * 0.10;
        }

        $maintenance->update([
            'resident_id'    => $request->resident_id,
            'month'          => $request->month,
            'amount'         => $request->amount,
            'late_fee'       => $lateFee,
            'due_date'       => $request->due_date,
            'payment_status' => $request->payment_status,
        ]);

        \App\Models\ActivityLog::log('Update Bill', "Updated bill for resident ID {$request->resident_id}");

        return redirect()->route('maintenances.index')->with('success', 'Maintenance record updated successfully.');
    }

    public function destroy($id)
    {
        $societyId = auth()->user()->society_id;
        $maintenance = Maintenance::where('society_id', $societyId)->findOrFail($id);
        $maintenance->delete();

        \App\Models\ActivityLog::log('Delete Bill', "Deleted bill.");

        return redirect()->route('maintenances.index')->with('success', 'Maintenance record deleted successfully.');
    }
}
