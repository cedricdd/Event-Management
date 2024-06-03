<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::with('organiser', 'attendees.user')->latest()->paginate();

        return EventResource::collection($events);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): Event
    {
        return Event::create($request->validate([
            'name' =>  'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date', 
            'end_time' => 'required|date|after:start_date', 
        ]) + ['user_id' => 1]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $event->load('organiser', 'attendees.user');
        
        return new EventResource($event);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $event->update($request->validate([
            'name' =>  'sometimes|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'sometimes|date', 
            'end_time' => 'sometimes|date|after:start_date', 
        ]));

        $event->load('organiser', 'attendees.user');

        return new EventResource($event);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return response(status: 204);
    }
}
