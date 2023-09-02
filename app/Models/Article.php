<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;
use App\Models\User;

class Article extends Model
{
    use HasFactory;

    public $timestamps = false;

    //retounr l'user qui met en ventes
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //retounr les commentaire (la ou cette article est en clé externe dans la table comments)
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    //retounr les user qui ont achetés (qui se trouvent dans la tabel de jonction)
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
