<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Article;
use App\Models\Like;

class Comment extends Model
{
    use HasFactory;

    public $timestamps = false;

    //retounr l'article concerné par le commentaire
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    //retounr l'user concerné par le commentaire
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}
