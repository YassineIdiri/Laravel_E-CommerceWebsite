<?php

namespace App\Http\Controllers\Order;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\ArticleUser;
use Illuminate\Support\Facades\Session;

use App\Models\User;


class CartController extends Controller
{    
    /**
     * initializeCart
     *
     * @return void
     */
    public static function initializeCart()
    {
        if(session()->has('connexion') && session('connexion') == true)
        {
            $user = User::findOrFail(session('user'));
            $panier = $user->panier;
             
            Session::put('panier', $panier);
        }
        
    }
    
    /**
     * add
     *
     * @param  $req
     * @return view
     */
    public function add(Request $req)
    {
        $idArticle = $req->id;
        $idUser = session('user');
        $panier = session('panier');
  
        $article =  ArticleUser::where([
              ["article_id", "=", $idArticle],
              ["user_id", "=", $idUser],
          ])->first();

        if($article){
            $article->quantity++;
            $article->save();

            $article = $panier->where('id', $article->id)->first();
            $article->quantity++;
        }
        else{
            $articleUser = new ArticleUser();
            $articleUser->user_id = $idUser;
            $articleUser->article_id = $idArticle;
            $articleUser->quantity = 1;
            $articleUser->save();

            $panier->add($articleUser);
        }

        Session::put('panier', $panier);
        
        return redirect(url() -> previous())->with('addCart', 'L\'article a été ajouté au panier.');
    }
    
    /**
     * addItem
     *
     * @param  $req
     * @return json
     */
    public function addItem(Request $req)
    {
        $idArticle = $req->id;
        $idUser = session('user');

  
        $article =  ArticleUser::where([
              ["article_id", "=", $idArticle],
              ["user_id", "=", $idUser],
          ])->first();

        if($article){
            $nouvelleQuantite = ($article->quantity) + 1;

            $article->quantity = $nouvelleQuantite;
            $article->save();

            $panier = session('panier');
            
            foreach ($panier as $articleUser) {
                
                if ($articleUser->article_id == $idArticle) {
                    $articleUser->quantity = $articleUser->quantity + 1;
                    break;
                }
            }

            Session::put('panier', $panier);

                
        }
        else{
            $articleUser = new ArticleUser();
            $articleUser->user_id = $idUser;
            $articleUser->article_id = $idArticle;
            $articleUser->quantity = 1;
            $articleUser->save();

            $nouvelleQuantite = 1;

            /*$panierArray = session('panier')->toArray();
            $panierArray[] = $articleUser;
            $panier = collect($panierArray);
            Session::put('panier', $panier);*/

            session('panier')->add($articleUser);

        }
        

        return response()->json(['nouvelleQuantite' => $nouvelleQuantite]);
    }

    
    /**
     * removeItem
     *
     * @param  mixed $req
     * @return json
     */
    public function removeItem(Request $req)
    {
        $idArticle = $req->id;
        $idUser = session('user');

        $nouvelleQuantite = 1;
  
        $article =  ArticleUser::where([
              ["article_id", "=", $idArticle],
              ["user_id", "=", $idUser],
          ])->first();

        if($article && $article->quantity>1){
            $nouvelleQuantite = ($article->quantity)-1;

            $article->quantity= $nouvelleQuantite;
            $article->save();

            $panier = session('panier');
            
            foreach ($panier as $articleUser) {
                
                if ($articleUser->article_id == $idArticle) {
                    $articleUser->quantity = $articleUser->quantity - 1;
                    break;
                }
            }

            Session::put('panier', $panier);
        }

        return response()->json(['nouvelleQuantite' => $nouvelleQuantite]);
    }

    
    /**
     * deleteItem
     *
     * @param  $req
     * @return void
     */
    public function deleteItem(Request $req)
    {
        $idArticle = $req->id;
        $idUser = session('user');
  
        ArticleUser::where([
            ["article_id", "=", $idArticle],
            ["user_id", "=", $idUser],
        ])->delete(); 
        
        $panier = session('panier')->where('article_id', '!=', $idArticle)->values();

        Session::put('panier', $panier);
        }
    }
