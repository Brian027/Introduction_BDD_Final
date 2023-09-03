<?php
require('../../../inc/param.php');
require('../../../class/data.class.php');
$bdd = new Data();

// UPDATE USER INFORMATIONS
if (isset($_POST) && !empty($_POST)) {
    // On revient d'un formulaire

    // Préparation des informations récuperées du formulaire
    $h = array();
    $h['fk_user'] = 1;
    $h['ordre'] = 0;

    // Gestion de l'avatar
    if (isset($_FILES) && !empty($_FILES) && !empty($_FILES['photo']['name'])) {
        // Generation d'un nom unique
        $tab_name = explode('.', $_FILES['photo']['name']);
        $unique_name = uniqid('img_') . '.' . $tab_name[1];

        // Préparation de l'upload
        $uploaddir = '../../../images/';
        $uploadfile = $uploaddir . $unique_name;
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadfile)) {
            $h['photographie'] = $unique_name;
        }
    }

    // Test pour savoir si on ajoute ou on modifie
    if ($_POST['id_photo'] > 0) {
        // Update de BDD
        $id_photo = $_POST['id_photo'];
        $bdd->sql_update('t_photo', $id_photo, $h);
        // Gestion de la traduction
        // Supprimer les anciennes traduction de la BDD
        $sql = "DELETE FROM t_photo_trad WHERE fk_photo=" . $id_photo;
        $bdd->query($sql);

        $sql = "SELECT * FROM t_langue";
        $datas_langue = $bdd->getData($sql);
        foreach ($datas_langue as $data_langue) {
            $libelle = $_POST['nom_photo' . $data_langue['id']];

            $h = array();
            $h['fk_photo'] = $id_photo;
            $h['fk_langue'] = $data_langue['id'];
            $h['nom'] = $libelle;

            $bdd->sql_insert('t_photo_trad', $h);
        }
    } else {
        // execution de la requete
        $id_photo = $bdd->sql_insert('t_photo', $h);
        $h = array();
        $h['fk_photo'] = $id_photo;
        $h['fk_langue'] = 1;
        $h['nom'] = $_POST['titre_photo'];
        $h['description'] = $_POST['description_photo'];

        $bdd->sql_insert('t_photo_trad',$h);
    }
    header("location: ../../../index.php?page=listing_photo");
}

// Verification pour Ajout / Modification
if (isset($_GET['id_photo']) && !empty($_GET['id_photo'])) {
    // Modification
    $id_photo = $_GET['id_photo'];
    $photo = $bdd->build_r_from_id('t_photo', $id_photo);
} else {
    // On est en creation
    $id_photo = 0;
    $photo = array();
    $photo['titre'] = '';
    $photo['description'] = '';
    $photo['photographie'] = '';
}

// Formulaire de modification

// Mise en forme du formulaire
$html = '<div class="blurBG"></div>';
$html .= '<div class="close" onclick="closeForm()"><i class="bx bx-x"></i></div>';
$html .= '<h2>Modifier</h2>';
$html .= '<form action="pages/admin/photo/managePhoto.php" method="POST" enctype="multipart/form-data">';
$html .= '<input type="hidden" name="id_photo" value="' . $id_photo . '" />';
$html .= '<div class="formField">';
$sql = "SELECT * FROM t_langue";
$datas_langue = $bdd->getData($sql);
foreach ($datas_langue as $data_langue) {
    $sql = "SELECT nom FROM t_photo_trad WHERE fk_photo = " . $id_photo . " AND fk_langue = " . $data_langue['id'];
    $temp = $bdd->getData($sql);
    $temp ? $value = $temp[0]['nom'] : $value = '';
    $html .= '<label for="nom_photo">Libelle (' . $data_langue['nom'] . ')</label>';
    $html .= '<input type="text" name="nom_photo' . $data_langue['id'] . '" id="nom_photo" placeholder="' . $value . '" value="' . $value . '" />';
}
$html .= '</div>';
$html .= '<div class="formField">';
foreach($datas_langue as $data_langue) {
    $sql = "SELECT description FROM t_photo_trad WHERE fk_photo = " . $id_photo . " AND fk_langue = " . $data_langue['id'];
    $temp = $bdd->getData($sql);
    $temp ? $value = $temp[0]['description'] : $value = '';
    $html .= '<label for="description_photo">Description (' . $data_langue['nom'] . ')</label>';
    $html .= '<textarea name="description_photo' . $data_langue['id'] . '" id="description_photo" placeholder="' . $value . '" value="' . $value . '"></textarea>';
}
$html .= '</div>';
$html .= '<div class="formField">';
$html .= '<label for="photo">Photo</label>';
$html .= '<input type="file" name="photo" id="photo" />';
$html .= '</div>';
// FORM SUBMIT
$html .= '<div class="formField">';
$html .= '<button>Mettre a jour</button>';
$html .= '</div>';
$html .= '</form>';

echo $html;
?>