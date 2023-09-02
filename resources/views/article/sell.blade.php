@extends('layout.base')

@section('styleCSS')
   <title>Store</title>
   <link rel="stylesheet" href="/assets/vendor/filepond/filepond.css">
   <link rel="stylesheet" href="/assets/vendor/filepond/filepond-plugin-image-preview.css">
   <link href="/assets/css/sell.css" rel="stylesheet">
@endsection


@section('Page') 
            <h1 data-aos="fade-right" data-aos-duration="1500">Sell a product</h1>
            <div class='containers' data-aos="fade-right" data-aos-duration="1500">

            
            <form action ="{{ route('store.save') }}"  method='POST' enctype='multipart/form-data'>
            <div class = 'form'>
            @csrf
            <div class='article'>
                  <label class='form-label'>Name <abbr>*</abbr> @error("name")<span class='error'>{{ $message }}</span>@enderror</label>
                  <input type='text' class='form-control' id='email' name='name' value = "{{ old('name')}}">


                  <label class='form-label'>Price <abbr>*</abbr> @error("price")<span class='error'>{{ $message }}</span>@enderror</label>
                  <input type='text' class='form-control' id='email' name='price' value = "{{ old('price')}}">

                  <label class='form-label'>Category <abbr>*</abbr> @error("category")<span class='error'>{{ $message }}</span>@enderror</label>
                  <select class="form-control" id="email" name="category">
                     <option value="Informatics" @if(old('category') === "Informatics") selected @endif>Informatics</option>
                     <option value="Fruits" @if(old('category') === "Fruits") selected @endif>Fruits</option>
                     <option value="Shoes" @if(old('category') === "Shoes") selected @endif>Shoes</option>
                     <option value="Appliances" @if(old('category') === "Appliances") selected @endif>Appliances</option>
                     <option value="Furniture" @if(old('category') === "Furniture") selected @endif>Furniture</option>
                     <option value="Vehicles" @if(old('category') === "Vehicles") selected @endif>Vehicles</option>
                     <option value="Property" @if(old('category') === "Property") selected @endif>Property</option>
                     <option value="Habit" @if(old('category') === "Habit") selected @endif>Habit</option>
                     <option value="Food" @if(old('category') === "Food") selected @endif>Food</option>
                     <option value="Dessert" @if(old('category') === "Dessert") selected @endif>Dessert</option>
               </select>

                  <label class='form-label'>Description <abbr>*</abbr> @error("description")<span class='error'>{{ $message }}</span>@enderror</label>
                  <textarea class='form-control' id='message' name='description'>{{ old('description')}}</textarea>
            </div>

            <div class='imageArticle'>

               <label class='form-label'>Main image <abbr>*</abbr>  @error("image")<span class='error'>{{ $message }}</span>@enderror</label>
               @error('mainimage')<div class="error">{{ $message }}</div>@enderror     
               <input type='file' class='form-control filepond' id='screenshot' name='mainimage' credits="false" />


               <label class='form-label'>Secondary images  @error("image")<span class='error'>{{ $message }}</span>@enderror</label>
               <input type='file' class='form-control filepond' id='screenshot' name='image' multiple credits="false" />
            </div>
         </div>
         <button type='submit' class='btn btn-primary' name='envoyer'>Sell</button>
            </form>
            </div>

         <script src="/assets/vendor/filepond/filepond.js"></script>
         <script src="/assets/vendor/filepond/filepond-plugin-image-preview.js"></script>
         
         <script >
            FilePond.registerPlugin(FilePondPluginImagePreview);
            const inputElement = document.querySelector('input[name="image"]');

            const pond = FilePond.create(inputElement);
            const inputElement2 = document.querySelector('input[name="mainimage"]');
            const pond2 = FilePond.create(inputElement2);

            FilePond.setOptions({
               server : {
                  process : '/store/upload',
                  revert : '/store/deleteImage',
                  headers : {
                     'X-CSRF-TOKEN' : '{{ csrf_token() }}'
                  }
               },
            })
         </script>
@endsection



