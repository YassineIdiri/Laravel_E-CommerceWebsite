
function confirmDelete(event) {
  event.preventDefault(); // Empêche la soumission du formulaire

  const articleId = event.target.getAttribute('data-article-id');

  Swal.fire({
    title: 'Êtes-vous sûr de vouloir supprimer cet article ?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Oui',
    cancelButtonText: 'Non'
    }).then((result) => {
    if (result.isConfirmed) {
    window.location.href = '/editArticle/delete/' + articleId;
    }
    });
}


var swiper = new Swiper(".edit-container", {
  slidesPerView: 4,
  spaceBetween: 20,
  sliderPerGroup: 4,
  loop: true,
  centerSlide: "true",
  fade: "true",
  grabCursor: "true",
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
    dynamicBullets: true,
  },
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },

  breakpoints: {
    0: {
      slidesPerView: 1,
    },
    520: {
      slidesPerView: 1,
    },
    768: {
      slidesPerView: 1,
    },
    1000: {
      slidesPerView: 1,
    },
    1200: {
      slidesPerView: 1, // Affiche 6 images à partir de 1200 pixels de largeur
    },
  },
});