<?php
$bdd = new Data();

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

    header('Location: index.php?page=fo_home');
}

$sql  = " SELECT ";
$sql .= "    p.id AS id_produit, ";
$sql .= "    pt.titre AS titre, ";
$sql .= "    pt.description_longue AS description, ";
$sql .= "    (p.prixHT + (p.prixHT / 100 * t.value)) AS prixTTC, ";
$sql .= "    pr.reduction AS reduction,";
$sql .= "    GROUP_CONCAT(pi.nom_fichier SEPARATOR '#') AS fichier_image, ";
$sql .= "    (SELECT SUM(qte) FROM t_produit_stock WHERE fk_produit=p.id) AS qte ";
$sql .= "  FROM ";
$sql .= "    t_produit p ";
$sql .= "    LEFT JOIN t_produit_trad pt ON pt.fk_produit=p.id ";
$sql .= "    LEFT JOIN t_produit_image pi ON pi.fk_produit=p.id ";
$sql .= "    LEFT JOIN t_promotion pr ON pr.id=p.fk_promotion ";
$sql .= "    LEFT JOIN t_tva t ON t.id=p.fk_tva ";
$sql .= "    LEFT JOIN t_produit_rayon pra ON pra.fk_produit=p.id ";
$sql .= "  WHERE pt.fk_langue=1";
if(isset($_GET['id_rayon'])) {
    $sql .= " AND pra.fk_rayon=".$_GET['id_rayon'];
}

$sql .= "  GROUP BY p.id ";

// Gestion de la pagination
$nb_produit_par_page = NB_PRODUIT_PAR_PAGE;

// On récupère le nombre de produit total
$sql_count = "SELECT COUNT(*) AS nb_produit FROM (".$sql.") AS t";

$nb_produit = $bdd->getData($sql_count);

// On calcule le nombre de page
$nb_page = ceil($nb_produit[0]['nb_produit'] / $nb_produit_par_page);

// On récupère le numéro de la page courante
if(isset($_GET['offset']) && !empty($_GET['offset'])){
    $page_courante = $_GET['offset'] / $nb_produit_par_page + 1;
} else {
    $page_courante = 1;
}

// On calcule l'offset
$offset = ($page_courante - 1) * $nb_produit_par_page;

// On ajoute l'offset à la requete
$sql .= " LIMIT ".$offset.",".$nb_produit_par_page;

$datas_produit = $bdd->getData($sql);

$sql = "SELECT r.id, rt.nom FROM t_rayon r LEFT JOIN t_rayon_trad rt ON rt.fk_rayon=r.id WHERE rt.fk_langue=".$_SESSION[SESSION_NAME]['id_langue'];
$datas_rayon = $bdd->getData($sql);
$link = array();
$html =  '<main class="shop">
                    <div class="titlePage">
                    <h1 class="titleProductHeader">Nos produits</h1>
                    </div>
                    <div class="containerGlobal">
                        <div class="sideBar">
                                <div class="linkContainer">
                                    <div class="linkCategories">';
                                    if($datas_rayon){
                                        foreach ($datas_rayon as $data_rayon) {
                                            $link[] = '<a href="index.php?page=fo_home&id_rayon='.$data_rayon['id'].'" class="linkCategorie">'.$data_rayon['nom'].'</a>';
                                        }
                                        if($_SESSION[SESSION_NAME]['id_langue'] == 1){
                                            $link[] = '<a href="index.php?page=fo_home">Tous les produits</a>';
                                        } else {
                                            $link[] = '<a href="index.php?page=fo_home" class="allProducts">All products</a>';
                                        }
                                    } else {
                                        $link[] = '<a href="index.php?page=fo_home">Aucune catégorie</a>';
                                    }
$html .= implode($link);                     
$html  .=                   '</div>
                                </div>';
$html .=                 '</div>';

 $html .=  '<div class="galleryProduct">';

 if($datas_produit){
    foreach($datas_produit as $data_produit){
        if(!empty($data_produit['fichier_image'])) {
            // On a une ou plusieurs images.. (au hazard si plusieurs)
            $tab_image = explode('#', $data_produit['fichier_image']);
            shuffle($tab_image);
            $image = 'images/produit/' . $tab_image[0];
        } else {
            // image par defaut => le produit n'a pas d'image...
            $image = 'images/interface/default_product.png';
        }
    $html .=        '<div class="productCard">
                                <div class="imgProduct">
                                    <img src="'.$image.'" alt="Image Asus Rog" />';
                                    if($data_produit['qte']>0) {
                                        $html .= '       <span>';
                                        $html .= '           En stock';
                                        $html .= '       </span>';
                                    } else {
                                        $html .= '       <span>';
                                        $html .= '           En rupture';
                                        $html .= '       </span>';
                                    }
    $html .=               '</div>
                                <div class="productContent">
                                    <div class="tagNote">
                                        <p>TechStore - Asus</p>
                                        <span><i class="fa-solid fa-star"></i>4.9</span>
                                    </div>
                                    <div class="productDescription">
                                       <h2 class="productTitle">'.$data_produit['titre'].'</h2>
                                        <p>
                                            '.substr($data_produit['description'],0,80).'...
                                        </p>
                                        <span><img src="images/arrowtobracket.png" alt="image arrow bracket" />95
                                            Achats</span>
                                    </div>';
    $html .=                        '<div class="productPrice">';
                                        if($data_produit['reduction']>0){
                                            $html .= '<p class="noReduc">'.number_format($data_produit['prixTTC'],2,',',' ').'€</p>';
                                            $html .= '<p class="reduction">'.number_format($data_produit['prixTTC'] - ($data_produit['prixTTC'] / 100 * $data_produit['reduction']),2,',',' ').'€</p>';
                                        } else {
                                            $html .= '<p>'.number_format($data_produit['prixTTC'],2,',',' ').'€</p>';
                                        }
    $html .=                        '</div>
                                    <div class="callToAction">
                                        <button class="addToCard">
                                        <a href="index.php?page=fo_home&add_id_produit='.$data_produit['id_produit'].'">';
        $html.= '                           <i class="fa-solid fa-cart-plus"></i>';
        $html.= '                       </a>';
        $html .=                        '</button>
                                        <button class="viewProduct">
                                            <a href="index.php?page=fo_produit&id_produit='.$data_produit['id_produit'].'" target="_blank">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                        </button>
                                    </div>
                                </div>
                            </div>';
            }
} else {
    $html .= '<h1 class="unAvailableProduct"> Aucun article disponible </h1>';
}
$html .=    '</div>';
$html .=  '</div>';
$html .=    '<div class="pagination">';
$html .=        '<ul>';
// lien vers la page précédente (désactivé si on se trouve sur la 1ère page)
if($datas_produit){
                    if($page_courante == 1){
                        $html .= '<li class="disabled"><a href="#"><i class="fa-solid fa-angle-left"></i></a></li>';
                    } else {
                        $html .= '<li><a href="index.php?page=fo_home&offset='.($page_courante-2)*$nb_produit_par_page.'"><i class="fa-solid fa-angle-left"></i></a></li>';
                    }
                    for($i=1;$i<=$nb_page;$i++){
                        if($i == $page_courante){
                            $html .= '<li class="active">'.$i.'</li>';
                        } else {
                            $html .= '<li><a href="index.php?page=fo_home&offset='.($i-1)*$nb_produit_par_page.'">'.$i.'</a></li>';
                        }
                    }
} 
// lien vers la page suivante (désactivé si on se trouve sur la dernière page)
if($datas_produit){
                    if($page_courante == $nb_page){
                        $html .= '<li class="disabled"><a href="#"><i class="fa-solid fa-angle-right"></i></a></li>';
                    } else {
                        $html .= '<li><a href="index.php?page=fo_home&offset='.$page_courante*$nb_produit_par_page.'"><i class="fa-solid fa-angle-right"></i></a></li>';
                    }
}
$html .=        '</ul>';
$html .=    '</div>
            </main>';
