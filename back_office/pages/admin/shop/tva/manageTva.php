<?php
require('../../../../inc/param.php');
require('../../../../class/data.class.php');
$bdd = new Data();

// UPDATE USER INFORMATIONS
if (isset($_POST) && !empty($_POST)) {
    // On revient d'un formulaire

    // Préparation des informations récuperées du formulaire
    $h = array();
    $h['nom_tva'] = $_POST['nom_tva'];
    $h['value'] = $_POST['value_tva'];

    // Test pour savoir si on ajoute ou on modifie
    if ($_POST['id_tva'] > 0) {
        // Update de BDD
        $id_tva = $_POST['id_tva'];

        // execution de la requete
        $bdd->sql_update('t_tva', $id_tva, $h);
    } else {
        // execution de la requete
        $id_tva = $bdd->sql_insert('t_tva', $h);
    }
    header("location: ../../../../index.php?page=listing_tva");
}

// Verification pour Ajout / Modification
if (isset($_GET['id_tva']) && !empty($_GET['id_tva'])) {
    // Modification
    $id_tva = $_GET['id_tva'];
    $tva = $bdd->build_r_from_id('t_tva', $id_tva);
} else {
    // On est en creation
    $id_pays = 0;
    $tva = array();
    $tva['nom'] = '';
}

// Mise en forme du formulaire
$html = '<div class="blurBG"></div>';
$html .= '<div class="close" onclick="closeForm()"><i class="bx bx-x"></i></div>';
$html .= '<h2>Modifier ' . $tva['nom_tva'] . '</h2>';
// Formulaire de modification
$html .= '<form action="pages/admin/shop/tva/manageTva.php" method="POST" >';
$html .= '<input type="hidden" name="id_tva" value="' . $id_tva . '" />';
$html .= '<div class="formField">';
$html .= '<label for="nom_tva">Nom Tva</label>';
$html .= '<input type="text" name="nom_tva" id="nom_tva" placeholder="Nom de la Tva" value="' . $tva['nom_tva'] . '" />';
$html .= '</div>';
$html .= '<div class="formField">';
$html .= '<label for="value_tva">Value Tva</label>';
$html .= '<input type="number" name="value_tva" id="value_tva" step=".01" placeholder="Valeur de la TVA" value="'. $tva['value'] .'" />';
$html .= '</div>';
// FORM SUBMIT
$html .= '<div class="formField">';
$html .= '<button>Mettre a jour</button>';
$html .= '</div>';
$html .= '</form>';

echo $html;
?>