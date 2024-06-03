<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Traits\AddRelations;

const ALLOWED_RELATIONS = ["organiser" => "organiser", "attendees" => "attendees.user"];

class EventController extends Controller
{
    use AddRelations;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::query();
        $this->addRelations($events, ALLOWED_RELATIONS);
        $events = $events->latest()->paginate();

        return EventResource::collection($events);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $event = Event::create($request->validate([
            'name' =>  'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date', 
            'end_time' => 'required|date|after:start_date', 
        ]) + ['user_id' => 1]);

        $this->addRelations($event, ALLOWED_RELATIONS);

        return new EventResource($event);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $this->addRelations($event, ALLOWED_RELATIONS);
        
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

        $this->addRelations($event, ALLOWED_RELATIONS);

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
