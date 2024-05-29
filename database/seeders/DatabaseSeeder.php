<?php

namespace Database\Seeders;

use App\Models\Attendee;
use App\Models\Event;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(1)->myself()->create();
        User::factory(999)->create();

        $users = User::all();

        for($i = 0; $i < 200; ++$i) {
            $user = $users->random();
            
            Event::factory()->create(["user_id"=> $user->id]);
        }

        $events = Event::all();

        foreach($users as $user) {
            foreach($events->random(random_int(0,5)) as $event) {
                Attendee::create(["user_id" => $user->id, "event_id" => $event->id]);
            }
        }
    }
}
