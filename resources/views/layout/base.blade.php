@php
if(!isset($_SESSION))
   session_start();
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/assets/css/base.css" rel="stylesheet">
    <link href="/assets/vendor/aos/aos.css" rel="stylesheet">
    <script src="/assets/vendor/aos/aos.js"></script>
    <link href="/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <script src="/assets/vendor/sweetalert/sweetalert2.js"></script> 
    <link rel="icon" type="image/x-icon" href="/assets/Images/img/favicon.ico">
    <script src="https://cdn.jsdelivr.net/npm/js-confetti@latest/dist/js-confetti.browser.js"></script>
    @yield('styleCSS')
    @livewireStyles
</head>

<body data-aos-easing="ease-in-out" data-aos-duration="1000" data-aos-delay="0">
<header>
  <script src="//js.pusher.com/3.1/pusher.min.js"></script>
  @if(session()->has('connexion') && session('connexion') == true)
  <script>

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;
  
    var pusher = new Pusher('7ae88cecac02b66b8975', {
      cluster: 'eu'
    });
  
    var channel = pusher.subscribe('message_notif');
    channel.bind('message', function(data) {
      
      if(data.id_user === {{ session('user') }} )
      {
          Livewire.emit("newMessage");
          Livewire.emit("actualiseNotif");
          Livewire.emit("actualiseContact");
      }

    });
  </script>
@endif
  <script src="/assets/js/cart.js"></script>
        <div>
           <a href = "/"><h2 class = "logo">OnlineShop</h2><a>
            <a href = "/"><h4 class = "description">Ecommerce website</h4><a>
        </div>
        <nav class ="navigation">
            
            <span  class="a" id ="cart-icon" onclick='viewCart()'><i class="bi bi-basket"></i><span>Cart</span></span>
            <a href = "{{ route('store.index') }}" class="a aa"><i class="bi bi-shop-window"></i><span>Store</span></a>

            <a href =  "{{route('message.index')}}"  class='a anotif'><i class="bi bi-envelope-at">
              @if(session('user'))
                 @livewire('notifications')
              @endif
             </i><span>Contact</span></a>

              <a href = "{{route('user.profilPage')}}" class="a aa"><i class="bi bi-clipboard"></i><span>Profil</span></a>
            
              <a href = "{{ route('dashboard') }}" class="a"><i class="bi bi-bar-chart-line"></i><span>Board</span></a>
            @if(session()->has('connexion') && session('connexion') == true)
              <a href = "{{ route('logout') }}" class='a' ><i class="bi bi-box-arrow-in-left"></i><span>LogOut</span></a>
            @else
              <a href = "{{ route('login') }}" class='a' ><i class="bi bi-box-arrow-in-right"></i><span>LogIn</span></a>
            @endif
            

        </nav>
        <div id="icons"></div>

        <div class='cart'>
        @if(session()->has('connexion') && session('connexion') == true) 
          @if( url()->current() == "http://127.0.0.1/payment/stripe")
          <span class='icon' id='close-cart'><i class="bi bi-x"></i></span>
          <h2 class='cart-title'>Votre panier est affiché sur la page courante</h2>
          @elseif(session('panier'))
          <h2 class='cart-title'>Your Cart</h2>
          <div class='cart-content'>
          @php $total = 0; @endphp

          @foreach (session('panier') as $produit) 
              <div class='cart-box' id="{{ $produit->article->id }}">
              <a href=''><img src="/assets/Images/{{$produit->article->images->where('main', 1)->first()->path }}" alt='' class='cart-img'></a>
              <div class='detail-box'>
              <div class='cart-product-title'> {{ $produit->article->name }} </div>
              <div class='cart-price'>{{ $produit->article->price }} €</div>
              <div class='cart-price' id='quantity'>Qt : {{ $produit->quantity }} </div>
              </div>
              <div class="btns">
              <button class='cart-remove' name = 'rm'  onclick='add( "{{route("cart.addItem",$produit->article->id)}}", {{$produit->article->id}})'><i class="bi bi-plus"></i></button>
              <button class='cart-remove' name = 'rm'  onclick='remove( "{{route("cart.removeItem",$produit->article->id)}}", {{$produit->article->id}})'><i class="bi bi-dash"></i></button>
              <button class='cart-remove' name = 'rm'  onclick='deleteItem( "{{route("cart.deleteItem",$produit->article->id)}}", {{$produit->article->id}})'><i class="bi bi-trash3"></i></button>
              </div>
              </div>
              @php $total+=($produit->article->price)*($produit->quantity); @endphp
          @endforeach
          
          </div>
          <div class='total'>
          <div class='total-title'>Total:</div>
          <div class='total-title' id='total'>{{$total}} €</div>
          </div>
          <a href="{{ Route('payment.stripe') }}" class='btn-buy'>Pay Now</a>
          <span class='icon' id='close-cart' onclick='hideCart()'><i class="bi bi-x"></i></span>
          @else
          <span class='icon' id='close-cart' onclick='hideCart()'><i class="bi bi-x"></i></span>
          <h2 class='cart-title'>panier</h2>
          @endif
        @else
        <span class='icon' id='close-cart' onclick='hideCart()'><i class="bi bi-x"></i></span>
          <h2 class='cart-title'>Connect you to see cart</h2>

        @endif

        </div>    
 
</header>
<script src="/assets/js/base.js"></script>

@yield('Page')
@livewireScripts

</body>
</html>
