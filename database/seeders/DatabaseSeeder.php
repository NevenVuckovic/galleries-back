<?php

namespace Database\Seeders;

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
        $this->call(UsersSeeder::class);
        $this->call(GalleriesSeeder::class);

        $this->call(ImagesSeeder::class);

        $this->call(CommentsSeeder::class);
    }
}
