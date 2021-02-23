<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Gallery;
use Illuminate\Database\Seeder;

class CommentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Comment::factory(10)->create()->each(function ($comment) {
            $gallery = Gallery::inRandomOrder()->first();
            $comment->gallery_id = $gallery->id;
            $comment->save();
        });
    }
}
