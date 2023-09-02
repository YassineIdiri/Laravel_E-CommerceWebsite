@extends('layout.base')

@section('styleCSS')
   <title>Profil</title>
   <link href="/assets/css/setting.css" rel="stylesheet">
@endsection

@section('Page') 

    @php
      use Carbon\Carbon;
    @endphp

    <div class="info-user">
                
        <form action="{{ route('user.edit') }}" class = "info" method='POST'>
            @csrf
            <h3>Informations</h3>
            <label class='form-label'>Registration date : {{ Carbon::parse($user->openingDate)->format('d F Y') }}</label>
            <hr>
            <div class="container-btn">
            <span class="btn" id="edit1" name="edit1">Edit <i class="bi bi-pencil-square"></i></span>
            </div>

            <label class='form-label'>Name  @error("Name")<span class='error'>{{ $message }}</span>@enderror </label>
            <input type='text' class='form-control' id='editName' name='Name' value = "{{ old('name',$user->name)}}" disabled>

            <label class='form-label'>Email  @error("Email")<span class='error'>{{ $message }}</span>@enderror </label>
            <input type='text' class='form-control' id='editEmail' name= 'Email' value = "{{ old('mail',$user->email)}}" disabled>

            <hr>
            <div class="container-btn">
            <span class="btn" id="edit2" name="edit1">Edit <i class="bi bi-pencil-square"></i></span>
            </div>

            <label class='form-label'>New Password  @error("Password")<span class='error'>{{ $message }}</span>@enderror @error("ConfirmPassword")<span class='error'>{{ $message }}</span>@enderror </label>

            <input type='password' class='form-control' id='pass1' name='Password' placeholder = 'Enter the new password' disabled>
            <input type='password' class='form-control' id='pass2' name='ConfirmPassword' placeholder = 'Confirm the new password' disabled>
            
            <hr>
            <label class='form-label'>Actual password <abbr>*</abbr> @error("ActualPassword")<span class='error'>{{ $message }}</span>@enderror </label>
            <input type='password' class='form-control' id='email' name='ActualPassword' placeholder = 'Enter your current password to edit' >
            <div class="container-edit">
            <button name = 'submitEdit' class='btn-edit'>Edit</button>
            <button type="submit" class="btn btn-reset" name="supprimer" data-user-id="{{ session('user') }}" onclick="return confirmDelete(event)">Supprimer</button>
            </div>
        </form>

        </div>
        <script src="/assets/js/setting.js"></script>

        @if (session('edit'))
            <script>new swal({ title: 'Votre profil a été mis à jour', });</script>
        @endif

@endsection