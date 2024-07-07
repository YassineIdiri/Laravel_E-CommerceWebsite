<?php

namespace App\Http\Controllers\Order;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Order;
use App\Models\Article;
use App\Models\ArticleUser;
use App\Models\TemporyOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Order\CartController;

class CheckoutController extends Controller
{
    
    /**
     * stripe
     *
     * @return view
     */
    public function stripe()
    {
        return view('payment.checkout');   
    }
    
    /**
     * checkout
     *
     * @return redirect
     */
    public function checkout()
    {
            $stripe = new \Stripe\StripeClient(env("STRIPE_KEY_TEST"));

            $ref = uniqid();

            $lineItems = [];
            foreach (session('panier') as $item) {
                
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $item->article->name, // Assuming you have a 'name' field in the cart item.
                        ],
                        'unit_amount' => $item->article->price * 100, // Assuming the price is in cents.
                    ],
                    'quantity' => $item->quantity, // Assuming you have a 'quantity' field in the cart item.
                ];

                $temp = new TemporyOrder();
                $temp->reference = $ref;
                $temp->quantity =  $item->quantity;
                $temp->price = $item->article->price;
                $temp->date = Carbon::now();
                $temp->article_id = $item->article->id;
                $temp->user_id = session('user');
                $temp->save();
            }

            Session::put('ref', $ref);
            $user = User::findOrFail(session('user'));

            $checkout_session = $stripe->checkout->sessions->create([
                'customer_email' => $user->email, // You can change this to use the customer's email from your cart data.
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('payment.successPayment'),
                'cancel_url' => route('payment.cancelPayment'),
            ]);

            return redirect($checkout_session->url);
    }

     /**
     * stripe
     *
     * @return string
     */
    public function storePDF($ref)
    {
            $name = Order::where('reference', '=', $ref)->first()->user->name;
            $order = Order::where('reference', '=', $ref)->get();

            $pdf = PDF::loadView('mail.pdf', ['name' => $name, 'order' => $order]);
            $pdfPath = 'pdfs/' . $ref . '.pdf';  

            Storage::put($pdfPath, $pdf->output());

            return $pdfPath;
    }


    public function mailTo($name,$email,$ref,$pdfPath)
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
            $mail->Subject  = $name ." please find your invoice attached";
            $mail->Body = "<a href = 'http://127.0.0.1/payment/pdf/" . $ref . "'> Download your invoice</a>";
           
            
            $mail->send();
        }
        catch(Exception $e)
        {
            return back()->with('error','Email not send');
 
        }

    }
  
    /**
     * success
     *
     * @return view
     */
    public function success()
    {
        if(!(session()->has('ref')))
        {
            return abort(403);
        }
        else if(session('ref') == '')
        {
            return abort(403);
        }

        $ref = session('ref');

        $temp = TemporyOrder::where('reference', $ref)
                    ->where('user_id', session('user'))
                    ->get();
                    
        if (!($temp->isEmpty()))
        {
            foreach($temp as $line)
            {
                $order = new Order();
                $order->reference = $ref;
                $order->quantity = $line->quantity;
                $order->price = $line->price;
                $order->date = $line->date;

                $article = Article::findOrFail($line->article_id);

                $order->article = $article->name;
                $order->category = $article->category;
                $order->user_id = $line->user_id;
                $order->refArticle = "ref";
                $order->save();

                TemporyOrder::findOrFail($line->id)->delete();
            }
    
            ArticleUser::where('user_id', session('user'))->delete();
            CartController::initializeCart();

            $name = Order::where('reference', '=', $ref)->first()->user->name;
            $mail = Order::where('reference', '=', $ref)->first()->user->email;
            $pdfPath = $this->storePDF($ref);
            $this->mailTo($name,$mail,$ref,$pdfPath);

            return view('payment.success');  
        }
        else
        {
            return abort(403);
        }
        
    }

    public function download($filename)
    {
        $path = public_path('assets/Images/pdfs/' . $filename.'.pdf');

        if (!file_exists($path)) {
            return abort(404);
        }

        return response()->download($path);
    }
    
    
    /**
     * cancel
     *
     * @return view
     */
    public function cancel()
    {
        
        if(!(session()->has('ref')))
        {
            return abort(403);
        }
        else if(session('ref') == '')
        {
            return abort(403);
        }
        
        $ref = session('ref');

        TemporyOrder::where('reference', $ref)
        ->where('user_id', session('user'))
        ->delete();

        Session::put('ref', '' );

        return view('payment.cancel'); 
    }
}
