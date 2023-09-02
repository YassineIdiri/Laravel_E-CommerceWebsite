@extends('layout.base')

@section('styleCSS')
   <title>Log in</title>
   <link href="/assets/css/login.css" rel="stylesheet">
@endsection

@section('Page') 

<div class="wrapper active-popup">
    <div class="form-box login">
        <p class='h3'>Login</p>
        <br>
        @if(session('errorLog') || $errors-> has("Lname") || $errors-> has("Lpassword"))<p class='error'>Username or password is wrong</p>@endif
        @if(session('activate'))<p class='error'>Your account is not yet activated. Please check your email for the activation link</p>@endif
        <form method = "POST" action = "{{ route('loginSubmit') }}">
            @csrf
            <div class="input-box" >
                <span class="icon"><i class="bi bi-pen-fill"></i></span>
                <input type="text" name ="Lname" placeholder="" >
                <label id ="name">Name</label>
            </div>

            <div class="input-box  @if(old('Lname') || $errors->has('Lpassword')) active @endif">
                <span class="icon"><i class="bi bi-lock-fill"></i></span>
                <input type="password" name ="Lpassword" placeholder="">
                <label id="password">Password</label>
            </div>

            <div class="remember-forgot">
                <label><input type="checkbox">Remember me</label>
                <a href='#'>Forgot Password?</a>
            </div>

            <button type="submit" class="btn" name="login">Login</button>
            <div class="login-register">
             <p>Don't have an account? <a href = '#' 
                class = "register-link">Register</a></p>
            </div>

            
        </form>
    </div>
        
    @if (old('name') || old('email') || old('password') || old('password2') || old("register"))
    <script> 
        const w = document.querySelector('.wrapper');
        w.classList.add('active');
    </script>      
    @endif
        
        <div class="form-box register">
           @livewire('register')
        </div>

    </div>

    <script src = "/assets/js/login.js"></script>

    @if (session('signupActivate'))
      <script>new swal({ title: 'Nous vous avons envoyé un e-mail permettant dactiver votre compte sur votre boite mail', });</script>
    @endif

    @if (session('signup'))
      <script>new swal({ title: 'Vous avez été enregistré', });</script>
    @endif


@endsection