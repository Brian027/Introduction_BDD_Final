<?php
$bdd = new Data();

// Suppression ToDo :) ?
if (isset($_GET['id_produit']) && !empty($_GET['id_produit'])) {
    // Suppression de l'utilisateur

    $id_produit = $_GET['id_produit'];
    $bdd->sql_delete('t_produit', $id_produit);

    // Redirection vers le listing des utilisateurs
    header("location: index.php?page=listing_produit");
}

// Préparation de la requete
$sql = 'SELECT';
    $sql.= '    p.id AS id_produit,';
    $sql.= '    pt.titre AS titre,';
    $sql.= '    (SELECT SUM(qte) FROM t_produit_stock WHERE fk_produit=p.id) AS qte,';
    $sql.= '    p.prixHT AS prixHT,';
    $sql.= '    t.value AS value,';
    $sql.= '    (p.prixHT + (p.prixHT / 100 * t.value)) AS prixTTC,';
    $sql.= '    p.isActif AS isActif';
    $sql.= ' FROM t_produit p';
    $sql.= ' LEFT JOIN t_produit_trad pt ON pt.fk_produit = p.id';
    $sql.= ' LEFT JOiN t_tva t ON t.id=p.fk_tva';
    $sql.= ' WHERE pt.fk_langue = 1';

// Execution de la requete sur le serveur de BDD
$datas_produit = $bdd->getData($sql);

// Préparation du retour
$html = '<div class="blurBG"></div>';
$html .= '<div class="manageUser">';
$html .= '<div class="wrapper productWrapper">';
$html .= '<div class="close" onclick="closeForm();"><i class="bx bx-x"></i></div>';
$html .= '<h2>Ajout d\'un produit</h2>';
// Formulaire d'ajout
$html .= '<form action="pages/admin/shop/produit/manageProduit.php " method="POST" enctype="multipart/form-data">';
$html .= '<input type="hidden" name="id_produit" value="0" />';
$html .= '<div class="containerForm">';
$html .= '<div class="leftContent">';
// Information produit
$html .= '<div class="infoProduct">';
$html .= '<h3><span>Etape 1:</span> Information produit</h3>';
$html .= '<div class="listInfoProduct">';
$html .= '<ul>';
$html .= '<li>Date de création: <strong>'. date('d/m/Y') ." ". date('H:i:s', time() + 3600) .' </strong></li>';
$html .= '<li>Date de modification: <strong>'. date('d/m/Y') ." ". date('H:i:s', time() + 3600) .' </strong></li>';
$html .= '<li>Utilisateur: <strong>'. $_SESSION[SESSION_NAME]['nom_user'] .'</strong></li>';
$html .= '<li>';
$html .= '<label for="isActif">Actif</label>';
$html .= '<select name="isActif" id="isActif">';
$html .= '<option value="0">Non</option>';
$html .= '<option value="1">Oui</option>';
$html .= '</select>';
$html .= '</li>';
$html .= '</ul>';
$html .= '</div>';
$html .= '</div>';
$html .= '<div class="formField">';
$html .= '<label for="nom_produit">Nom Produit</label>';
$html .= '<input type="text" name="nom_produit" id="nom_produit" placeholder="Nom du Produit" />';
$html .= '</div>';
$html .= '<div class="formField">';
$html .= '<label for="description_courte">Description Courte</label>';
$html .= '<textarea name="description_courte" id="description_courte" placeholder="Description Courte"></textarea>';
$html .= '</div>';
$html .= '<div class="formField">';
$html .= '<label for="description_longue">Description Longue</label>';
$html .= '<textarea name="description_longue" id="description_longue" placeholder="Description Longue"></textarea>';
$html .= '</div>';
$html .= '</div>';
$html .= '<div class="rightContent">';
$html .= '<div class="formGroupField">';
$html .= '<div class="formField">';
$html .= '<h3><span>Etape 2:</span> Gestion du Tarif, TVA et Promotion</h3>';
$html .= '<label for="prixHT">Prix HT</label>';
$html .= '<input type="number" name="prixHT" id="prixHT" placeholder="Prix HT" step=".01" />';
$html .= '</div>';
$html .= '<div class="formField">';
$html .= '<label for="tva">TVA</label>';
$html .= '<select name="tva" id="tva">';
$html .= '<option value="0">Choisir une TVA</option>';
$sql = "SELECT * FROM t_tva;";
$datas_tva = $bdd->getData($sql);
if($datas_tva){
    foreach ($datas_tva as $tva) {
        $html .= '<option value="' . $tva['id'] . '">' . $tva['value'] . '</option>';
    }
}
$html .= '</select>';
$html .= '</div>';
$html .= '<div class="formField">';
$html .= '<label for="promotion">Promotion</label>';

$sql = "SELECT p.id as id, pt.nom as nom";
$sql .= " FROM t_promotion p";
$sql .= " LEFT JOIN t_promotion_trad pt ON pt.fk_promotion = p.id";
$sql .= " WHERE p.isActif = 1";
$sql .= " AND pt.fk_langue = 1;";
$datas_promotion = $bdd->getData($sql);
$html .= '<select name="promotion" id="promotion">';
$html .= '<option value="0">Choisir une promotion</option>';
if($datas_promotion){
    foreach ($datas_promotion as $promotion) {
        $html .= '<option value="' . $promotion['id'] . '">' . $promotion['nom'] . '</option>';
    }
}
$html .= '</select>';
$html .= '</div>';
$html .= '<div class="formField">';
$html .= '<label for="debut_promotion">Début Promotion</label>';
$html .= '<input type="date" name="debut_promotion" id="debut_promotion" placeholder="Début Promotion" />';
$html .= '</div>';
$html .= '<div class="formField">';
$html .= '<label for="fin_promotion">Fin Promotion</label>';
$html .= '<input type="date" name="fin_promotion" id="fin_promotion" placeholder="Fin Promotion" />';
$html .= '</div>';
$html .= '</div>';
$html .= '<div class="formField">';
$html .= '<h3><span>Etape 3:</span> Gestion du poids et du stock</h3>';
$html .= '<label for="poids">Poids</label>';
$html .= '<input type="number" name="poids" id="poids" placeholder="Poids" step=".01" />';
$html .= '</div>';
$html .= '<div class="formField">';
$html .= '<h4>Stock</h4>';
$sql = "SELECT * FROM t_stock";
$sql .= " WHERE isActif = 1";
$datas_stock = $bdd->getData($sql);
if($datas_stock){
    foreach ($datas_stock as $stock) {
        $html .= '<label for="stock_' . $stock['id'] . '">' . $stock['nom'] . '</label>';
        $html .= '<input type="number" name="stock_' . $stock['id'] . '" id="stock_' . $stock['id'] . '" placeholder="' . $stock['nom'] . '" step=".01" />';
    }
}
$html .= '</div>';
$html .= '<div class="formField">';
$html .= '<h3><span>Etape 4:</span> Gestion des rayons</h3>';
// SELECT RAYON
$sql = "SELECT * FROM t_rayon_trad";
$sql .= " WHERE fk_langue = 1";
$datas_rayon = $bdd->getData($sql);
$html .= '<label for="rayon_">Rayon</label>';
$html .= '<select name="rayon_" id="rayon">';
$html .= '<option value="0">Choisir un rayon</option>';
if (!empty($datas_rayon)) {
    foreach ($datas_rayon as $rayon) {
        $html .= '<option value="' . $rayon['fk_rayon'] . '">' . $rayon['nom'] . '</option>';
    }
}
$html .= '</select>';
$html .= '</div>';
$html .= '<div class="formField">';
$html .= '<h3><span>Etape 5:</span> Gestion des images</h3>';
$html .= '<label for="image_product[]">Image</label>';
$html .= '<input type="file" name="image_product[]" id="image" placeholder="Image" multiple />';
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';
// FORM SUBMIT
$html .= '<div class="formField">';
$html .= '<button>Ajouter</button>';
$html .= '</div>';
$html .= '</form>';
$html .= '</div>';
$html .= '</div>';
$html .= '<button class="btnAddUser"><i class="bx bx-plus"></i></button>';

$html .= '<div class="containerUsers">';
$html .= '<div class="listingUser">';
$html .= '<h1>Listing des produits</h1>';
$html .= '<div class="tableContainer">';

// Etape 3 : Test du retour de la requete

if(empty($datas_produit)){
    $html .= '<h1>Aucun produit pour l\'instant !</h1>';
} else {
    $html .= '<table class="table">';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th>Id</th>';
    $html .= '<th>Nom</th>';
    $html .= '<th>Utilisateur</th>';
    $html .= '<th>Prix HT</th>';
    $html .= '<th>Code TVA</th>';
    $html .= '<th>Prix TTC</th>';
    $html .= '<th>Statut</th>';
    $html .= '<th>Action</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    foreach($datas_produit as $produit) {

        $html .= '<tbody>';
        $html .= '<tr>';
        $html .= '<td class="res-head">';
        $html .= '<span>' . $produit['id_produit'] . '</span>';
        $html .= '</td>';
        $html .= '<td class="res-head">';
        $html .= '<span>' . $produit['titre'] . '</span>';
        $html .= '</td>';
        $html .= '<td class="res-head">';
        $html .= '<span>' . $_SESSION[SESSION_NAME]['nom_user'] . '</span>';
        $html .= '</td>';
        $html .= '<td class="res-head">';
        $html .= '<span>' . $produit['prixHT'] . '</span>';
        $html .= '</td>';
        $html .= '<td class="res-head">';
        $html .= '<span>' . $produit['value'] . '</span>';
        $html .= '</td>';
        $html .= '<td class="res-head">';
        $html .= '<span>' . number_format($produit['prixTTC'],2) . '</span>';
        $html .= '<td class="res-head">';
        if ($produit['isActif'] == 1) {
            $html .= '<span>Actif</span>';
        } else {
            $html .= '<span>Inactif</span>';
        }
        $html .= '</td>';
        $html .= '<td class="action">';
        $html .= '<a href="#" onclick="produitForm(' . $produit['id_produit'] . ');" class="btnEditUser"><i class="bx bx-edit"></i></a>';
        $html .= '<a href="index.php?page=listing_produit&id_produit=' . $produit['id_produit'] . '" onclick="return confirm(\'Etes vous sur de vouloir supprimer cet utilisateur ?\')"><i class="bx bx-trash"></i></a>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</tbody>';
    }
}

$html .= '</table>';
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';

$page = new Page(true);
$page->build_content($html);
$page->show();

?>