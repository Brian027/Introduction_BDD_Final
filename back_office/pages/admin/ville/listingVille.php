<?php
$bdd = new Data();

// Suppression ToDo :) ?
if (isset($_GET['id_ville']) && !empty($_GET['id_ville'])) {
    // Suppression de l'utilisateur

    $id_ville = $_GET['id_ville'];
    $bdd->sql_delete('t_ville', $id_ville);

    // Redirection vers le listing des utilisateurs
    header("location: index.php?page=listing_ville");
}

// Préparation de la requete
$sql = 'SELECT';
$sql .= ' id,';
$sql .= ' nom';
$sql .= ' FROM t_ville;';

// Execution de la requete sur le serveur de BDD
$datas_ville = $bdd->getData($sql);

// Préparation du retour
$html = '<div class="blurBG"></div>';
$html .= '<div class="manageUser">';
$html .= '<div class="wrapper">';
$html .= '<div class="close" onclick="closeForm();"><i class="bx bx-x"></i></div>';
$html .= '<h2>Ajouter une ville</h2>';
// Formulaire d'ajout
$html .= '<form action="pages/admin/ville/manageVille.php" method="POST" enctype="multipart/form-data">';
$html .= '<div class="formField">';
$html .= '<label for="nom_ville">Nom</label>';
$html .= '<input type="text" name="nom_ville" id="nom_ville" placeholder="Ville" />';
$html .= '</div>';
$html .= '<input type="hidden" name="id_ville" value="0" />';
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
$html .= '<h1>Listing des Villes</h1>';
$html .= '<div class="tableContainer">';
$html .= '<table class="table">';
$html .= '<thead>';
$html .= '<tr>';
$html .= '<th>Id</th>';
$html .= '<th>Nom</th>';
$html .= '<th>Action</th>';
$html .= '</tr>';
$html .= '</thead>';

// Etape 3 : Test du retour de la requete

foreach($datas_ville as $ville) {

    $html .= '<tbody>';
    $html .= '<tr>';
    $html .= '<td class="res-head">';
    $html .= '<span id=' . $ville['id'] . '">' . $ville['id'] . '</span>';
    $html .= '</td>';
    $html .= '<td class="res-head">';
    $html .= '<span id=' . $ville['id'] . '">' . $ville['nom'] . '</span>';
    $html .= '</td>';
    $html .= '<td class="action">';
    $html .= '<a href="#" onclick="villeForm(' . $ville['id'] . ');" class="btnEditUser"><i class="bx bx-edit"></i></a>';
    $html .= '<a href="index.php?page=listing_ville&id_ville=' . $ville['id'] . '" onclick="return confirm(\'Etes vous sur de vouloir supprimer cet utilisateur ?\')"><i class="bx bx-trash"></i></a>';
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