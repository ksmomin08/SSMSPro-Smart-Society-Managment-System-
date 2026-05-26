<?php

namespace App\Http\Controllers;
use App\Models\Complaint;
use App\Models\Resident;

use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index(Request $request)
{
    $search = $request->search;

    $complaints = Complaint::when($search, function($query) use ($search){

        $query->where('title', 'LIKE', "%{$search}%")
              ->orWhere('description', 'LIKE', "%{$search}%");

    })->paginate(5);

    return view('admin.complaints.index', compact('complaints', 'search'));
}

    public  function create(){
        $residents = Resident::all();

        return view('admin.complaints.create', compact('residents'));
    }

    public function store(Request $request){

    $request->validate([
        'resident_id' => 'required|exists:residents,id',
        'title' => 'required|string|max:255',
        'description' => 'required|string',
    ]); 
        Complaint::create([
            'resident_id' => $request -> resident_id,
            'title'       => $request -> title,
            'description' => $request -> description,
            'status'      => 'pending'
        ]);

        return redirect()->route('complaints.index');
    }

    public function edit($id)
   {
    $complaint = Complaint::findOrFail($id);

    return view('admin.complaints.edit', compact('complaint'));
   }

    public function update(Request $request, $id)
   {
    $request->validate([        
        'resident_id' => 'required|exists:residents,id',
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'status' => 'required|in:pending,resolved,rejected'
    ]);
    $complaint = Complaint::findOrFail($id);

    $complaint->update([
        'resident_id' => $request->resident_id,
        'title' => $request->title,
        'description' => $request->description,
        'status' => $request->status
    ]);


    return redirect()->route('complaints.index');
   }

   public function destroy($id)
   {
    $complaint = Complaint::findOrFail($id);

    $complaint->delete();

    return redirect()->route('complaints.index');
   }
}
