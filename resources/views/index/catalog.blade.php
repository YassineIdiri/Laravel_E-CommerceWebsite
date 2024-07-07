@extends('layout.base')

@section('styleCSS')
   <title>Catalog</title>
   <link href="/assets/css/catalog.css" rel="stylesheet">
@endsection


@section('Page')  

      <section class='sec' data-aos="fade-up" data-aos-duration="1500">

         <div class="search-container">
            
            <form action = "search" method="get" id="Forms">
               <div class="forms">
                  <input type='text' name='search' class = 'search' placeholder='Search'  value="{{ $searchTerm ?? '' }}"> 
                  <button class="search-btn"><i class="bi bi-search"></i></button>
               </div>
               <input type="text" name = 'priceMax' class = 'priceMax' placeholder='Max price' value="{{ $priceMax ?? '' }}">
               <select name="sortBy" id="sortBy">
                  <option value="asc" {{ isset($sortBy) && $sortBy === 'asc' ? 'selected' : '' }}>Ascending Price</option>
                  <option value="desc" {{ isset($sortBy) && $sortBy === 'desc' ? 'selected' : '' }}>Descending Price</option>
            </select>
            </form>
         </div>
      @if(isset($searchTerm))  
      <div class='pheading'>{{ count($articles) }} items  for "{{ $searchTerm}}"</div>
      @else
      <div class='pheading'>{{ count($articles) }} items</div>
      @endif
      <div class = 'products'>
   
      @foreach ($articles as $article) 
         <div class = 'card'>
         <div class='img'><a href="/article/{{ $article->id }}"><img src="/assets/Images/{{$article->images->where('main', 1)->first()->path }} "  alt='' class='product-img'></a></div>
         <div class='bas'>
         <div class='desc'> {{ $article->category }} </div>
         <div class='title'> {{ $article->name }} </div>
         <div class='box'>
         <div class='price'> {{ $article->price }} €</div>
      
         @if(session()->has('user') && $article->user_id == session('user'))
            <form action="{{ route('editArticle.index', ['id' => $article->id]) }}" class="form" method="get">
               <button class="btn add-cart" name="buyy">Edit product</button>
               <input type="hidden" name="id" value="{{ $article->id }}">
            </form>
         @else
         <div class="form" >
            <button class='btn add-cart cart-remove' name ='buyy'  onclick='add( "{{route("cart.addItem",$article->id)}}", {{$article->id}})'>Add to cart</button>
         </div>
         @endif

         </div>
         </div>
         </div>
    @endforeach
    
    </div>
    </section>

    <script src="/assets/js/catalog.js"></script>
    
@endsection