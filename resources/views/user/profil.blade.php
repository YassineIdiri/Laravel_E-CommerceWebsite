@extends('layout.base')

@section('styleCSS')
  <title>Profil</title>
   <link href="/assets/css/profil.css" rel="stylesheet">
@endsection


@section('Page')  
  <script src="/assets/js/profil.js"></script>

  <div class="info-user">

    @php
        $averageRating = $averageRating = number_format($user->comments->avg('rating'), 1);
        $likeRecu = app('App\Http\Controllers\User\UserController')->likeRecu($user->name);
        use Carbon\Carbon;
    @endphp

    <div class="profil" data-aos="fade-right" data-aos-duration="1500">
      
        <div class="profile-info" >
          <div class='haut'>
            <div class='avatar'><div class="profile-avatar" style="user-select: none !important;"><h1>{{ substr($user->name, 0, 1) }}</h1></div><h1>{{$user->name}}</h1></div>
            <div class='right'>
            <div class='btn'> 
              <button class="option" onclick="activeOption()">
                <i class="bi bi-three-dots-vertical"></i>
              </button> 
              @if(session()->has('connexion') && session('connexion') == true && session('user')==$user->id)
                <div style="display :flex; margin-top : 5px;">
                <button class="disable setting-button" onclick="setting()">Setting <i class="bi bi-gear"></i></button>
                <button class="disable orders-button" onclick="order()">Orders <i class="bi bi-receipt"></i></button>
                </div>
              @else
                <button class="disable report-button" onclick="report({{$user->id}})">Report <i class="bi bi-flag"></i></button>
                <button class="disable orders-button" onclick="msg('{{$user->name}}')">Contact <i class="bi bi-receipt"></i></button>
              @endif
            </div>
            <h3><span>{{$likeRecu}}<i class='bi bi-heart'></i></span></h3>
            </div>
          </div>

          <div class='bas'>
          <h4><i class="bi bi-bookmarks"></i> Member since {{ Carbon::parse($user->openingDate)->format('d F Y') }}</h4>
          <h4>
            @if($averageRating == floor($averageRating))
                {{floor($averageRating)}}
                @for($i=1; $i<=$averageRating; $i++) <i class='bi bi-star-fill'></i>@endfor
                @for($i=$averageRating; $i<5; $i++)  <i class='bi bi-star'></i> @endfor
            @else
                {{$averageRating}}
                @for($i=1; $i<=floor($averageRating); $i++) <i class='bi bi-star-fill'></i>@endfor
                <i class='bi bi-star-half'></i>
                @for($i=floor($averageRating)+1; $i<5; $i++)  <i class='bi bi-star'></i> @endfor
            @endif
            
            {{$user->comments->count()}} comments
          </h4>
          </div>
        </div>
      </div>

    <div class='info'>

        <div class="commentaires" data-aos="fade-right" data-aos-duration="1500">
        <h3>List comments  ({{$user->comments->count()}}) </h3>
        <hr>
        @foreach ($user->comments as $comm)
          <div class='commentBox'>
            <a href='article/{{$comm->article->id}}#comment{{$comm->id}}' class='article'>{{$comm->article->name}}</a>
            <h3> {{ $user->name }} 
                @for($i=1; $i<=$comm->rating; $i++) <i class='bi bi-star-fill'></i>@endfor
                @for($i=$comm->rating; $i<5; $i++)  <i class='bi bi-star'></i> @endfor
            </h3>
            <h5>{{$comm->writeAt}}</h5>
            <div class="msgp"><p>{!!  nl2br($comm->content) !!}</p> <span class='like'>{{$comm->likes->count()}} <i class='bi bi-heart'></i></span></div>
            
          </div>     
        @endforeach
        </div>

    
        <div class = "annonces" data-aos="fade-left"  data-aos-duration="1500">
        <h3>List products sale ({{$user->article->count()}})</h3>
        <hr>
        <div class='cart-content'>

        @foreach ($user->article as $art)
        <div class='cart-box'>
        <a href='/article/{{$art->id}}'><img src="/assets/Images/{{$art->images->where('main', 1)->first()->path }} " alt='' class='cart-img'></a>
        <div class='detail-box'>
        <div class='cart-product-title'><h4> {{$art->name}} <h4></div>
        <div class='cart-price form-label'> {{$art->price}} €</div>
        </div>
        </div>     
        @endforeach
        </div>
    </div>
  </div>

  @if (session('report'))
  <script>new swal({ title: 'Cet utilisateur a été signalé', });</script>
  @endif
@endsection