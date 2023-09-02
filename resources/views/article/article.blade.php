@extends('layout.base')

@section('styleCSS')
   <title>Article</title>
   <link rel="stylesheet" href="/assets/css/article.css">
   <link rel="stylesheet" href="/assets/css/carrousselArticle.css" />
   <link rel="stylesheet" href="/assets/vendor/swiper/swiper-bundle.min.css" />
   <script src="/assets/vendor/htmx/htmx.js"></script>
@endsection

@section('Page') 

            <script src="/assets/js/article.js"></script>
            <div class='conteneur'>
         
              <div class = 'produit'>
                <div data-aos="fade-right" data-aos-duration="1500" class = 'images'>

                  @if($article->images->count() == 1)

                  <div class="card">
                  <div class="image-box">
                    <img src="/assets/Images/{{$article->images->where('main', true)->first()->path }}" alt="" />
                  </div>
                </div>

                @else

                <div class="swiper">
    
                    <div class="article-container">
                      <div class="card-wrapper swiper-wrapper">
                
                        {{-- Affichage de l'image principale --}}
                        @foreach ($article->images->where('main', true) as $mainImage)
                        <div class="card swiper-slide">
                          <div class="image-box">
                            <img src="/assets/Images/{{$mainImage->path }}" alt="" />
                          </div>
                        </div>
                      @endforeach
    
                      {{-- Affichage des autres images --}}
                      @foreach ($article->images->where('main', false) as $image)
                        <div class="card swiper-slide">
                          <div class="image-box">
                            <img src="/assets/Images/{{$image->path }}" alt="" />
                          </div>
                        </div>
                      @endforeach
                
                      </div>
                    </div>
                
                    <div class="swiper-button-next swiper-navBtn" style="background: #eee;color: black !important;"></div>
                    <div class="swiper-button-prev swiper-navBtn" style="background: #eee;color: black !important;"></div>
                    <div class="swiper-pagination"></div>
                
                  </div>
                @endif
                </div>
                <div data-aos="fade-left"  data-aos-duration="1500" class = 'descri'>
                  <h2>{{ $article->name }} <p class='imageCount'> {{ $imageCount }} Images</p></h2>
                  <p class='prix'> {{ $article->price }} €</p>
                  <h4 class="date">Put up for sale on : {{ $article->dateOfSale }} </h4>
                  <hr>
                  <h3 class='des'>Description</h3>
                  <p class='descr'> {!!  nl2br($article->description)!!}</p>
    
                @if(session()->has('user') && $article->user_id == session('user'))
                <form action="{{ route('editArticle.index', ['id' => $article->id]) }}" class="form" method="POST">
                  @csrf
                    <button class="button">Edit product</button>
                </form>
                @else
                 <div class="form" >
                 <button class='button' name ='buy'  onclick='add( "{{route("cart.addItem",$article->id)}}", {{$article->id}})'>Add to cart</button>
                 </div>
                @endif
    
                </div>
                </div>
    
                <div class='table' data-aos="fade-right"  data-aos-duration="1500">
                  <h2>Product Information</h2>
                  <table>
                      <tr>
                          <th>Seller</th>
                          <td>@if(session()->has('user') && session('user') != $article->user->id) <a href ='/user/profil/{{$article->user->name}}'>{{$article->user->name}}</a> @else {{$article->user->name}} @endif</td>
                      </tr>
                      <tr>
                          <th>Category</th>
                          <td>{{$article->category}}</td>
                      </tr>
                      <tr>
                          <th>Reviews</th>
                          <td>@if($commentCount == 0)
                                  No comments
                              @elseif($commentCount == 1)
                                      {{floor($averageRating)}}
                                      @for($i=1; $i<=$averageRating; $i++) <i class="bi bi-star-fill"></i>@endfor
                                      @for($i=$averageRating; $i<5; $i++)  <i class="bi bi-star"></i> @endfor
                                      &nbsp; 1 comment
                              @else
                                     @if($averageRating == floor($averageRating))
                                        {{floor($averageRating)}}
                                        @for($i=1; $i<=$averageRating; $i++) <i class="bi bi-star-fill"></i>@endfor
                                        @for($i=$averageRating; $i<5; $i++)  <i class="bi bi-star"></i> @endfor
                                     @else
                                        {{$averageRating}}
                                        @for($i=1; $i<=floor($averageRating); $i++) <i class="bi bi-star-fill"></i>@endfor
                                        <i class="bi bi-star-half"></i>
                                        @for($i=floor($averageRating)+1; $i<5; $i++)  <i class="bi bi-star"></i> @endfor
                                     @endif
                                      &nbsp; {{ $commentCount }} comments
                              @endif</td>
                      </tr>
                  </table>
              </div>

            <div class = 'commentaires' data-aos="fade-right" data-aos-duration="1800"> 
             <h2>Comments</h2>
             <div class ='commentaire' >

            @if(session()->has('connexion') && session('connexion') == true)
              <p id="activeComment"  onclick="activeComment()">Click here to write a comment <i id ="chevron" class='bi bi-chevron-right'></i></p>
               <form action="{{ route('comment.submit') }}" method="post" id="form"  >
               @csrf
               <input type="hidden" name="rating" id="rating" value="0">
               <p>Rating <abbr>*</abbr> : </p>
               <div class="rating-stars">
                <span onclick="rateStar(1)"><i class="bi bi-star"></i></span>
                <span onclick="rateStar(2)"><i class="bi bi-star"></i></span>
                <span onclick="rateStar(3)"><i class="bi bi-star"></i></span>
                <span onclick="rateStar(4)"><i class="bi bi-star"></i></span>
                <span onclick="rateStar(5)"><i class="bi bi-star"></i></span>
              </div>

              <p>Comment <abbr>*</abbr> :</p>
              <textarea placeholder="Type something ..." class="commentbox" name="content" type="text"></textarea>
               <input name="id" type="hidden" value="{{ $article->id }}">
               <button type="submit" name="com">Submit</button>
               </form>
            @else
              <div class="connect">Sign in to leave or like a comment</div>
            @endif

            @foreach ($article->comments->reverse() as $comment) 
                  
                  <div hx-target="this" hx-swap="outerHTML" id="comment{{$comment->id}}">
                    <hr>
                    <h3>
                      @if(session()->has('user') && session('user') != $comment->user->id) 
                         <a href = '/user/profil/{{ $comment->user->name }}'> {{ $comment->user->name }}</a>
                      @else
                        {{ $comment->user->name }}
                      @endif
                    </h3>

                      @for($i=1; $i<=$comment->rating; $i++) <i class="bi bi-star-fill"></i>@endfor
                      @for($i=$comment->rating; $i<5; $i++)  <i class="bi bi-star"></i> @endfor
                  </h3>
                  <h5> {{ $comment->writeAt }}</h5>
                  <div class="comm">

                          <div class="msg">
                          <p>{!! nl2br($comment->content) !!}</p> 
                        @if(session()->has('user') && $comment->user_id === session('user'))
                          <button class="option" onclick="activeOption(event)">
                            <i class="bi bi-three-dots-vertical"></i>
                          </button> 
                          <button class="like-btn" onclick="likeComment({{ $comment->id }})">
                            <i id="like-icon-{{ $comment->id }}" class='bi'></i>
                            <script>isLike("{{ $comment->id }}")</script>&nbsp;<span id="like{{$comment->id}}" class='nbLike'>{{ $comment->likes() -> count()}}</span>
                          </button>
                        </div>
                          <div class = "bouton">
                            <button hx-get="{{ route('comment.edit', $comment->id) }}" class="submit-button"><i class="bi bi-pencil-square"></i></button>
                            <button onclick='deleteMessage( "{{route("comment.delete",$comment->id)}}", "comment{{$comment->id}}")' class="delete-button"><i class="bi bi-trash3"></i></button>
                        </div>
                        @else 
                          <button class="like-btn" onclick="likeComment({{ $comment->id }})">
                            <i id="like-icon-{{ $comment->id }}" class='bi'></i>
                            <script>isLike("{{ $comment->id }}")</script>&nbsp;<span id="like{{$comment->id}}" class='nbLike'>{{ $comment->likes() -> count()}}</span>
                          </button>
                         </div>
                        @endif 

                    </div>
                  </div>
            @endforeach
              </div>
            </div>
            </div>

            <script src="/assets/vendor/swiper/swiper-bundle.min.js"></script>
            <script src="/assets/js/index.js"></script>

            @if (session('editArticle'))
              <script>new swal({ title: 'Votre article a été modifié', });</script>
            @endif

            @if (session('addComment'))
              <script>new swal({ title: 'Votre commentaire a été ajouté', });</script>
            @endif 

            @if (session('articleSell'))
            <script>new swal({ title: 'Votre article a été posté', });</script>
         @endif

        
        
         
        
        

@endsection