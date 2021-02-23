<?php

namespace Database\Seeders;

use App\Models\Gallery;
use App\Models\Image;
use Illuminate\Database\Seeder;

class ImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Image::factory(10)->create()->each(function ($image) {
            $gallery = Gallery::inRandomOrder()->first();
            $image->gallery_id = $gallery->id;
            $image->save();
        });

    }
}
