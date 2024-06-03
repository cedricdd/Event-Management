<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Http\Traits\AddRelations;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\Request;

const ALLOWED_RELATIONS = ["user" => "user"];

class AttendeeController extends Controller
{
    use AddRelations;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Event $event)
    {
        $attendees = $event->attendees();
        $this->addRelations($attendees, ALLOWED_RELATIONS);
        $attendees = $attendees->latest()->paginate();

        return AttendeeResource::collection($attendees);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        $attendee = $event->attendees()->create(['user_id'=> 1]);

        $this->addRelations($attendee, ALLOWED_RELATIONS);

        return new AttendeeResource($attendee);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, Attendee $attendee)
    {
        $this->addRelations($attendee, ALLOWED_RELATIONS);

        return new AttendeeResource($attendee);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, Attendee $attendee)
    {
        $attendee->delete();

        return response(status : 204);
    }
}
