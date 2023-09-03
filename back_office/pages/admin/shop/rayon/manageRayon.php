<?php
require('../../../../inc/param.php');
require('../../../../class/data.class.php');
$bdd = new Data();

// UPDATE USER INFORMATIONS
if (isset($_POST) && !empty($_POST)) {
    // On revient d'un formulaire

    // Préparation des informations récuperées du formulaire
    $h = array();
    $h['isActif'] = $_POST['isActif'];

    // Test pour savoir si on ajoute ou on modifie
    if ($_POST['id_rayon'] > 0) {
        // Update de BDD
        $id_rayon = $_POST['id_rayon'];
        $bdd->sql_update('t_rayon', $id_rayon, $h);
        // Gestion de la traduction
        // Supprimer les anciennes traduction de la BDD
        $sql = "DELETE FROM t_rayon_trad WHERE fk_rayon=".$id_rayon;
        $bdd->query($sql);

        $sql = "SELECT * FROM t_langue";
        $datas_langue = $bdd->getData($sql);
        foreach($datas_langue as $data_langue) {
            $libelle = $_POST['nom_rayon'.$data_langue['id']];

            $h = array();
            $h['fk_rayon'] = $id_rayon;
            $h['fk_langue'] = $data_langue['id'];
            $h['nom'] = $libelle;

            $bdd->sql_insert('t_rayon_trad',$h);
        }
    } else {
        // execution de la requete
        $id_rayon = $bdd->sql_insert('t_rayon', $h);
        $h = array();
        $h['fk_rayon'] = $id_rayon;
        $h['fk_langue'] = 1;
        $h['nom'] = $_POST['nom_rayon'];

        $bdd->sql_insert('t_rayon_trad',$h);
    }
    header("location: ../../../../index.php?page=listing_rayon");
}

// Verification pour Ajout / Modification
if (isset($_GET['id_rayon']) && !empty($_GET['id_rayon'])) {
    // Modification
    $id_rayon = $_GET['id_rayon'];
    $data_rayon = $bdd->build_r_from_id('t_rayon', $id_rayon);
} else {
    // On est en creation
    $id_rayon = 0;
    $data_rayon = array();
    $data_rayon['nom_rayon'] = '';
}

// FORMULAIRE DE MODIFICATION

// Mise en forme du formulaire
$html = '<div class="blurBG"></div>';
$html .= '<div class="close" onclick="closeForm()"><i class="bx bx-x"></i></div>';
$html .= '<h2>Modifier</h2>';
$html .= '<form action="pages/admin/shop/rayon/manageRayon.php" method="POST" >';
$html .= '<input type="hidden" name="id_rayon" value="' . $id_rayon . '" />';
$html .= '<div class="formField">';
$sql = "SELECT * FROM t_langue";
$datas_langue = $bdd->getData($sql);
foreach($datas_langue as $data_langue) {
    $sql = "SELECT nom FROM t_rayon_trad WHERE fk_rayon = ".$id_rayon." AND fk_langue = ".$data_langue['id'];
        $temp = $bdd->getData($sql);
        $temp ? $value = $temp[0]['nom'] : $value = '';
        $html .= '<label for="nom_rayon">Libelle ('.$data_langue['nom'].')</label>';
        $html .= '<input type="text" name="nom_rayon'.$data_langue['id'].'" id="nom_rayon" placeholder="'. $value .'" value="'.$value.'" />';
}
$html .= '</div>';
$html .= '<div class="formField">';
$html .= '<label for="isActif">Actif</label>';
$html .= '<select name="isActif" id="isActif">';
$html .= '<option value="1" ' . ($data_rayon['isActif'] == 1 ? 'selected' : '') . '>Oui</option>';
$html .= '<option value="0" ' . ($data_rayon['isActif'] == 0 ? 'selected' : '') . '>Non</option>';
$html .= '</select>';
$html .= '</div>';
// FORM SUBMIT
$html .= '<div class="formField">';
$html .= '<button>Mettre a jour</button>';
$html .= '</div>';
$html .= '</form>';

echo $html;
?>