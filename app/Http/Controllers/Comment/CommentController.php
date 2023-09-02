<?php

namespace App\Http\Controllers\Comment;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Http\Requests\commentRequest;
use Carbon\Carbon;

class CommentController extends Controller
{    
    /**
     * submitComment
     *
     * @param  $req
     * @return redirect
     */
    public function submitComment(commentRequest $req)
    {
        $comment = new Comment;
        $comment-> title = "Mon titre";
        $comment-> content = htmlspecialchars($req->input('content'));
        $comment-> rating = $req->input('rating');
        $comment-> writeAt = Carbon::now();
        $comment-> editAt = Carbon::now();
        $comment->user_id = session('user');
        $comment->article_id = $req->input('id');
        $comment->save();

        return back()->with('addComment', 'Le commentaire a été ajouté.');
    }

    
    /**
     * editComment
     *
     * @param  $request
     * @param  $id
     * @return view
     */
    public function editComment(Request $request, Comment $id)
    {
        if($id->user_id === session('user') and $request -> server("HTTP_HX_REQUEST") === "true")
        {
          return view("article.editComment", ["comment" => $id]);
        }
        else
        {
            return abort(403);
        }
    }

    
    /**
     * submitEditComment
     *
     * @param  mixed $req
     * @return redirect
     */
    public function submitEditComment(commentRequest $req)
    {
        $comment = Comment::findOrFail($req->id);

        if($comment->user_id == session('user'))
        {
            $comment->content = htmlspecialchars($req->content);
            $comment->rating = $req->rating;
            $comment->save(); 
            return redirect(url() -> previous());
        }
        else
        {
            return abort(403);
        }
    }
    
    /**
     * deleteComment
     *
     * @param  $id
     * @return void
     */
    public function deleteComment(Comment $id)
    {
        if( $id->user_id == session('user'))
        {
            $id->delete();
        }
        else
        {
            return abort(403);
        }
    }
}
