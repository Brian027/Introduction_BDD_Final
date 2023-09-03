<?php
$bdd = new Data();

// Suppression ToDo :) ?
if (isset($_GET['id_pays']) && !empty($_GET['id_pays'])) {
    // Suppression de l'utilisateur

    $id_pays = $_GET['id_pays'];
    $bdd->sql_delete('t_pays', $id_pays);

    // Redirection vers le listing des utilisateurs
    header("location: index.php?page=listing_pays");
}

// Préparation de la requete
$sql = 'SELECT';
$sql .= ' id,';
$sql .= ' nom';
$sql .= ' FROM t_pays;';

// Execution de la requete sur le serveur de BDD
$datas_pays = $bdd->getData($sql);

// Préparation du retour
$html = '<div class="blurBG"></div>';
$html .= '<div class="manageUser">';
$html .= '<div class="wrapper">';
$html .= '<div class="close" onclick="closeForm();"><i class="bx bx-x"></i></div>';
$html .= '<h2>Ajouter un Pays</h2>';
// Formulaire d'ajout
$html .= '<form action="pages/admin/pays/managePays.php" method="POST" enctype="multipart/form-data">';
$html .= '<input type="hidden" name="id_pays" value="0" />';
$html .= '<div class="formField">';
$html .= '<label for="nom">Nom</label>';
$html .= '<input type="text" name="nom" id="nom" placeholder="Nom" />';
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
$html .= '<h1>Listing des Pays</h1>';
$html .= '<div class="tableContainer">';
$html .= '<table class="table">';
$html .= '<thead>';
$html .= '<tr>';
$html .= '<th>Id</th>';
$html .= '<th>Pays</th>';
$html .= '<th>Action</th>';
$html .= '</tr>';
$html .= '</thead>';

// Etape 3 : Test du retour de la requete

foreach($datas_pays as $pays) {

    $html .= '<tbody>';
    $html .= '<tr>';
    $html .= '<td class="res-head">';
    $html .= '<span id=' . $pays['id'] . '">' . $pays['id'] . '</span>';
    $html .= '</td>';
    $html .= '<td class="res-head">';
    $html .= '<span id=' . $pays['id'] . '">' . $pays['nom'] . '</span>';
    $html .= '</td>';
    $html .= '<td class="action">';
    $html .= '<a href="#" onclick="paysForm(' . $pays['id'] . ');" class="btnEditUser"><i class="bx bx-edit"></i></a>';
    $html .= '<a href="index.php?page=listing_pays&id_pays=' . $pays['id'] . '" onclick="return confirm(\'Etes vous sur de vouloir supprimer cet utilisateur ?\')"><i class="bx bx-trash"></i></a>';
    $html .= '</td>';
    $html .= '</tr>';
    $html .= '</tbody>';
}

$html .= '</table>';
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';

$page = new Page(true);
$page->build_content($html);
$page->show();
?>