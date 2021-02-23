<?php

namespace Database\Seeders;

use App\Models\Gallery;
use App\Models\User;
use Illuminate\Database\Seeder;

class GalleriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Gallery::factory(10)->create()->each(function ($gallery) {
            $user = User::inRandomOrder()->first();
            $gallery->user_id = $user->id;
            $gallery->save();
        });

    }
}
