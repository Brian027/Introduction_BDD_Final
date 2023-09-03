<?php
require('../../../inc/param.php');
require('../../../class/data.class.php');
$bdd = new Data();

// UPDATE USER INFORMATIONS
if (isset($_POST) && !empty($_POST)) {
    // On revient d'un formulaire

    // Préparation des informations récuperées du formulaire
    $h = array();
    $h['nom'] = $_POST['nom'];

    // Test pour savoir si on ajoute ou on modifie
    if ($_POST['id_pays'] > 0) {
        // Update de BDD
        $id_pays = $_POST['id_pays'];

        // execution de la requete
        $bdd->sql_update('t_pays', $id_pays, $h);
    } else {
        // execution de la requete
        $id_pays = $bdd->sql_insert('t_pays', $h);
    }
    header("location: ../../../index.php?page=listing_pays");
}

// Verification pour Ajout / Modification
if (isset($_GET['id_pays']) && !empty($_GET['id_pays'])) {
    // Modification
    $id_pays = $_GET['id_pays'];
    $pays = $bdd->build_r_from_id('t_pays', $id_pays);
} else {
    // On est en creation
    $id_pays = 0;
    $pays = array();
    $pays['nom'] = '';
}

// Mise en forme du formulaire
$html = '<div class="blurBG"></div>';
$html .= '<div class="close" onclick="closeForm()"><i class="bx bx-x"></i></div>';
$html .= '<h2>Modifier ' . $pays['nom'] . '</h2>';
// Formulaire de modification
$html .= '<form action="pages/admin/pays/managePays.php" method="POST" >';
$html .= '<input type="hidden" name="id_pays" value="' . $id_pays . '" />';
$html .= '<div class="formField">';
$html .= '<label for="nom">Nom</label>';
$html .= '<input type="text" name="nom" id="nom" placeholder="Nom" value="' . $pays['nom'] . '" />';
$html .= '</div>';
// FORM SUBMIT
$html .= '<div class="formField">';
$html .= '<button>Mettre a jour</button>';
$html .= '</div>';
$html .= '</form>';

echo $html;
?>