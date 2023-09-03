<?php
require('../../../inc/param.php');
require('../../../class/data.class.php');
$bdd = new Data();

// UPDATE USER INFORMATIONS
if (isset($_POST) && !empty($_POST)) {
    // On revient d'un formulaire

    // Préparation des informations récuperées du formulaire
    $h = array();
    $h['nom'] = $_POST['nom_ville'];

    // Test pour savoir si on ajoute ou on modifie
    if ($_POST['id_ville'] > 0) {
        // Update de BDD
        $id_ville = $_POST['id_ville'];

        // execution de la requete
        $bdd->sql_update('t_ville', $id_ville, $h);
    } else {
        // execution de la requete
        $id_ville = $bdd->sql_insert('t_ville', $h);
    }
    header("location: ../../../index.php?page=listing_ville");
}

// Verification pour Ajout / Modification
if (isset($_GET['id_ville']) && !empty($_GET['id_ville'])) {
    // Modification
    $id_ville = $_GET['id_ville'];
    $ville = $bdd->build_r_from_id('t_ville', $id_ville);
} else {
    // On est en creation
    $id_ville = 0;
    $ville = array();
    $ville['nom'] = '';
}

// Mise en forme du formulaire
$html = '<div class="blurBG"></div>';
$html .= '<div class="close" onclick="closeForm()"><i class="bx bx-x"></i></div>';
$html .= '<h2>Modifier ' . $ville['nom'] . '</h2>';
// Formulaire de modification
$html .= '<form action="pages/admin/ville/manageVille.php" method="POST">';
$html .= '<div class="formField">';
$html .= '<label for="nom_ville">Ville</label>';
$html .= '<input type="text" name="nom_ville" id="nom_ville" placeholder="Ville" value="' . $ville['nom'] . '" />';
$html .= '</div>';
$html .= '<input type="hidden" name="id_ville" value="' . $id_ville . '" />';
// FORM SUBMIT
$html .= '<div class="formField">';
$html .= '<button>Mettre a jour</button>';
$html .= '</div>';
$html .= '</form>';

echo $html;
?>