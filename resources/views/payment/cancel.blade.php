@extends('layout.base')

@section('styleCSS')
  <link rel="stylesheet" href="/assets/css/payment.css">
@endsection

@section('Page') 
    <div class="container">
        <h1>Payment Canceled</h1>
        <p>Your payment has been canceled.</p>
        <a href="/">Back to Home</a>
    </div>
@endsection
