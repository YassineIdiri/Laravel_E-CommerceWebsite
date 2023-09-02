
AOS.init();

function rateStar(stars) {
    // Mettre à jour la valeur du champ caché pour stocker la note sélectionnée
    document.getElementById('rating').value = stars;

    // Remplir les étoiles en fonction de la note sélectionnée
    let allStars = document.querySelectorAll('.rating-stars span');
    allStars.forEach((star, index) => {
        if (index < stars) {
            star.innerHTML = "<i class='bi bi-star-fill'></i>"; // Étoile remplie
        } else {
            star.innerHTML = "<i class='bi bi-star'></i>"; // Étoile vide
        }
    });
}

function activeComment() {
  let form = document.getElementById('form');
  form.classList.toggle("displayForm");

  let chevron = document.getElementById('chevron');
  chevron.classList.toggle("bi-chevron-right");
  chevron.classList.toggle("bi-chevron-down");
}

function deleteMessage(url, id) {
  Swal.fire({
      title: 'Êtes-vous sûr de vouloir supprimer ce commentaire ?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Oui',
      cancelButtonText: 'Non'
  }).then((result) => {
      if (result.isConfirmed) {
          fetch(url, {
              method: "DELETE",
              headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
              },
          }).then((response) => {
              if (response.ok) {
                  document.getElementById(id).remove();
              } else {
                  // La requête a échoué, afficher un message d'erreur ou effectuer une autre action en conséquence
                  console.error("La requête a échoué :", response.status);
              }
          }).catch((error) => {
              console.error("Erreur lors de la requête :", error);
          });
      }
  });
}

function activeOption(event)
{
  let clickedButton = event.target; // Récupère le bouton qui a été cliqué

  let buttonDiv = clickedButton.parentElement.parentElement.parentElement.querySelector(".bouton"); // Remonter au
  buttonDiv.classList.toggle("activeC");
}

async function likeComment(commentId) {
    url = "/comment/like/" + commentId;
    let resp = await fetch(url);
    let data = await resp.json();

    if(data['success'] == true) {
      const likeIcon = document.getElementById(`like-icon-${commentId}`);
      likeIcon.classList.toggle('bi-heart');
      likeIcon.classList.toggle('bi-heart-fill');

      const span = document.getElementById('like'+commentId);
      span.textContent = data['like'];
    }
}

async function isLike(commentId)  {
    url = "/comment/isLike/" + commentId;
    let resp = await fetch(url);
    let data = await resp.json();

    if(data['value'] == true) {
      const likeIcon = document.getElementById(`like-icon-${commentId}`);
      likeIcon.classList.add('bi-heart-fill');
    } else
    {
      const likeIcon = document.getElementById(`like-icon-${commentId}`);
      likeIcon.classList.add('bi-heart');
    }
}




