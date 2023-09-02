<?php

namespace App\Http\Controllers\Article;
use App\Http\Controllers\Controller;

use App\Models\Article;

class ArticleController extends Controller
{    
    /**
     * article
     *
     * @param   $id
     * @return view
     */
    public function article($id)
    {
        $article = Article::findOrFail($id);
        
        $commentCount = $article->comments->count();
        $imageCount = $article->images->count();
        $averageRating = number_format($article->comments->avg('rating'), 1);
  
        return view('article.article', compact('article', 'commentCount', 'imageCount', 'averageRating'));
    }
    
    /**
     * details
     *
     * @param  $id
     * @return json
     */
    public function details($id)
    {   
        $article = Article::findOrFail($id);

        if (!$article) {
            return response()->json(['message' => 'Article not found'], 403);
        }

        $imagePath = $article->images->where('main', 1)->first()->path;

        $data = [
            'article' => $article,
            'image_path' => $imagePath
        ];

        return response()->json(['data' => $data], 200);
    }   
  
}
