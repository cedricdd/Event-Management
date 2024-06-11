<?php

namespace App\Policies;

use App\Models\Attendee;
use App\Models\Event;
use App\Models\User;

use Illuminate\Auth\Access\Response;

class AttendeePolicy
{
    /**
     * Determine whether the user can delete an attendee from an event.
     */
    public function delete(User $user, Attendee $attendee, Event $event): bool
    {
        return $user->id == $event->user_id || $user->id == $attendee->user_id;
    }

    /**
     * Determine whether the user can add an attendee to an event.
     */
    public function store(User $user, Event $event): Response
    {
        return $user->id == $event->user_id ? Response::allow()
        : Response::deny('You do not own this post.');
    }
}
