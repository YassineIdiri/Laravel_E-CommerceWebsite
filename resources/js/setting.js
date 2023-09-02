let btnEdit1 = document.getElementById("edit1");
if(btnEdit1)
{
    btnEdit1.onclick = () => {
        document.getElementById("editName").disabled = false;
        document.getElementById("editEmail").disabled = false;
    }  
}

let btnEdit2 = document.getElementById("edit2");
if(btnEdit2)
{
    btnEdit2.addEventListener("click", function() {
        document.getElementById("pass1").disabled = false;
        document.getElementById("pass2").disabled = false;
      });
}

function confirmDelete(event) {
    event.preventDefault(); // Empêche la soumission du formulaire
  
    const userId = event.target.getAttribute('data-user-id');
  
    Swal.fire({
        title: 'Êtes-vous sûr de vouloir supprimer votre compte ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oui',
        cancelButtonText: 'Non'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '/user/delete/' + userId;
        }
    });
  }


