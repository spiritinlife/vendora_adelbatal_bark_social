<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(100)
            ->has(\App\Models\Bark::factory()->count(10))
            ->create();

        // add friends to each user
        // user with id 1 has 1 friend
        // user with id 2 has 2 friend
        // user with id 3 has 3 friends
        // ...
        // user with id 101 has 100 friends
        \App\Models\User::chunkById(10, function($users) {
            foreach($users as $user) {
                $friendsCount = $user->id;
                $friends = \App\Models\User::where('id', '!=', $user->id)->take($friendsCount)->pluck('id')->toArray();
                $user->friends()->sync($friends);
            }
        });
    }
}
