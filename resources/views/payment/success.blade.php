@extends('layout.base')

@section('styleCSS')
   <link rel="stylesheet" href="/assets/css/payment.css">
@endsection

@section('Page') 
    <div class="container">
        <h1>Payment Accepted</h1>
        <p>Your payment has been successfully processed.</p>
        <a href="/">Back to Home</a>
    </div>
@endsection
