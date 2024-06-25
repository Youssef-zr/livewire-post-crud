<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $table  = 'posts';
    protected  $fillable = ["title", "slug", "content", "mini_description", "status", 'image'];

    protected $casts = ['created_at_human','image'];


    public function getCreatedAtHumanAttribute()
    {
        $date = Carbon::parse($this->created_at);
        return $date->diffForHumans();
    }

    public function getImageAttribute($path)
    {
        if (empty($path) || !file_exists($path)) {
            return "https://placehold.co/90x90";
        }

        return $path;
    }
}
