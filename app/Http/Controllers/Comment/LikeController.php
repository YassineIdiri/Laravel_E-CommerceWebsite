<?php

namespace App\Http\Controllers\Comment;
use App\Http\Controllers\Controller;

use App\Models\Like;
use App\Models\Comment;

class LikeController extends Controller
{    
    /**
     * likeComment
     *
     * @param  $id
     * @return json
     */
    public function likeComment(Comment $id)
    {
        if(session()->has('connexion') && session('connexion') == true)
        {
          $like = Like::where('comment_id', $id->id)
                      ->where('user_id', session('user'))
                      ->count();

          if( $like == 0 )
          {
            $like = new Like();
        
            $like->user_id = session('user');
            $like->comment_id = $id->id;
      
            $like->save();
          }
          else if ($like != 0)
          {
              $liked = Like::where('comment_id', $id->id)
                          ->where('user_id', session('user'))
                          ->first();

              $liked->delete();
          }
          $likesCount = Like::where('comment_id', $id->id)->count();

          return ['success' => true, 'like' => $likesCount]; 
        }
        return ['success' => false]; 
    }
    
    /**
     * isLiked
     *
     * @param  $id
     * @return json
     */
    public function isLiked(Comment $id)
    {
      if(session()->has('connexion') && session('connexion') == true)
      {
        $like = Like::where('comment_id', $id->id)
                    ->where('user_id', session('user'))
                    ->count();

        if($like == 1)
        {
          return ['value' => true];
        }
        else
        {
          return ['value' => false];
        }
      }
      else
      {
        return ['value' => false];
      }
      
    }
}
