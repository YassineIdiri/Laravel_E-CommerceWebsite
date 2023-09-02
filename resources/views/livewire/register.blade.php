<p class='h3'>Register</p>
<form method = "POST" action = "{{ route('signupSubmit') }}">
    @csrf

    <div class="input-box">
        <span class="icon"><i class="bi bi-pen-fill"></i></span>
        <input type="text" name ="name" placeholder="" value = "{{ old('name')}}">
        <label>Name @error("name")<span class='error'>{{ $message }}</span>@enderror </label>
    </div>

    <div class="input-box">
        <span class="icon"><i class="bi bi-envelope-at-fill"></i></span>
        <input type="text" name ="email" placeholder="" value = "{{ old('email')}}">
        <label>Email @error("email")<span class='error'>{{ $message }}</span>@enderror </label>
    </div>

    <div class="input-box">
        <span class="icon"><i class="bi bi-lock-fill"></i></span>
        <input type="password" name ="password" placeholder="">
        <label>Password @error("password")<span class='error'>{{ $message }}</span>@enderror </label>
    </div>

    <div class="input-box">
        <span class="icon"><i class="bi bi-lock-fill"></i></span>
        <input type="password" name ="ConfirmPassword" placeholder="" >
        <label>Confirm password @error("ConfirmPassword")<span class='error'>{{ $message }}</span>@enderror </label>
    </div>

    <div class="remember-forgot">
        <label><input type="checkbox">I agree to the terms & conditions</label>
    </div>
    <button type="submit" class="btn" name="register" value="exists">Register</button>
    <div class="login-register">
     <p>Already have an account? <a href = '#' 
        class = "login-link">Login</a></p>
    </div>
</form>