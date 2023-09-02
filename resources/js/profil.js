function activeOption()
{
  let buttonDiv = document.querySelectorAll(".disable");

   buttonDiv.forEach((button) => {
  button.classList.toggle("activeC");
});
}

function report(userId) {
  Swal.fire({
    title: 'Entrez le motif du signalement',
    input: 'text',
    inputAttributes: {
      autocapitalize: 'off'
    },
    showCancelButton: true,
    confirmButtonText: 'Signaler',
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = '/user/report?userId=' + userId + '&reason=' + result.value;
    }
  });
}

function setting()
{
  window.location.href = '/user/settings';
}

function order()
{
  window.location.href = '/user/orders';
}

function msg(user)
{
  window.location.href = '/message/'+user;
}