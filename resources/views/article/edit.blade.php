@extends('layout.base')

@section('styleCSS')
   <title>Edit article</title>
   <link href="/assets/css/sell.css" rel="stylesheet">
   <link rel="stylesheet" href="/assets/vendor/swiper/swiper-bundle.min.css" />
   <link rel="stylesheet" href="/assets/css/carrousselEditArticle.css" />
   <link rel="stylesheet" href="/assets/vendor/filepond/filepond.css">
   <link rel="stylesheet" href="/assets/vendor/filepond/filepond-plugin-image-preview.css">
@endsection

@section('Page')

                    <div class = 'boite'>
                        <div data-aos="fade-right" data-aos-duration="1500" class = 'images'>

                            @if($article->images->count() == 1)
          
                            <div class="card">
                            <div class="image-box">
                              <img src="/assets/Images/{{$article->images->where('main', true)->first()->path }}" alt="" />
                              
                            </div>
                          </div>
          
                          @else
          
                          <div class="swiper">
              
                              <div class="edit-container">
                                <div class="card-wrapper swiper-wrapper">
                          
                                  {{-- Affichage de l'image principale --}}
                                  @foreach ($article->images->where('main', true) as $mainImage)
                                  <div class="card swiper-slide" >
                                    <div class="image-box">
                                      <img src="/assets/Images/{{$mainImage->path }}" alt="" />
                                        <form method="post" action="{{ route('editArticle.editImage') }}" class="editImage">
                                            @csrf
                                            <button class='btn btn-primary' name='main'>Define main image</button>
                                            <button class='btn btn-reset' name='reset'>Delete</button>
                                            <input type='hidden' name='id' value='{{ $mainImage->id }}'>
                                        </form>
                                   </div>
                                  </div>
                                @endforeach
              
                                {{-- Affichage des autres images --}}
                                @foreach ($article->images->where('main', false) as $image)
                                  <div class="card swiper-slide">
                                    <div class="image-box">
                                      <img src="/assets/Images/{{$image->path }}" alt="" />
                                        <form method="post" action="{{ route('editArticle.editImage') }}" class="editImage">
                                            @csrf
                                            <button class='btn btn-primary' name='main'>Define main image</button>
                                            <button class='btn btn-reset' name='reset'>Delete</button>
                                            <input type='hidden' name='id' value='{{ $image->id }}'>
                                        </form>
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

                    <div class='containers' data-aos="fade-left"  data-aos-duration="1500">

            
                    <form action ="{{ route('editArticle.save') }}"  method='POST' enctype='multipart/form-data'>
                    @csrf
                    <label class='form-label'>Titre <abbr>*</abbr> @error("titre")<span class='error'>{{ $message }}</span>@enderror</label>
                    <input type='text' class='form-control' id='email' name='titre' value = '{{ old('titre',$article->name)}}'>
            
            
                    <label class='form-label'>Prix <abbr>*</abbr> @error("prix")<span class='error'>{{ $message }}</span>@enderror</label>
                    <input type='text' class='form-control' id='email' name='prix' value = '{{ old('prix',$article->price) }}'>
        
                    <label class='form-label'>Catégorie <abbr>*</abbr> @error("category")<span class='error'>{{ $message }}</span>@enderror</label>
                    <!--<input type='text' class='form-control' id='email' name='categorie' value = '{{ old('categorie',$article->category)}}'>-->
                    <select class="form-control" id="email" name="category">
                        <option value="Informatics" @if(old('category') === "Informatics") selected @elseif($article->category === "Informatics" && old('category') === null) selected @endif>Informatics</option>
                        <option value="Fruits" @if(old('category') === "Fruits") selected @elseif($article->category === "Fruits" && old('category') === null) selected @endif>Fruits</option>
                        <option value="Shoes" @if(old('category') === "Shoes" ) selected @elseif($article->category === "Shoes" && old('category') === null) selected @endif>Shoes</option>
                        <option value="Appliances" @if(old('category') === "Appliances" ) selected @elseif($article->category === "Appliances" && old('category') === null) selected @endif>Appliances</option>
                        <option value="Furniture" @if(old('category') === "Furniture") selected @elseif($article->category === "Furniture" && old('category') === null) selected @endif>Furniture</option>
                        <option value="Vehicles" @if(old('category') === "Vehicles" ) selected @elseif($article->category === "Vehicles" && old('category') === null) selected @endif>Vehicles</option>
                        <option value="Property" @if(old('category') === "Property" ) selected @elseif($article->category === "Property" && old('category') === null) selected @endif>Property</option>
                        <option value="Habit" @if(old('category') === "Habit" ) selected @elseif($article->category === "Habit" && old('category') === null) selected @endif>Habit</option>
                        <option value="Food" @if(old('category') === "Food" ) selected @elseif($article->category === "Food" && old('category') === null) selected @endif>Food</option>
                        <option value="Dessert" @if(old('category') === "Dessert" ) selected @elseif($article->category === "Dessert" && old('category') === null) selected @endif>Dessert</option>
                    </select>
    
                    <label class='form-label'>Description <abbr>*</abbr> @error("description")<span class='error'>{{ $message }}</span>@enderror</label>
                    <textarea class='form-control' placeholder='Exprimez vous' id='message' name='description'>{{htmlspecialchars_decode(old('description',$article->description)) }}</textarea>
                      
                    <input type='hidden' name='id' value='{{ $article->id }}'>
                    <div class='button'>
                    <button class='btn btn-primary' name='envoyer'>Edit</button>
                    <button type="submit" class="btn btn-reset" name="supprimer" data-article-id="{{ $article->id }}" onclick="return confirmDelete(event)">Supprimer</button>
                    </div>
                        
                    </form>

                    <form action ="{{ route('editArticle.saveImage') }}"  method='POST' enctype='multipart/form-data'>
                       @csrf
                      <label class='form-label'>Pour rajouter des images des images faite les glissé et cliquez sur upload elles seront automatiquement ajouté</label>
                      <input type='file' class='form-control filepond' id='screenshot' name='image' multiple credits="false" />
                      <input type='hidden' name='id' value='{{ $article->id }}'>
                      <button type='submit' class='btn btn-primary' name='envoyer'>Upload</button>
                    </form>
                    </div>
                    </div>

                    <script src="/assets/vendor/swiper/swiper-bundle.min.js"></script>
                    <script src="/assets/vendor/filepond/filepond.js"></script>
                    <script src="/assets/vendor/filepond/filepond-plugin-image-preview.js"></script>
                    <script src="/assets/js/editArticle.js"></script>

                    <script>
                      FilePond.registerPlugin(FilePondPluginImagePreview);
                      // Get a reference to the file input element
                      const inputElement = document.querySelector('input[name="image"]');
                      // Create a FilePond instance
                      const pond = FilePond.create(inputElement);

                      FilePond.setOptions({
                        server : {
                            process : '/editArticle/uploadEdit',
                            revert : '/editArticle/deleteImage',
                            headers : {
                              'X-CSRF-TOKEN' : '{{ csrf_token() }}'
                            }
                        },
                      })
                    </script>
                   
                    

                    @if (session('defineMain'))
                       <script>new swal({ title: 'Vous ne pouvez pas supprimer la main image. Définisser une nouvelle main image pour supprimer celle-ci', });</script>
                    @endif

                    @if (session('edit'))
                       <script>new swal({ title: 'Les modifications ont été enregistrée', });</script>
                    @endif
@endsection 