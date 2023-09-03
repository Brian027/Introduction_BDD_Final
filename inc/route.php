<?php
    $route = array();

    // Accueil Front Office
    $route['fo_home'] = 'front_office/pages/home/home.php';

    // Galerie Photo
    $route['fo_photo'] = 'front_office/pages/photo/listing_photo.php';

    // Galerie Produit
    $route['fo_produit'] = 'front_office/pages/produit/produit.php';

    // Panier
    $route['fo_panier'] = 'front_office/pages/panier/panier.php';

    // Commande
    $route['fo_commande'] = 'front_office/pages/commande/commande.php';
    $route['fo_manage_commande'] = 'front_office/pages/commande/manage_commande.php';

    // User / Adresse / Paiement
    $route['fo_user'] = 'front_office/pages/user/manage_user.php';
    $route['fo_listing_adresse'] = 'front_office/pages/adresse/listing_adresse.php';
    $route['fo_adresse'] = 'front_office/pages/adresse/manage_adresse.php';
    $route['fo_listing_paiement'] = 'front_office/pages/paiement/listing_paiement.php';
    $route['fo_checkout'] = 'front_office/pages/checkout/pay.php';

    // Signin / Logout
    $route['signin'] = 'back_office/pages/admin/signin/signin.php';
    $route['logout'] = 'back_office/pages/admin/logout/logout.php';

    // Login 
    $route['login'] = 'back_office/pages/admin/login/login.php';

    if(userCanAdmin()){

        // Home
        $route['listing_photo'] = 'back_office/pages/admin/photo/listingPhoto.php';
        $route['managePhoto'] = 'back_office/pages/admin/photo/managePhoto.php';

        // User
        $route['listing_user'] = 'back_office/pages/admin/user/listingUser.php';
        $route['manageUser'] = 'back_office/pages/admin/user/manageUser.php';

        // Menu
        $route['listing_menu'] = 'back_office/pages/admin/menu/listingMenu.php';
        $route['manageMenu'] = 'back_office/pages/admin/menu/manageMenu.php';

        // Ville
        $route['listing_ville'] = 'back_office/pages/admin/ville/listingVille.php';
        $route['manageVille'] = 'pages/admin/ville/manageVille.php';

        // Pays
        $route['listing_pays'] = 'back_office/pages/admin/pays/listingPays.php';
        $route['managePays'] = 'back_office/pages/admin/pays/managePays.php';

        /* SHOP */

        // Tva
        $route['listing_tva'] = 'back_office/pages/admin/shop/tva/listingTva.php';
        $route['manageTva'] = 'back_office/pages/admin/shop/tva/manageTva.php';

        // Rayon
        $route['listing_rayon'] = 'back_office/pages/admin/shop/rayon/listingRayon.php';
        $route['manageRayon'] = 'back_office/pages/admin/shop/rayon/manageRayon.php';

        // Stock
        $route['listing_stock'] = 'back_office/pages/admin/shop/stock/listingStock.php';
        $route['manageStock'] = 'back_office/pages/admin/shop/stock/manageStock.php';

        // Promotion
        $route['listing_promotion'] = 'back_office/pages/admin/shop/promotion/listingPromotion.php';
        $route['managePromotion'] = 'back_office/pages/admin/shop/promotion/managePromotion.php';

        // Produit
        $route['listing_produit'] = 'back_office/pages/admin/shop/produit/listingProduit.php';
        $route['manageProduit'] = 'back_office/pages/admin/shop/produit/manageProduit.php';

        // Commande
        $route['listing_commande'] = 'back_office/pages/admin/shop/commande/listing_commande.php';
        $route['manage_commande'] = 'back_office/pages/admin/shop/commande/manage_commande.php';
    }
?>