<?php
require('../../../../inc/param.php');
require('../../../../class/data.class.php');
$bdd = new Data();

// UPDATE USER INFORMATIONS
if (isset($_POST) && !empty($_POST)) {
    // On revient d'un formulaire

    // Préparation des informations récuperées du formulaire
    $h = array();
    $h['isActif'] = $_POST['statut'];
    $h['reduction'] = $_POST['reduction'];

    // Test pour savoir si on ajoute ou on modifie
    if ($_POST['id_promotion'] > 0) {
        // Update de BDD
        $id_promotion = $_POST['id_promotion'];
        $bdd->sql_update('t_promotion', $id_promotion, $h);
        // Gestion de la traduction
        // Supprimer les anciennes traduction de la BDD
        $sql = "DELETE FROM t_promotion_trad WHERE fk_promotion=".$id_promotion;
        $bdd->query($sql);

        $sql = "SELECT * FROM t_langue";
        $datas_langue = $bdd->getData($sql);
        foreach($datas_langue as $data_langue) {
            $libelle = $_POST['nom_promotion'.$data_langue['id']];

            $h = array();
            $h['fk_promotion'] = $id_promotion;
            $h['fk_langue'] = $data_langue['id'];
            $h['nom'] = $libelle;

            $bdd->sql_insert('t_promotion_trad',$h);
        }
    } else {
        // execution de la requete
        $id_promotion = $bdd->sql_insert('t_promotion', $h);
        $h = array();
        $h['fk_promotion'] = $id_promotion;
        $h['fk_langue'] = 1;
        $h['nom'] = $_POST['nom_promotion'];

        $bdd->sql_insert('t_promotion_trad',$h);
    }
    header("location: ../../../../index.php?page=listing_promotion");
}

// Verification pour Ajout / Modification
if (isset($_GET['id_promotion']) && !empty($_GET['id_promotion'])) {
    // Modification
    $id_promotion = $_GET['id_promotion'];
    $data_promotion = $bdd->build_r_from_id('t_promotion', $id_promotion);
} else {
    // On est en creation
    $id_promotion = 0;
    $data_promotion = array();
    $data_promotion['nom_promotion'] = '';
    $data_promotion['reduction'] = '';
    $data_promotion['isActif'] = '';
}

// FORMULAIRE DE MODIFICATION

// Mise en forme du formulaire
$html = '<div class="blurBG"></div>';
$html .= '<div class="close" onclick="closeForm()"><i class="bx bx-x"></i></div>';
$html .= '<h2>Modifier</h2>';
$html .= '<form action="pages/admin/shop/promotion/managePromotion.php" method="POST" >';
$html .= '<input type="hidden" name="id_promotion" value="' . $id_promotion . '" />';
$html .= '<div class="formField">';
$sql = "SELECT * FROM t_langue";
$datas_langue = $bdd->getData($sql);
foreach($datas_langue as $data_langue) {
    $sql = "SELECT nom FROM t_promotion_trad WHERE fk_promotion = ".$id_promotion." AND fk_langue = ".$data_langue['id'];
        $temp = $bdd->getData($sql);
        $temp ? $value = $temp[0]['nom'] : $value = '';
        $html .= '<label for="nom_promotion">Libelle ('.$data_langue['nom'].')</label>';
        $html .= '<input type="text" name="nom_promotion'.$data_langue['id'].'" id="nom_promotion" placeholder="'. $value .'" value="'.$value.'" />';
}
$html .= '</div>';
$html .= '<div class="formField">';
$html .= '<label for="reduction">Reduction</label>';
$html .= '<input type="text" name="reduction" id="reduction" placeholder="Reduction" value="' . $data_promotion['reduction'] . '" />';
$html .= '</div>';
$html .= '<div class="formField">';
$html .= '<label for="statut">Statut</label>';
$html .= '<select name="statut" id="statut">';
$html .= '<option value="1" ' . ($data_promotion['isActif'] == 1 ? 'selected' : '') . '>Actif</option>';
$html .= '<option value="0" ' . ($data_promotion['isActif'] == 0 ? 'selected' : '') . '>Inactif</option>';
$html .= '</select>';
$html .= '</div>';
// FORM SUBMIT
$html .= '<div class="formField">';
$html .= '<button>Mettre a jour</button>';
$html .= '</div>';
$html .= '</form>';

echo $html;
?>