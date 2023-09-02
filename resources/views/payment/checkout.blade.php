@extends('layout.base')

@section('styleCSS')
   <title>Payment</title>
   <link href="/assets/css/checkout.css" rel="stylesheet">
@endsection

@section('Page') 
<div class='CartPayment'>
      @if(session('panier') && session()->has('connexion') && session('connexion') == true)
      <h2 class='cart-title Cart-title'>Summary of your order</h2>
      <div class='Cart-content'>
      @php $total = 0; @endphp
      @foreach (session('panier') as $produit) 
          <div class='cart-box Cart-box' id="{{ $produit->article->id }}">
          <a href=''><img src="/assets/Images/{{$produit->article->images->where('main', 1)->first()->path }}" alt='' class='Cart-img'></a>
          <div class='Cart-product-title'><h3> {{ $produit->article->name }} </h3></div>
          <div class='cart-price Cart-price'><h3>{{ $produit->article->price }} €</h3></div>
          <h3><div class='cart-price Cart-price' id='quantity'>Qt : {{ $produit->quantity }} </div></h3>
          <div class="btn">
          <button class='cart-remove' name = 'rm' onclick='add( "{{route("cart.addItem",$produit->article->id)}}", {{$produit->article->id}})'><h3><i class="bi bi-plus"></i></h3></button>
          <button class='cart-remove' name = 'rm' onclick='remove( "{{route("cart.removeItem",$produit->article->id)}}", {{$produit->article->id}})'><i class="bi bi-dash"></i></h3></button>
          <button class='cart-remove' name = 'rm' onclick='deleteItem( "{{route("cart.deleteItem",$produit->article->id)}}", {{$produit->article->id}})'><i class="bi bi-trash3"></i></h3></button>
          </div>
          </div>
          @php $total+=($produit->article->price)*($produit->quantity); @endphp
      @endforeach
      
      </div>
      <div class='total Total '>
      <div class='total-title Total-title'>Total:</div>
      <div class='total-title Total-title' id='total'>{{$total}} €</div>
      </div>
      <form action="/payment/create-checkout-session"  method="POST">
        @csrf
        <button type="submit" class='btn-buy'>Proceed to payment</button>
    </form>
      <span class='icon' id='Close-cart'><i class="bi bi-x"></i></span>
      @endif
</div> 

@endsection
