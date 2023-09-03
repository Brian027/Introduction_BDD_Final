<?php
require('../../../inc/param.php');
require('../../../class/data.class.php');
$bdd = new Data();

// UPDATE USER INFORMATIONS
if (isset($_POST) && !empty($_POST)) {
    // On revient d'un formulaire

    // Préparation des informations récuperées du formulaire
    $h = array();
    $h['url'] = $_POST['url'];
    $h['ordre'] = $_POST['ordre'];
    $h['fk_parent'] = $_POST['fk_parent'];

    // Test pour savoir si on ajoute ou on modifie
    if ($_POST['id_menu'] > 0) {
        // Update de BDD
        $id_menu = $_POST['id_menu'];
        $bdd->sql_update('t_menu', $id_menu, $h);
        // Gestion de la traduction
        // Supprimer les anciennes traduction de la BDD
        $sql = "DELETE FROM t_menu_trad WHERE fk_menu=".$id_menu;
        $bdd->query($sql);

        $sql = "SELECT * FROM t_langue";
        $datas_langue = $bdd->getData($sql);
        foreach($datas_langue as $data_langue) {
            $libelle = $_POST['libelle'.$data_langue['id']];

            $h = array();
            $h['fk_menu'] = $id_menu;
            $h['fk_langue'] = $data_langue['id'];
            $h['libelle'] = $libelle;

            $bdd->sql_insert('t_menu_trad',$h);
        }
    } else {
        // execution de la requete
        $id_menu = $bdd->sql_insert('t_menu', $h);
        $h = array();
        $h['fk_menu'] = $id_menu;
        $h['fk_langue'] = 1;
        $h['libelle'] = $_POST['libelle'];

        $bdd->sql_insert('t_menu_trad',$h);
    }
    
    header("location: ../../../index.php?page=listing_menu");
}

// Verification pour Ajout / Modification
if (isset($_GET['id_menu']) && !empty($_GET['id_menu'])) {
    // Modification
    $id_menu = $_GET['id_menu'];
    $data_menu = $bdd->build_r_from_id('t_menu', $id_menu);
} else {
    // On est en creation
    $id_menu = 0;
    $data_menu = array();
    $data_menu['url'] = '';
    $data_menu['ordre'] = '';
    $data_menu['libelle'] = '';
    $data_menu['fk_parent'] = '';
}

// Formulaire de modification
// Mise en forme du formulaire
$html = '<div class="blurBG"></div>';
$html .= '<div class="close" onclick="closeForm()"><i class="bx bx-x"></i></div>';
$html .= '<h2>Modifier ' . $data_menu['url'] . '</h2>';
$html .= '<form action="pages/admin/menu/manageMenu.php" method="POST" enctype="multipart/form-data">';
$html .= '<input type="hidden" name="id_menu" value="' . $id_menu . '" />';
$html .= '<div class="formField">';
$html .= '<label for="url">Url</label>';
$html .= '<input type="text" name="url" id="url" placeholder="Url" value="' . $data_menu['url'] . '" />';
$html .= '</div>';
$html .= '<div class="formField">';
$html .= '<label for="ordre">Ordre</label>';
$html .= '<input type="text" name="ordre" id="ordre" placeholder="Ordre" value="' . $data_menu['ordre'] . '" />';
$html .= '</div>';
$html .= '<div class="formField">';
    $sql = "SELECT * FROM t_langue";
    $datas_langue = $bdd->getData($sql);
    foreach($datas_langue as $data_langue){
        $sql = "SELECT libelle FROM t_menu_trad WHERE fk_menu = ".$id_menu." AND fk_langue = ".$data_langue['id'];
        $temp = $bdd->getData($sql);
        $temp ? $value = $temp[0]['libelle'] : $value = '';
        $html .= '<label for="libelle">Libelle ('.$data_langue['nom'].')</label>';
        $html .= '<input type="text" name="libelle'.$data_langue['id'].'" id="libelle" placeholder="Libelle" value="'.$value.'" />';
    }
$html .= '</div>';
$html .= '<div class="formField">';
$html .= '<label for="libelle">Parent</label>';
$html .= '<select name="fk_parent" id="fk_parent">';
$html .= '<option value="0">Aucun</option>';
$sql = "SELECT * FROM t_menu WHERE fk_parent = 0";
$datas_menu = $bdd->getData($sql);
foreach($datas_menu as $data_menu){
    $selected = '';
    if($data_menu['id'] == $data_menu['fk_parent']){
        $selected = 'selected';
    }
    $html .= '<option value="'.$data_menu['id'].'" '.$selected.'>'.$data_menu['url'].'</option>';
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