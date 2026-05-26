<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use App\Models\Resident;
use App\Mail\NoticeMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index(Request $request)
    {
        $societyId = auth()->user()->society_id;
        $search = $request->search;

        $notices = Notice::where('society_id', $societyId)
            ->when($search, function($query) use ($search){
                $query->where('title', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%")
                      ->orWhere('category', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('admin.notices.index', compact('notices', 'search'));
    }

    public function create()
    {
        return view('admin.notices.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:Announcement,Emergency,Event',
            'description' => 'required|string',
            'notice_date' => 'required|date',
            'scheduled_at' => 'nullable|date',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096'
        ]);

        $societyId = auth()->user()->society_id;

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('notices', 'public');
        }

        $notice = Notice::create([
            'society_id' => $societyId,
            'title' => $request->title,
            'category' => $request->category,
            'description' => $request->description,
            'notice_date' => $request->notice_date,
            'scheduled_at' => $request->scheduled_at,
            'attachment' => $attachmentPath
        ]);

        \App\Models\ActivityLog::log('Broadcast Notice', "Created notice: {$notice->title}");

        // Multi-society isolated mailing - wrapped in try-catch to guarantee stability
        $residents = Resident::where('society_id', $societyId)->get();
        
        try {
            foreach ($residents as $resident) {
                if ($resident->email && class_exists(NoticeMail::class)) {
                    Mail::to($resident->email)->queue(new NoticeMail($notice));
                }
            }
        } catch (\Exception $e) {
            // Log mail failures but do not crash the request
            \Log::warning("Notice email broadcasting failed: " . $e->getMessage());
        }

        return redirect()->route('notices.index')->with('success', 'Notice created and broadcasted successfully!');
    }

    public function edit($id)
    {
        $societyId = auth()->user()->society_id;
        $notice = Notice::where('society_id', $societyId)->findOrFail($id);
        return view('admin.notices.edit', compact('notice'));
    }

    public function update(Request $request, $id)
    {
        $societyId = auth()->user()->society_id;
        $notice = Notice::where('society_id', $societyId)->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:Announcement,Emergency,Event',
            'description' => 'required|string',
            'notice_date' => 'required|date',
            'scheduled_at' => 'nullable|date',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096'
        ]);

        $notice->update($request->only('title', 'category', 'description', 'notice_date', 'scheduled_at'));

        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('notices', 'public');
            $notice->update(['attachment' => $attachmentPath]);
        }

        \App\Models\ActivityLog::log('Update Notice', "Updated notice: {$notice->title}");

        return redirect()->route('notices.index')->with('success', 'Notice updated successfully.');
    }

    public function destroy($id)
    {
        $societyId = auth()->user()->society_id;
        $notice = Notice::where('society_id', $societyId)->findOrFail($id);
        $notice->delete();

        \App\Models\ActivityLog::log('Delete Notice', "Deleted notice.");

        return redirect()->route('notices.index')->with('success', 'Notice deleted successfully.');
    }
}
