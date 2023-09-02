function calculerPrixTotal() {
    const elementsCartBox = document.querySelectorAll('.Cart-box');
  
    let prixTotal = 0;

    elementsCartBox.forEach((element) => {
      const quantite = parseInt(element.querySelector('.Cart-price#quantity').textContent.split(':')[1].trim());
      const prix = parseFloat(element.querySelector('.Cart-price').textContent.split('€')[0].trim());
  
      const sousTotal = quantite * prix;

      prixTotal += sousTotal;
    });
  
     return prixTotal;
  }
  
  async function deleteItem(id) {
    url = "/cart/deleteCart/" + id;
    let resp = await fetch(url);
    let data = await resp.json();

    const elem = document.getElementById(id);
    if(data.quantity==0)
    {
        elem.remove();
    }
    else
    {
        const qt = elem.querySelector('#quantity');
        qt.textContent = "Qt :" + data.quantity;
    }

    const tot = document.getElementById("total"); 
    tot.textContent = calculerPrixTotal() + " €";
};
