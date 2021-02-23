<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $fillable = [
        'imageURL',
        'gallery_id',
    ];

    public function user()
    {
        return $this->belongsTo(Gallery::class, 'gallery_id');
    }

    public function comments()
    {
        return $this->belongsTo(Comment::class, 'gallery_id');
    }
}
