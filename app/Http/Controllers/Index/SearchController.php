<?php

namespace App\Http\Controllers\Index;
use App\Http\Controllers\Controller;

use App\Models\Article;
use Illuminate\Http\Request;

class SearchController extends Controller
{    
    /**
     * search
     *
     * @param  $req
     * @return view
     */
    public function search(request $req)
    {
        $searchTerm = $req->input('search', '*');
        $sortBy = $req->input('sortBy', 'asc'); // Par défaut, on trie par prix croissant de prix
        $priceMax ='';

        if ($req->has('priceMax') && is_numeric($req->input('priceMax'))) {
            $priceMax = $req->input('priceMax');
            $articles = Article::where('name', 'like', '%' . $searchTerm . '%')
                ->where('price', '<=', $priceMax)
                ->orderBy('price', $sortBy)
                ->get();
        } 
        else{
            $articles = Article::where('name', 'like', '%' . $searchTerm . '%')
                ->orderBy('price', $sortBy)
                ->get();
         }
          return view('index.catalog', compact('articles','searchTerm','sortBy','priceMax'));
    }
    
    /**
     * searchCategory
     *
     * @param  $category
     * @param  $req
     * @return view
     */
    public function searchCategory($category, request $req)
    {
        $searchTerm = $req->input('search', '*');
        $sortBy = $req->input('sortBy', 'asc'); // Par défaut, on trie par prix croissant
        $priceMax ='';

        if ($req->has('priceMax') && is_numeric($req->input('priceMax'))) 
        {
            $articles = Article::select('articles.id', 'articles.user_id', 'articles.name', 'articles.price', 'articles.description', 'articles.category', 'images.id as imageId', 'images.path', 'images.main')
            -> join('images', 'images.article_id', '=', 'articles.id') 
            -> where("main", "=", 1) 
            ->where('price', '<=', $priceMax)
            ->where('category', $category)
            ->orderBy('price', $sortBy)
            ->take(4)
            ->get()
            ->toArray();  

        } 
        else
         {
            $articles = Article::select('articles.id', 'articles.user_id', 'articles.name', 'articles.price', 'articles.description', 'articles.category', 'images.id as imageId', 'images.path', 'images.main')
            -> join('images', 'images.article_id', '=', 'articles.id') 
            -> where("main", "=", 1) 
            ->where('category', $category)
            ->orderBy('price', $sortBy)
            ->take(4)
            ->get()
            ->toArray();  
         }
          
        return view('index.catalogCategory', compact('articles','searchTerm','sortBy','priceMax','category'));
    }
}
