<?php
$bdd = new Data();

// Suppression ToDo :) ?
if (isset($_GET['id_tva']) && !empty($_GET['id_tva'])) {
    // Suppression de l'utilisateur

    $id_tva = $_GET['id_tva'];
    $bdd->sql_delete('t_tva', $id_tva);

    // Redirection vers le listing des utilisateurs
    header("location: index.php?page=listing_tva");
}

// Préparation de la requete
$sql = 'SELECT';
$sql .= ' id,';
$sql .= ' nom_tva,';
$sql .= ' value';
$sql .= ' FROM t_tva;';

// Execution de la requete sur le serveur de BDD
$datas_tva = $bdd->getData($sql);

// Préparation du retour
$html = '<div class="blurBG"></div>';
$html .= '<div class="manageUser">';
$html .= '<div class="wrapper">';
$html .= '<div class="close" onclick="closeForm();"><i class="bx bx-x"></i></div>';
$html .= '<h2>Ajouter une Tva</h2>';
// Formulaire d'ajout
$html .= '<form action="pages/admin/shop/tva/manageTva.php " method="POST" enctype="multipart/form-data">';
$html .= '<input type="hidden" name="id_tva" value="0" />';
$html .= '<div class="formField">';
$html .= '<label for="nom_tva">Nom Tva</label>';
$html .= '<input type="text" name="nom_tva" id="nom_tva" placeholder="Nom de la TVA" />';
$html .= '</div>';
$html .= '<div class="formField">';
$html .= '<label for="value_tva">Valeur</label>';
$html .= '<input type="number" name="value_tva" id="value_tva" step=".01" placeholder="Valeur de la TVA" />';
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
$html .= '<h1>Listing des Tva</h1>';
$html .= '<div class="tableContainer">';

// Etape 3 : Test du retour de la requete

if (empty($datas_tva)) {
    $html .= '<h1>Aucune tva</h1>';
} else {
    $html .= '<table class="table">';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th>Id</th>';
    $html .= '<th>Tva</th>';
    $html .= '<th>Valeur</th>';
    $html .= '<th>Action</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    foreach($datas_tva as $tva) {

        $html .= '<tbody>';
        $html .= '<tr>';
        $html .= '<td class="res-head">';
        $html .= '<span id=' . $tva['id'] . '">' . $tva['id'] . '</span>';
        $html .= '</td>';
        $html .= '<td class="res-head">';
        $html .= '<span id=' . $tva['id'] . '">' . $tva['nom_tva'] . '</span>';
        $html .= '</td>';
        $html .= '<td class="res-head">';
        $html .= '<span id=' . $tva['id'] . '">' . $tva['value'] . '</span>';
        $html .= '</td>';
        $html .= '<td class="action">';
        $html .= '<a href="#" onclick="tvaForm(' . $tva['id'] . ');" class="btnEditUser"><i class="bx bx-edit"></i></a>';
        $html .= '<a href="index.php?page=listing_tva&id_tva=' . $tva['id'] . '" onclick="return confirm(\'Etes vous sur de vouloir supprimer cet utilisateur ?\')"><i class="bx bx-trash"></i></a>';
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