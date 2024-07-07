<?php

namespace App\Http\Controllers\Index;
use Carbon\Carbon;
use App\Models\User;

use App\Models\Message;
use App\Models\TemporaryUser;
use App\Mail\ActivateAccountMail;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\SignupRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Order\CartController;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ConnexionController extends Controller
{    
    public function mailTo($email, $ref)
    {
        $mail = new PHPMailer(true);

        try{
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = env('MAIL_HOST');
            $mail->SMTPAuth =true;
            $mail->Username = env('MAIL_USERNAME');
            $mail->Password = env('MAIL_PASSWORD');
            $mail->SMTPSecure = env('MAIL_ENCRYPTION');
            $mail->Port = env('MAIL_PORT');

            $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject  = "Activate your account";
            $mail->Body = "Here is the link to activate your account: <a href = 'http://127.0.0.1/activate/" . $ref . "'> Activate your account</a>";
           
            
            $mail->send();
        }
        catch(Exception $e)
        {
            return back()->with('error','Email not send');
 
        }

    }

    /**
     * login
     *
     * @return view
     */
    public function login()
    {
        if(session()->has('connexion') && session('connexion') == true)
        {
    
            return redirect()->route('index');
        }
        else
        {
            return view('index.login'); 
        }
          
    }
    
    /**
     * logout
     *
     * @return redirect
     */
    public function logout() {
        if(session()->has('connexion') && session('connexion') == true) {
            Session::put('connexion', false);
            Session::put('user', 0);
    
            return redirect()->route('index');
        } else{
            return redirect()->route('login'); 
        }     
    }
    
    /**
     * loginSubmit
     *
     * @param  $req
     * @return redirect
     */
    public function loginSubmit(LoginRequest $req)
    {
        $data = User::where([
            ["name", "=", $req->input('Lname')],
            ["password", "=", $req->input('Lpassword')],
        ])->first();

        $dataActivate = TemporaryUser::where([
            ["name", "=", $req->input('Lname')],
            ["password", "=", $req->input('Lpassword')],
        ])->first();

        if ($data) {
            Session::put('connexion', true);
            Session::put('user', $data->id);
            Session::put('unreadMessage',  $this->countUnreadMessages());

            CartController::initializeCart();
           
    
            return redirect()->route('index');
        } 
        else if($dataActivate)
        {
            return redirect()->route('login')->with('activate', 'Vous devez activez votre compte en allant dans votre boite mail');
        }    
        else
        {
            return redirect()->route('login')->with('errorLog', 'Identifiants incorrects');
        }
    }


    public function countUnreadMessages()
    {
        
            $unreadMessagesCount = Message::where('to_id', session('user'))
                ->whereNull('readAt')
                ->count();

            return $unreadMessagesCount;
    }

    
    /**
     * signupSubmit
     *
     * @param  $req
     * @return redirect
     */
    public function signupSubmit(SignupRequest $req)
    {
        $ref = uniqid();

        $user = new TemporaryUser();
        $user->name = $req->input('name');
        $user->email = $req->input('email');
        $user->password = $req->input('password');
        $user->openingDate = Carbon::now();
        $user->ref = $ref;
        $user->save();

        $this->mailTo($user->email,$user->ref);

        return redirect()->route('index')->with('signupActivate', 'Your article was');
    } 

    public function signupActivate($ref)
    {
        $temporaryUser = TemporaryUser::where([
            ["ref", "=", $ref],
        ])->first();


        $user = new User();
        $user->name = $temporaryUser->name;
        $user->email =$temporaryUser->email;
        $user->password = $temporaryUser->password;
        $user->openingDate = $temporaryUser->openingDate;
        $user->save();

        $temporaryUser->delete();

        return redirect()->route('login')->with('signup', 'Your article was');
    }   
}