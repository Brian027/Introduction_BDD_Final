<?php
    $bdd = new Data();

    // Gestion du Panier
    if(isset($_GET['add_id_produit']) && !empty($_GET['add_id_produit'])) {
        // L'utilisateur a voulou ajouter un produit au panier

        $gotProduct = false;
        foreach ($_SESSION[SESSION_NAME]['panier'] as $key => $data_produit) {
            if($_SESSION[SESSION_NAME]['panier'][$key]['id_produit'] == $_GET['add_id_produit']) {
                $_SESSION[SESSION_NAME]['panier'][$key]['qte'] = $_SESSION[SESSION_NAME]['panier'][$key]['qte'] + 1;
                $gotProduct = true;
            }
        }

        if(!$gotProduct) {
            $data_produit = array(
                'id_produit' => $_GET['add_id_produit'],
                'qte' => 1
            );
            $_SESSION[SESSION_NAME]['panier'][] = $data_produit;
        }

        header('Location: index.php?page=fo_produit&id_produit='.$_GET['id_produit']);
    }

    // Gestion de l'ID du produit
    if(isset($_GET['id_produit']) && !empty($_GET['id_produit'])) {
        $id_produit = $_GET['id_produit'];
    } else {
        header('Location: index.php?page=fo_home');
    }

    $sql  = " SELECT ";
    $sql .= "    p.id AS id_produit, ";
    $sql .= "    pt.titre AS titre, ";
    $sql .= "    pt.description_courte AS description_courte, ";
    $sql .= "    pt.description_longue AS description_longue, ";
    $sql .= "    p.prixHT AS prixHT, ";
    $sql .= "    p.poids AS poids, ";
    $sql .= "    (p.prixHT + (p.prixHT / 100 * t.value)) AS prixTTC, ";
    $sql .= "    pr.reduction AS reduction,";
    $sql .= "    GROUP_CONCAT(DISTINCT(pi.nom_fichier) SEPARATOR '#') AS fichier_image, ";
    $sql .= "    (SELECT SUM(qte) FROM t_produit_stock WHERE fk_produit=p.id) AS qte ";
    $sql .= "  FROM ";
    $sql .= "    t_produit p ";
    $sql .= "    LEFT JOIN t_produit_trad pt ON pt.fk_produit=p.id AND pt.fk_langue=1";
    $sql .= "    LEFT JOIN t_produit_image pi ON pi.fk_produit=p.id ";
    $sql .= "    LEFT JOIN t_promotion pr ON pr.id=p.fk_promotion ";
    $sql .= "    LEFT JOIN t_tva t ON t.id=p.fk_tva ";
    $sql .= "    LEFT JOIN t_produit_rayon pra ON pra.fk_produit=p.id ";
    $sql .= "  WHERE 1 = 1 ";
    $sql .= "  AND p.id=".$id_produit;
    $sql .= "  GROUP BY p.id ";

    $data_produit = $bdd->getData($sql);
    $data_produit = $data_produit[0];

    // préparation de la mise en forme
    $html = '<div class="detailProduct">
                <div class="flexItem">
                    <div class="imgBloc">';
    $tab_image = explode('#',$data_produit['fichier_image']);
    if(!empty($tab_image)) {
        $first_image = array_shift($tab_image);
        $html .= '<img src="images/produit/'.$first_image.'" class="product_image_big"/>';
    } else {
        $html .= '<img src="https://via.placeholder.com/300x300" class="product_image_big"/>';
    }
    $html .=        '</div>
                </div>
                <div class="flexItem">
                    <h1 class="titleProductDescription">'.$data_produit['titre'].'</h1>';
    

    $html .=        '<div class="price">';

    if($data_produit['reduction'] > 0){
        $html .=        '<span class="priceOff">'.number_format($data_produit['prixTTC'],2,',',' ').' €</span>';
        $html .=        '<span class="priceNow">'.number_format($data_produit['prixTTC'] - ($data_produit['prixTTC'] / 100 * $data_produit['reduction']),2,',',' ').' €</span>';
    } else {
        $html .=        '<span class="priceNow">'.number_format($data_produit['prixTTC'],2,',',' ').' €</span>';
    }
                        
    $html .=        '</div>
                    <div class="state">';
                    $sql  = " SELECT ";
                    $sql .= "    SUM(qte) AS qte ";
                    $sql .= "  FROM ";
                    $sql .= "    t_produit_stock ";
                    $sql .= "  WHERE 1 = 1 ";
                    $sql .= "  AND fk_produit=".$data_produit['id_produit'];
                    $data_qte = $bdd->getData($sql);
                    $data_qte = $data_qte[0];
                    if($data_qte['qte'] > 0) {
                        $html .= '<span class="stateAvailable">
                        Il reste '.$data_qte['qte'].' exemplaire(s) en stock
                        </span>';
                    } else {
                        $html .= '<span class="stateUnavailable">En rupture</span>';
                    }
    $html .=        '<span class="progressLine" aria-label="Progress Bar"></span>
                    </div>
                    <div class="technicalSheet">
                        <h2 class="technicalSheetTitle">Description:</h2>
                        <p class="technicalSheetDescription">
                        '.
                            $data_produit['description_longue'].'
                        </p>
                    </div>
                    <div class="wrapContainer">
                        <h2>Quantité</h2>
                        <div class="form">
                            <div class="inputField">
                                <button id="decrement">-</button>
                                <input type="number" min="1" max="10" value="1" step="1" id="cartQuantity">
                                <button id="increment">+</button>
                            </div>
                            <div class="callToActionProduct">
                                <button class="addToCart" title="Ajouter au panier" aria-label="Ajouter au panier">
                                    <a href="index.php?page=fo_produit&id_produit='.$data_produit['id_produit'].'&add_id_produit='.$data_produit['id_produit'].'">';
                                $html.= '                           <i class="fa-solid fa-cart-plus"></i>';
                                $html.= '                       </a></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';

?>