<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Http\Traits\AddRelations;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class AttendeeController extends Controller implements HasMiddleware
{
    use AddRelations;

    private array $allowed_relations = ["user" => "user"];

    public static function middleware(): array {
        return [
            new Middleware(middleware: "auth:sanctum", only: ['destroy', 'store'])
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Event $event)
    {
        $attendees = $event->attendees();
        $this->addRelations($attendees, $this->allowed_relations);
        $attendees = $attendees->latest()->paginate();

        return AttendeeResource::collection($attendees);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        Gate::authorize('store', [Attendee::class, $event]);

        $attendee = $event->attendees()->create(
            $request->validate(['user_id' => 'required|integer'])
        );

        $this->addRelations($attendee, $this->allowed_relations);

        return new AttendeeResource($attendee);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, Attendee $attendee)
    {
        $this->addRelations($attendee, $this->allowed_relations);

        return new AttendeeResource($attendee);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Event $event, Attendee $attendee)
    {
        Gate::authorize('delete', [$attendee, $event]);

        $attendee->delete();

        return response(status : 204);
    }
}
