<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Report;
use App\Models\Order;

class UserController extends Controller
{    
    /**
     * setting
     *
     * @return view
     */
    public function setting()
    {
            $user = User::where([
                ["id", "=", session('user')],
            ])->first();

            return view('user.setting',compact('user'));
    }
    
    /**
     * profil
     *
     * @param  $user
     * @return view
     */
    public function profil($user)
    {
        $user = User::where([
            ["name", "=", $user],
        ])->first();

        if($user)
        {
            return view('user.profil',compact('user'));
        }
        else
        {
            return abort(404);
        }
        
    }
    
    /**
     * profilPage
     *
     * @return view
     */
    public function profilPage()
    {
        if(session()->has('connexion') && session('connexion') == true)
        {
            $user = User::findOrFail(session('user'));
            return view('user.profil',compact('user'));
        }
        else
        {
            return abort(404);
        }
        
    }
    
    /**
     * order
     *
     * @return view
     */
    public function order()
    {

        $references = Order::select('reference')
                        ->where('user_id', session('user'))
                        ->groupBy('reference')
                        ->pluck('reference')
                        ->toArray();

        $orderListArticle = []; // Utilisation d'un tableau pour stocker les commandes et articles

        foreach ($references as $reference) 
        {
            $listArticle = Order::where('user_id', session('user'))
                            ->where('reference', $reference)
                            ->get(['date', 'article', 'category', 'price', 'quantity']);
                        
            $orderArticle = [
                'listArticle' => $listArticle,
                'reference' => $reference,
            ];
                        
            $orderListArticle[] = $orderArticle;
        }

        return view('user.order',compact('orderListArticle'));
    }
    
    /**
     * likeRecu
     *
     * @param  $user
     * @return int
     */
    public function likeRecu($user)
    {
        $user = User::where([
            ["name", "=", $user],
        ])->first();

        if($user)
        {
            $like = 0;
            foreach($user->comments as $comm)
            {
                $like += $comm->likes->count();
            }
            return $like;
        }
        else
        {
            return -1;
        }
    }
    
    /**
     * reportUser
     *
     * @param  $request
     * @return redirect
     */
    public function reportUser(Request $request)
    {
        $userId = $request->input('userId'); 
        $reason = $request->input('reason'); 

        $report = new Report();
        $report->reason = $reason;
        $report->date = now(); 
        $report->from_id = session('user'); 
        $report->concern_id = $userId;
        $report->save();

        return redirect(url() -> previous())->with('report', 'report');
    }

    
}