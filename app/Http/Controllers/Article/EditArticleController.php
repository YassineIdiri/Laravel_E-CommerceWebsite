<?php

namespace App\Http\Controllers\Article;
use App\Models\Image;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Models\TemporaryImage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\editArticleRequest;
use App\Http\Controllers\Article\StoreController;


class EditArticleController extends Controller
{
        
    /**
     * editArticle
     *
     * @param  $id
     * @param  $req
     * @return view
     */
    public function editArticle($id,Request $req)
    {
          $article = Article::findOrFail($id);
          if($article->user_id == session('user'))
          {
              StoreController::clearTemporaryImages();
              return view('article.edit', compact('article'));
          }
          else
          {
            return abort(403);
          }
    }
    
    /**
     * save
     *
     * @param  $req
     * @return view
     */
    public function save(editArticleRequest $req)
    {
          $id = $req->id;
          $article = Article::findOrFail($id);
          if($article->user_id == session('user'))
          {
                $article->name = $req->input('titre');
                $article->price = $req->input('prix');
                $article->description = htmlspecialchars($req->input('description'));
                $article->category = $req->input('category'); 
                $article->save(); 
                
                $commentCount = $article->comments->count();
                $imageCount = $article->images->count();
                $averageRating = number_format($article->comments->avg('rating'), 1);

                return view('article.article', compact('article', 'commentCount', 'imageCount', 'averageRating'))->with(['editArticle' => 'Votre article a été modifié.']);
          }
          else
          {
             return abort(403);
          }      
    }
    
    /**
     * delete
     *
     * @param  mixed $id
     * @return redirect
     */
    public function delete(Article $id)
    {
          if($id->user_id == session('user'))
          {
            $images = Image::where('article_id', $id->id)->get();

            foreach($images as $img) {
                Storage::disk("public") -> delete($img->path);
                $img->delete(); 
            }

            $id->delete();
            
            return redirect()->route('index')->with('articleDelete', 'Your article was');
          }
          else
          {
             return abort(403);
          }      
    }
}
