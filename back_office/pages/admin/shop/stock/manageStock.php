<?php
require('../../../../inc/param.php');
require('../../../../class/data.class.php');
$bdd = new Data();

// UPDATE USER INFORMATIONS
if (isset($_POST) && !empty($_POST)) {
    // On revient d'un formulaire

    // Préparation des informations récuperées du formulaire
    $h = array();
    $h['nom'] = $_POST['nom_stock'];
    $h['isActif'] = $_POST['isActif'];

    // Test pour savoir si on ajoute ou on modifie
    if ($_POST['id_stock'] > 0) {
        // Update de BDD
        $id_stock = $_POST['id_stock'];
        $bdd->sql_update('t_stock', $id_stock, $h);
    } else {
        // execution de la requete
        $id_stock = $bdd->sql_insert('t_stock', $h);
    }
    header("location: ../../../../index.php?page=listing_stock");
}

// Verification pour Ajout / Modification
if (isset($_GET['id_stock']) && !empty($_GET['id_stock'])) {
    // Modification
    $id_stock = $_GET['id_stock'];
    $data_stock = $bdd->build_r_from_id('t_stock', $id_stock);
} else {
    // On est en creation
    $id_stock = 0;
    $data_stock = array();
    $data_stock['nom_stock'] = '';
}

// FORMULAIRE DE MODIFICATION

// Mise en forme du formulaire
$html = '<div class="blurBG"></div>';
$html .= '<div class="close" onclick="closeForm()"><i class="bx bx-x"></i></div>';
$html .= '<h2>Modifier</h2>';
$html .= '<form action="pages/admin/shop/stock/manageStock.php" method="POST" >';
$html .= '<input type="hidden" name="id_stock" value="' . $id_stock . '" />';
$html .= '<div class="formField">';
$html .= '<label for="nom_stock">Nom</label>';
$html .= '<input type="text" name="nom_stock" id="nom_stock" placeholder="Nom" value="' . $data_stock['nom'] . '" />';
$html .= '</div>';
$html .= '<div class="formField">';
$html .= '<label for="isActif">Status</label>';
$html .= '<select name="isActif" id="isActif">';
$sql = 'SELECT';
$sql .= ' id,';
$sql .= ' isActif';
$sql .= ' FROM t_stock';
$sql .= ' WHERE id = ' . $id_stock;
$datas = $bdd->getData($sql);
foreach ($datas as $data) {
    if($data['isActif'] == 1){
        $html .= '<option value="1" selected>Actif</option>';
        $html .= '<option value="0">Inactif</option>';
    } else {
        $html .= '<option value="1">Actif</option>';
        $html .= '<option value="0" selected>Inactif</option>';
    }
}
$html .= '</select>';
$html .= '</div>';
// FORM SUBMIT
$html .= '<div class="formField">';
$html .= '<button>Mettre a jour</button>';
$html .= '</div>';
$html .= '</form>';

echo $html;
?>