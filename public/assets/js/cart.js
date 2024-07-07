function disableAllButtons() {
    const buttons = document.querySelectorAll('.cart-remove');
    buttons.forEach(button => {
        button.disabled = true;
        setTimeout(() => {
            button.disabled = false;
        }, 500);
    });
}

function add(url, id) {
    disableAllButtons();

    fetch(url, {
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
    })
    .then((response) => {
        if (response.ok) {
            return response.json();
        } else {
            throw new Error("Error: " + response.status);
        }
    })
    .then((data) => {
        if (data.nouvelleQuantite > 1) {
            const elem = document.getElementById(id);
            const qt = elem.querySelector('#quantity');
            qt.textContent = "Qt :" + data.nouvelleQuantite;

            const tot = document.getElementById("total");
            tot.textContent = calculerPrixTotal() + " €";
        } else {
            fetch('/api/article/details/' + id)
            .then((articleResponse) => {
                if (!articleResponse.ok) {
                    throw new Error("Error : " + articleResponse.status);
                }
                return articleResponse.json();
            })
            .then((articleData) => {

                const cartBox = document.createElement("div");
                cartBox.classList.add("cart-box");
                cartBox.id = articleData.data.article.id;

                const imgLink = document.createElement("a");
                imgLink.href = "#";
                const cartImg = document.createElement("img");
                cartImg.src = "/assets/Images/" + articleData.data.image_path;
                cartImg.alt = "";
                cartImg.classList.add("cart-img");
                imgLink.appendChild(cartImg);
                cartBox.appendChild(imgLink);

                const detailBox = document.createElement("div");
                detailBox.classList.add("detail-box");

                const cartProductTitle = document.createElement("div");
                cartProductTitle.classList.add("cart-product-title");
                cartProductTitle.textContent = articleData.data.article.name;
                detailBox.appendChild(cartProductTitle);

                const cartPrice = document.createElement("div");
                cartPrice.classList.add("cart-price");
                cartPrice.textContent = articleData.data.article.price + " €";
                detailBox.appendChild(cartPrice);

                const cartQuantity = document.createElement("div");
                cartQuantity.id = "quantity";
                cartQuantity.classList.add("cart-price");
                cartQuantity.textContent = "Qt : 1";
                detailBox.appendChild(cartQuantity);

                cartBox.appendChild(detailBox);

                const btns = document.createElement("div");
                btns.classList.add("btns");

                const plusButton = document.createElement("button");
                plusButton.classList.add("cart-remove");
                plusButton.name = "rm";
                plusButton.onclick = () => add('/cart/addItem/'+articleData.data.article.id, articleData.data.article.id);
                const plusIcon = document.createElement("i");
                plusIcon.classList.add("bi", "bi-plus");
                plusButton.appendChild(plusIcon);
                btns.appendChild(plusButton);

                const dashButton = document.createElement("button");
                dashButton.classList.add("cart-remove");
                dashButton.name = "rm";
                dashButton.onclick = () => remove('/cart/removeItem/'+articleData.data.article.id,  articleData.data.article.id);
                const dashIcon = document.createElement("i");
                dashIcon.classList.add("bi", "bi-dash");
                dashButton.appendChild(dashIcon);
                btns.appendChild(dashButton);

                const trashButton = document.createElement("button");
                trashButton.classList.add("cart-remove");
                trashButton.name = "rm";
                trashButton.onclick = () => deleteItem('/cart/deleteItem/'+articleData.data.article.id, articleData.data.article.id);
                const trashIcon = document.createElement("i");
                trashIcon.classList.add("bi", "bi-trash3");
                trashButton.appendChild(trashIcon);
                btns.appendChild(trashButton);

                cartBox.appendChild(btns);

                const cartContent = document.querySelector('.cart-content');
                cartContent.appendChild(cartBox);

                const tot = document.getElementById("total");
                tot.textContent = calculerPrixTotal() + " €";

                new swal({ title: 'The item has been added to the cart', });
                
            })
            .catch((error) => {
                console.error("Error :", error);
            });
        }
    })
    .catch((error) => {
        console.error("Error :", error);
    });
}


function remove(url,id)
{
    disableAllButtons();

    fetch(url, {
        method: "DELETE",
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
    }).then((response) => {
        if (response.ok) {
            response.json().then((data) => {
                const elem = document.getElementById(id);

                const qt = elem.querySelector('#quantity');
                qt.textContent = "Qt :" + data.nouvelleQuantite;
            
                const tot = document.getElementById("total"); 
                tot.textContent = calculerPrixTotal() + " €";
            });
        } else {
            console.error("The request failed :", response.status);
        }
    }).catch((error) => {
        console.error("Error during request :", error);
    });
}

function deleteItem(url,id)
{
    disableAllButtons();

    fetch(url, {
        method: "DELETE",
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
    }).then((response) => {
        if (response.ok) {
            document.getElementById(id).remove();
            const tot = document.getElementById("total"); 
            tot.textContent = calculerPrixTotal() + " €";
        } else {
            console.error("Error :", response.status);
        }
    }).catch((error) => {
        console.error("Error :", error);
    });
}


