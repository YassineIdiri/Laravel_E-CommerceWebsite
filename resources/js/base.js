AOS.init({
    offset: 0
  });
  
const icon = document.getElementById('icons');
const nav = document.getElementsByTagName('header');


icon.addEventListener('click', ()=> {nav[0].classList.toggle("active");
});

const links = document.querySelectorAll("nav.navigation a, nav.navigation a, nav.navigation button");

links.forEach((link) => {link.addEventListener('click', ()=> {
nav[0].classList.remove("active");
})
})

function viewCart()
{
    document.querySelector('.cart').classList.add("activeCart");
}

function hideCart()
{
    document.querySelector('.cart').classList.remove("activeCart");
}


function calculerPrixTotal() {
    const elementsCartBox = document.querySelectorAll('.cart-box');
  
    let prixTotal = 0;

    elementsCartBox.forEach((element) => {
      const quantite = parseInt(element.querySelector('.cart-price#quantity').textContent.split(':')[1].trim());
      const prix = parseFloat(element.querySelector('.cart-price').textContent.split('€')[0].trim());
  
      const sousTotal = quantite * prix;

      prixTotal += sousTotal;
    });
  
     return prixTotal;
  }




