function deleteMessage(url,id) 
{
    Swal.fire({
        title: 'Êtes-vous sûr de vouloir supprimer ce message ?',
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
    let buttonDiv = clickedButton.parentElement.parentElement.querySelector(".button"); // Remonter au
    if(buttonDiv)
    {
        buttonDiv.classList.toggle("activeB");
    }
}

// Fonction pour colorier un élément par son ID
function colorizeElementById() {
  const element = document.getElementById("{{$user}}");
  if (element) {
    element.classList.toggle('activeContact');
  }
}

// Appel de la fonction lors du chargement de la page
window.addEventListener('load', function () {
  colorizeElementById();
});