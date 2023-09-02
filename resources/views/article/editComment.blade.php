<script>
    function rateStar(stars) {
     // Mettre à jour la valeur du champ caché pour stocker la note sélectionnée
     document.getElementById('rating2').value = stars;
    
     // Remplir les étoiles en fonction de la note sélectionnée
     let allStar = document.getElementById("comment_{{ $comment->id }}");
     console.log(allStar);

     let allStars = allStar.querySelectorAll('span');
     allStars.forEach((star, index) => {
         if (index < stars) {
             star.innerHTML = "<i class='bi bi-star-fill'></i>"; // Étoile remplie
         } else {
             star.innerHTML = "<i class='bi bi-star'></i>"; // Étoile vide
         }
     });
 }

 function autoResize(textarea) {
  textarea.style.height = "auto";
  textarea.style.height = textarea.scrollHeight + "px";
}

const textarea = document.querySelector(".editHTMX");
autoResize(textarea);
</script>

<form action="{{ route('comment.submitEdit') }}" method="post" id="formedit"  hx-target="this" hx-swap="outerHTML">
    @csrf
    <input type="hidden" name="rating" id="rating2" value="{{$comment->rating}}">
    <p>Rating <abbr>*</abbr> : </p>
    <div class="rating-stars2" id="comment_{{$comment->id}}">
     <span onclick="rateStar(1)"><i class='bi bi-star'></i></span>
     <span onclick="rateStar(2)"><i class='bi bi-star'></i></span>
     <span onclick="rateStar(3)"><i class='bi bi-star'></i></span>
     <span onclick="rateStar(4)"><i class='bi bi-star'></i></span>
     <span onclick="rateStar(5)"><i class='bi bi-star'></i></span>
   </div>

   <p>Comment <abbr>*</abbr> :</p>
   <textarea placeholder="Type something ..." class="commentbox editHTMX" name="content" type="text"> {{ htmlspecialchars_decode($comment->content)}} </textarea>
    <input name="id" type="hidden" value="{{ $comment->id }}">
    <button type="submit" name="com">Edit</button>
</form>

<script>
    rateStar({{$comment->rating}});
    console.log({{$comment->rating}});
</script>
