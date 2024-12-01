<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class WelcomeController extends Controller
{
    public function index()
    {
        // Fetch all events from the database
        $events = Event::all();

        // Return a view with the events passed to it
        return view('welcome', compact('events')); // 'welcome' is the view name, 'events' is the data
    }

    public function ajax(Request $request)
    {
        // Define validation rules for the event
        $rules = [
            'title' => 'required|string|max:255',
            'start' => 'required',
            'end' => 'required|after_or_equal:start',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'document' => 'nullable|mimes:pdf,doc,docx|max:2048'
        ];

        // Skip validation for delete action
        if ($request->type !== 'delete') {
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
        }

        switch ($request->type) {
            case 'add':
                // Create new event
                $event = new Event();
                $event->title = $request->title;
                $event->start = $request->start;
                $event->end = $request->end;
                $event->description = $request->description;
                $event->location = $request->location;

                // Handle the cover image upload
                if ($request->hasFile('cover')) {
                    $coverPath = $request->file('cover')->store('pengumuman/covers', 'public');
                    $event->cover = $coverPath;
                }

                // Handle the document upload
                if ($request->hasFile('document')) {
                    $documentPath = $request->file('document')->store('pengumuman/documents', 'public');
                    $event->document = $documentPath;
                }

                $event->save();
                return response()->json($event);

            case 'update':
                $event = Event::find($request->id);
                if (!$event) {
                    return response()->json(['error' => 'Event not found'], 404);
                }

                $event->title = $request->title;
                $event->title = $request->title;
                $event->start = $request->start;
                $event->end = $request->end;
                $event->description = $request->description;
                $event->location = $request->location;

                // Handle the cover image upload if present
                if ($request->hasFile('cover')) {
                    // Delete old cover if exists
                    if ($event->cover) {
                        Storage::disk('public')->delete($event->cover);
                    }
                    // Upload new cover image
                    $coverPath = $request->file('cover')->store('pengumuman/covers', 'public');
                    $event->cover = $coverPath;
                }

                // Handle the document upload if present
                if ($request->hasFile('document')) {
                    // Delete old document if exists
                    if ($event->document) {
                        Storage::disk('public')->delete($event->document);
                    }
                    // Upload new document
                    $documentPath = $request->file('document')->store('pengumuman/documents', 'public');
                    $event->document = $documentPath;
                }

                $event->save();
                return response()->json($event);

            case 'delete':
                $event = Event::find($request->id);
                if (!$event) {
                    return response()->json(['error' => 'Event not found'], 404);
                }

                // Delete the cover image if exists
                if ($event->cover) {
                    Storage::disk('public')->delete($event->cover);
                }

                // Delete the document if exists
                if ($event->document) {
                    Storage::disk('public')->delete($event->document);
                }

                $event->delete();
                return response()->json(['success' => true]);

            default:
                return response()->json(['error' => 'Invalid action type'], 400);
        }
    }
}