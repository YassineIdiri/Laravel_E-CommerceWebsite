<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\editProfilRequest;

class EditUserController extends Controller
{    
    /**
     * edit
     *
     * @param  $req
     * @return redirect
     */
    public function edit(editProfilRequest $req)
    {
        $userId = session('user');
        $user = User::find($userId);

        if($req->has('Name') && !$req->has('Password')) 
        {
            $user->name = $req->input('Name');
            $user->email = $req->input('Email');  
        }
        else if(!$req->has('Name') && $req->has('Password'))
        {
            $user->password = $req->input('Password'); 
        }
        else if($req->has('Name') && $req->has('Password'))
        {
            $user->name = $req->input('Name');
            $user->email = $req->input('Email');
            $user->password = $req->input('Password'); 
        }

        $user->save(); 
        return redirect()->route('user.settings',compact('user'))->with('edit', 'votre profil a été modifier');
    }
    
    /**
     * delete
     *
     * @param  $id
     * @return redirect
     */
    public function delete(User $id)
    {
        if($id->id == session('user'))
          {
            $id->delete(); 
            Session::put('connexion', false);
            Session::put('user', 0);
            return redirect()->route('index')->with('accountDelete', 'Your account was');
          }
          else
          {
             return abort(403);
          }
    }
}
