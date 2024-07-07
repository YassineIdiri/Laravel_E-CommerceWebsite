@extends('layout.base')

@section('styleCSS')
   <title>Online Shop</title>
   <link href="/assets/css/index.css" rel="stylesheet">
   <link rel="stylesheet" href="/assets/vendor/swiper/swiper-bundle.min.css" />
   <link rel="stylesheet" href="/assets/css/carroussel.css" />
@endsection

@section('Page') 

  <section>


  <div class="search-container">
      <form action = "search" class='form' method = "get">
        <input type='text' name='search' class = 'search' placeholder='Rechercher'>
        <button type="submit" class="search-btn" name='searchbtn'><i class="bi bi-search"></i></button>
      </form>
  </div>

  <div class = "haut">

    <div id="bestSales">
      <h2 class='h2'>Best sales</h2>
      <a href = "bestSales"><div id="container" class='animated'>
      </div></a>
  </div>

  <div class = "recommendation" >
  <h2 class='h2'>Recommended</h2>
    <div class = 'products'>
      @foreach ($recommande as $article)
          <div class = 'card'>
              <a href="article/{{ $article->id}}"><img src="/assets/Images/{{ $article->path }}"  alt='' class='product-img'></a>
              <div class='bas'>
              <div class='title'> {{ $article->name}} </div>
              <div class='box'>
              <div class='price'>{{ $article->price}} €</div>
              </div>
              </div>
          </div>
      @endforeach
  </div>
  </div>

    <div id="topOffers">
        <h2 class='h2'>Top offers</h2>
        <a href = "topOffers"><div id="container" class='animated'>
        </div></a>
    </div>

  </div>


  <div class="bottom">

    <div class="container swiper">
      <div class="slide-container">
        <div class="card-wrapper swiper-wrapper">

          @foreach ($productCounts as $p)
            
          <div class="card swiper-slide">
            <div class="image-box">
              <a href ="/category/{{$p['category']}}"><img src="/assets/Images/img/{{$p['category']}}.jpg" alt="" /></a>
            </div>
            <div class="profile-details">
              <div class="name-job">
                <h3 class="name">{{$p['category']}}</h3>
                <h4 class="job">{{$p['count']}} products</h4>
              </div>
            </div>
          </div>

          @endforeach

  
        </div>
      </div>
      <div class="swiper-button-next swiper-navBtn"></div>
      <div class="swiper-button-prev swiper-navBtn"></div>
      <div class="swiper-pagination"></div>
    </div>
  </div>

  
  @if (session('signupActivate'))
  <script>new swal({ title: 'Un mail vous a été envoyé afin dactiver votre compte', });</script>
  @endif
  @if (session('articleDelete'))
  <script>new swal({ title: 'L\'article a été supprimé.', });</script>
  @endif

  @if (session('accountDelete'))
  <script>new swal({ title: 'Votre compte a été supprimé.', });</script>
  @endif

  <script src="/assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="/assets/js/index.js"></script>
  </section>
@endsection