<?php
$bdd = new Data();

// Suppression ToDo :) ?
if (isset($_GET['id_stock']) && !empty($_GET['id_stock'])) {
    // Suppression de l'utilisateur

    $id_stock = $_GET['id_stock'];
    $bdd->sql_delete('t_stock', $id_stock);

    // Redirection vers le listing des utilisateurs
    header("location: index.php?page=listing_stock");
}

// Préparation de la requete
$sql = 'SELECT';
$sql .= ' id,';
$sql .= ' nom,';
$sql .= ' isActif';
$sql .= ' FROM t_stock';

// Execution de la requete sur le serveur de BDD
$datas_stock = $bdd->getData($sql);

// Préparation du retour
$html = '<div class="blurBG"></div>';
$html .= '<div class="manageUser">';
$html .= '<div class="wrapper">';
$html .= '<div class="close" onclick="closeForm();"><i class="bx bx-x"></i></div>';
$html .= '<h2>Ajouter un stock</h2>';
// Formulaire d'ajout
$html .= '<form action="pages/admin/shop/stock/manageStock.php " method="POST" enctype="multipart/form-data">';
$html .= '<input type="hidden" name="id_stock" value="0" />';
$html .= '<div class="formField">';
$html .= '<label for="nom_stock">Nom Stock</label>';
$html .= '<input type="text" name="nom_stock" id="nom_stock" placeholder="Nom du Stock" />';
$html .= '</div>';
$html .= '<div class="formField">';
$html .= '<label for="isActif">Status</label>';
$html .= '<select name="isActif" id="isActif">';
$html .= '<option>Choisir un statut</option>';
$html .= '<option value="1">Actif</option>';
$html .= '<option value="0">Inactif</option>';
$html .= '</select>';
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
$html .= '<h1>Listing des Stocks</h1>';
$html .= '<div class="tableContainer">';

// Etape 3 : Test du retour de la requete

if(empty($datas_stock)){
    $html .= '<h1>Aucun stock</h1>';
} else {
    $html .= '<table class="table">';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th>Id</th>';
    $html .= '<th>Nom du Stock</th>';
    $html .= '<th>Statut</th>';
    $html .= '<th>Action</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    foreach($datas_stock as $stock) {

        $html .= '<tbody>';
        $html .= '<tr>';
        $html .= '<td class="res-head">';
        $html .= '<span id=' . $stock['id'] . '">' . $stock['id'] . '</span>';
        $html .= '</td>';
        $html .= '<td class="res-head">';
        $html .= '<span id=' . $stock['id'] . '">' . $stock['nom'] . '</span>';
        $html .= '</td>';
        $html .= '<td class="res-head">';
        if ($stock['isActif'] == 1) {
            $html .= '<span id=' . $stock['id'] . '">Actif</span>';
        } else {
            $html .= '<span id=' . $stock['id'] . '">Inactif</span>';
        }
        $html .= '<td class="action">';
        $html .= '<a href="#" onclick="stockForm(' . $stock['id'] . ');" class="btnEditUser"><i class="bx bx-edit"></i></a>';
        $html .= '<a href="index.php?page=listing_stock&id_stock=' . $stock['id'] . '" onclick="return confirm(\'Etes vous sur de vouloir supprimer cet utilisateur ?\')"><i class="bx bx-trash"></i></a>';
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
