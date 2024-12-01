<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyEventRequest;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventsController extends Controller
{
    public function index()
    {
        $events = Event::all();
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(StoreEventRequest $request)
    {
        $data = $request->all();

        // Validasi apakah start_time lebih lama dari end_time
        if (strtotime($data['start_time']) > strtotime($data['end_time'])) {
            return redirect()
                ->back()
                ->withErrors(['start_time' => 'Start time cannot be later than end time.'])
                ->withInput();
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('images', 'public');
        }

        // Save event data including location
        Event::create($data);

        return redirect()->route('systemCalendar')->with('success', 'Event created successfully.');
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $data = $request->all();

        // Validasi apakah start_time lebih lama dari end_time
        if (strtotime($data['start_time']) > strtotime($data['end_time'])) {
            return redirect()
                ->back()
                ->withErrors(['start_time' => 'Start time cannot be later than end time.'])
                ->withInput();
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($event->photo) {
                Storage::disk('public')->delete($event->photo);
            }

            $data['photo'] = $request->file('photo')->store('images', 'public');
        }

        // Update event data
        $event->update($data);

        return redirect()->route('systemCalendar')->with('success', 'Event updated successfully.');
    }

    public function show(Event $event)
    {
        return view('admin.events.show', compact('event'));
    }

    public function destroy(Event $event)
    {
        if ($event->photo) {
            Storage::disk('public')->delete($event->photo);
        }
        $event->delete();

        return back();
    }

    public function massDestroy(MassDestroyEventRequest $request)
    {
        $events = Event::whereIn('id', request('ids'))->get();

        foreach ($events as $event) {
            if ($event->photo) {
                Storage::disk('public')->delete($event->photo);
            }
            $event->delete();
        }

        return response(null, \Symfony\Component\HttpFoundation\Response::HTTP_NO_CONTENT);
    }
}
