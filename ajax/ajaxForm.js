// Modal ajout utilisateur
const addUser = document.querySelector(".manageUser .wrapper");
const blurBG = document.querySelector(".blurBG");
const btnAddUser = document.querySelectorAll("button.btnAddUser");
const closeBtn = document.querySelector(".manageUser .wrapper .close");

btnAddUser.forEach(btn => {
    btn.addEventListener("click", () => {
        blurBG.classList.add("active");
        setTimeout(() => {
            addUser.classList.add("active");
        }, 1000)
    })
});

// Ajax Form Submit
const form = document.querySelector("form");
const btnSubmit = document.querySelector("form button");

function userForm(id){
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "pages/admin/user/manageUser.php?id_user=" + id);
    xhr.onload = function() {
        if (xhr.status === 200) {
            blurBG.classList.add("active");
            setTimeout(() => {
                addUser.classList.add("active");
                addUser.innerHTML = xhr.responseText;
            }, 1000)
        } else {
            console.log("Erreur AJAX");
        }
    }
    xhr.send();
}

function villeForm(ville){
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "pages/admin/ville/manageVille.php?id_ville=" + ville);
    xhr.onload = function() {
        if (xhr.status === 200) {
            blurBG.classList.add("active");
            setTimeout(() => {
                addUser.classList.add("active");
                addUser.innerHTML = xhr.responseText;
            }, 1000)
        } else {
            console.log("Erreur AJAX");
        }
    }
    xhr.send();
}

function paysForm(pays){
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "pages/admin/pays/managePays.php?id_pays=" + pays);
    xhr.onload = function() {
        if (xhr.status === 200) {
            blurBG.classList.add("active");
            setTimeout(() => {
                addUser.classList.add("active");
                addUser.innerHTML = xhr.responseText;
            }, 1000)
        } else {
            console.log("Erreur AJAX");
        }
    }
    xhr.send();
}

function photoForm(photo){
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "pages/admin/photo/managePhoto.php?id_photo=" + photo);
    xhr.onload = function() {
        if (xhr.status === 200) {
            blurBG.classList.add("active");
            setTimeout(() => {
                addUser.classList.add("active");
                addUser.innerHTML = xhr.responseText;
            }, 1000)
        } else {
            console.log("Erreur AJAX");
        }
    }
    xhr.send();
}

function menuForm(menu){
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "pages/admin/menu/manageMenu.php?id_menu=" + menu);
    xhr.onload = function() {
        if (xhr.status === 200) {
            blurBG.classList.add("active");
            setTimeout(() => {
                addUser.classList.add("active");
                addUser.innerHTML = xhr.responseText;
            }, 1000)
        } else {
            console.log("Erreur AJAX");
        }
    }
    xhr.send();
}

function tvaForm(tva){
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "pages/admin/shop/tva/manageTva.php?id_tva=" + tva);
    xhr.onload = function() {
        if (xhr.status === 200) {
            blurBG.classList.add("active");
            setTimeout(() => {
                addUser.classList.add("active");
                addUser.innerHTML = xhr.responseText;
            }, 1000)
        } else {
            console.log("Erreur AJAX");
        }
    }
    xhr.send();
}

function rayonForm(rayon){
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "pages/admin/shop/rayon/manageRayon.php?id_rayon=" + rayon);
    xhr.onload = function() {
        if (xhr.status === 200) {
            blurBG.classList.add("active");
            setTimeout(() => {
                addUser.classList.add("active");
                addUser.innerHTML = xhr.responseText;
            }, 1000)
        } else {
            console.log("Erreur AJAX");
        }
    }
    xhr.send();
}

function stockForm(stock){
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "pages/admin/shop/stock/manageStock.php?id_stock=" + stock);
    xhr.onload = function() {
        if (xhr.status === 200) {
            blurBG.classList.add("active");
            setTimeout(() => {
                addUser.classList.add("active");
                addUser.innerHTML = xhr.responseText;
            }, 1000)
        } else {
            console.log("Erreur AJAX");
        }
    }
    xhr.send();
}

function promotionForm(promotion){
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "pages/admin/shop/promotion/managePromotion.php?id_promotion=" + promotion);
    xhr.onload = function() {
        if (xhr.status === 200) {
            blurBG.classList.add("active");
            setTimeout(() => {
                addUser.classList.add("active");
                addUser.innerHTML = xhr.responseText;
            }, 1000)
        } else {
            console.log("Erreur AJAX");
        }
    }
    xhr.send();
}

function produitForm(produit){
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "pages/admin/shop/produit/manageProduit.php?id_produit=" + produit);
    xhr.onload = function() {
        if (xhr.status === 200) {
            blurBG.classList.add("active");
            setTimeout(() => {
                addUser.classList.add("active");
                addUser.innerHTML = xhr.responseText;
            }, 1000)
        } else {
            console.log("Erreur AJAX");
        }
    }
    xhr.send();
}

function deleteImg(id_produit,data_image_produit){
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "pages/admin/shop/produit/manageProduit.php?id_produit=" + id_produit + "&data_image_produit=" + data_image_produit);
    xhr.onload = function() {
        if (xhr.status === 200) {
            blurBG.classList.add("active");
            addUser.classList.add("active");
            addUser.innerHTML = xhr.responseText;
        } else {
            console.log("Erreur AJAX");
        }
    }
    xhr.send();
}

function closeForm(){
    addUser.classList.remove("active");
    setTimeout(() => {
        blurBG.classList.remove("active");
    }, 500);
}