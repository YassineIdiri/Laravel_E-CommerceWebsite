<?php

use App\Events\chatNotif;
use App\Http\Middleware\IsLogged;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Index\IndexController;
use App\Http\Controllers\Index\SearchController;
use App\Http\Controllers\Index\ConnexionController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\EditUserController;
use App\Http\Controllers\Article\StoreController;
use App\Http\Controllers\Article\ArticleController;
use App\Http\Controllers\Article\EditArticleController;
use App\Http\Controllers\Article\EditImageArticleController;
use App\Http\Controllers\Comment\CommentController;
use App\Http\Controllers\Comment\LikeController;
use App\Http\Controllers\Message\MessageController;
use App\Http\Controllers\Message\EditMessageController;
use App\Http\Controllers\Order\CartController;
use App\Http\Controllers\Order\CheckoutController;
use App\Http\Controllers\Order\MailController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::prefix('/') -> controller(ConnexionController::class) -> group(function () {

    Route::get('login', 'login')->name('login');
    Route::post('login',  'loginSubmit')->name('loginSubmit');
    Route::get('logout','logout')->name('logout');
    Route::post('signup', 'signupSubmit')->name('signupSubmit');
    Route::get('activate/{ref}','signupActivate')->name('activate');
});

Route::prefix('/cart') -> controller(CartController::class) ->  name("cart") -> group(function () {

    Route::get('/deleteCart/{id}', 'deleteCart')->name('.deleteCart');
    Route::get('/addItemCart/{id}', 'addItemCart')->name('.addItemCart');
    Route::post('/addCart', 'addCart')->name('.addCart')->middleware(IsLogged::class);

    Route::post('/add', 'add')->name('.add')->middleware(IsLogged::class);
    Route::post('/addItem/{id}', 'addItem')->name('.addItem')->middleware(IsLogged::class);
    Route::delete('/removeItem/{id}', 'removeItem')->name('.removeItem')->middleware(IsLogged::class);
    Route::delete('/deleteItem/{id}', 'deleteItem')->name('.deleteItem')->middleware(IsLogged::class);
});

Route::prefix('/article') -> controller(ArticleController::class) ->  name("article") -> group(function () {

    Route::get('/{id}', 'article')->name('');
});

Route::prefix('/store') -> controller(StoreController::class) ->  name("store") -> group(function () {

    Route::get('/', 'index') -> name('.index') -> middleware(IsLogged::class);
    Route::post('/upload', 'upload')->name('.upload');
    Route::delete('/deleteImage', 'deleteImage')->name('.deleteImages');
    Route::post('/save', 'save')->name('.save');
});

Route::prefix('/editArticle') -> controller(EditArticleController::class) ->  name("editArticle") -> group(function () {
    
    Route::get('/delete/{id}', 'delete')->name('.delete') -> middleware(IsLogged::class); //A ameliore (requete faite par swal il me semble)
    Route::post('/save', 'save') -> name('.save') -> middleware(IsLogged::class);
    Route::post('/{id}', 'editArticle') -> name('.index') -> middleware(IsLogged::class);
});

Route::prefix('/editArticle') -> controller(EditImageArticleController::class) ->  name("editArticle") -> group(function () {
    
    Route::post('/editImage', 'editImage')->name('.editImage')-> middleware(IsLogged::class);
    Route::post('/uploadEdit', 'uploadEdit')->name('.upload');
    Route::delete('/deleteImage',  'deleteImage')->name('.deleteImages');
    Route::post('/saveEdit',  'saveImage')->name('.saveImage');
});

Route::prefix('/comment') -> controller(CommentController::class) ->  name("comment") -> group(function () {

    Route::get('/edit/{id}', 'editComment')->name('.edit')-> middleware(IsLogged::class);
    Route::post('/submitEdit','submitEditComment')->name('.submitEdit')-> middleware(IsLogged::class);
    Route::delete('/delete/{id}', 'deleteComment')->name('.delete')-> middleware(IsLogged::class);
    Route::post('/submit', 'submitComment')->name('.submit') -> middleware(IsLogged::class);
});

Route::prefix('/comment') -> controller(LikeController::class) ->  name("comment") -> group(function () {

    Route::get('/like/{id}', 'likeComment')->name('.like');
    Route::get('/isLike/{id}', 'isLiked')->name('.isLiked'); 
});

Route::prefix('/message') -> controller(MessageController::class) ->  name("message") -> group(function () {

    Route::get('/', 'index')->name('.index')-> middleware(IsLogged::class);
    Route::get('/{user}', 'conversation')->name('.conversation')-> middleware(IsLogged::class);
});

Route::prefix('/message') -> controller(EditMessageController::class) ->  name("message") -> group(function () {

    Route::post('/submit', 'addMessage')->name('.submit')-> middleware(IsLogged::class);
    Route::get('/edit/{id}', 'editMessage')->name('.edit')-> middleware(IsLogged::class);
    Route::post('/edit', 'submitEditMessage')->name('.submitEdit')-> middleware(IsLogged::class);
    Route::delete('/delete/{id}', 'deleteMessage')->name('.delete')-> middleware(IsLogged::class);
});


Route::prefix('/user') -> controller(UserController::class) ->  name("user") -> group(function () {

    Route::get('/profil', 'profilPage')->name('.profilPage')-> middleware(IsLogged::class);
    Route::get('/profil/{user}', 'profil')->name('.profil');
    Route::get('/settings', 'setting')->name('.settings') -> middleware(IsLogged::class);
    Route::get('/orders', 'order')->name('.orders')-> middleware(IsLogged::class);
    Route::get('/report','reportUser')->name('.report')-> middleware(IsLogged::class);
});

Route::prefix('/user') -> controller(EditUserController::class) ->  name("user") -> group(function () {

    Route::post('/edit', 'edit')->name('.edit') -> middleware(IsLogged::class);
    Route::get('/delete/{id}',  'delete')->name('.delete') -> middleware(IsLogged::class);
}); 

Route::prefix('/payment') -> controller(CheckoutController::class) ->  name("payment") -> group(function () {

    Route::get('/stripe','stripe')->name('.stripe')-> middleware(IsLogged::class);
    Route::post('/create-checkout-session', 'checkout')->name('.checkout')-> middleware(IsLogged::class);
    Route::get('/success',  'success')->name('.successPayment')-> middleware(IsLogged::class);
    Route::get('/cancel',  'cancel')->name('.cancelPayment')-> middleware(IsLogged::class);
});

Route::prefix('/search') -> controller(SearchController::class) ->  name("search") -> group(function () {

    Route::get('', 'search')->name('');
    Route::get('/{category}','searchCategory')->name('.category');
});

Route::prefix('/') -> controller(IndexController::class) -> group(function () {

    Route::get('', 'index')->name('index');
    Route::get('category/{category}', 'category')->name('category');
    Route::get('bestSales', 'bestSales')->name('bestSales');
    Route::get('topOffers', 'topOffers')->name('topOffers');
    Route::get('dashboard', 'dashboard')->name('dashboard');
});
















