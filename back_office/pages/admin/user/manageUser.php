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
    $h['prenom'] = $_POST['prenom'];
    $h['adresse_1'] = $_POST['adresse_1'];
    $h['adresse_2'] = $_POST['adresse_2'];
    $h['login'] = $_POST['login'];
    $h['password'] = md5($_POST['password']);

    // Gestion de l'avatar
    if (isset($_FILES) && !empty($_FILES) && !empty($_FILES['avatar']['name'])) {
        // Generation d'un nom unique
        $tab_name = explode('.', $_FILES['avatar']['name']);
        $unique_name = uniqid('img_') . '.' . $tab_name[1];

        // Préparation de l'upload
        $uploaddir = '../../../images/avatar/';
        $uploadfile = $uploaddir . $unique_name;
        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadfile)) {
            $h['avatar'] = $unique_name;
        }
    }

    // Test pour savoir si on ajoute ou on modifie
    if ($_POST['id_user'] > 0) {
        // Update de BDD
        $id_user = $_POST['id_user'];

        // execution de la requete
        $bdd->sql_update('t_user', $id_user, $h);
    } else {
        // execution de la requete
        $id_user = $bdd->sql_insert('t_user', $h);
    }
    header("location: ../../../index.php?page=listing_user");
}

// Verification pour Ajout / Modification
if (isset($_GET['id_user']) && !empty($_GET['id_user'])) {
    // Modification
    $id_user = $_GET['id_user'];
    $data = $bdd->build_r_from_id('t_user', $id_user);
} else {
    // On est en creation
    $id_user = 0;
    $data = array();
    $data['nom'] = '';
    $data['prenom'] = '';
    $data['adresse_1'] = '';
    $data['adresse_2'] = '';
    $data['login'] = '';
    $data['avatar'] = '';
}

// Mise en forme du formulaire
$html = '<div class="blurBG"></div>';
$html .= '<div class="close" onclick="closeForm()"><i class="bx bx-x"></i></div>';
$html .= '<h2>Modifier ' . $data['prenom'] . '</h2>';
// Formulaire de modification
$html .= '<form action="pages/admin/user/manageUser.php" method="POST" enctype="multipart/form-data">';
$html .= '<input type="hidden" name="id_user" value="' . $id_user . '" />';
$html .= '<div class="containerForm">';
$html .= '<div class="left">';
$html .= '<div class="formField">';
$html .= '<label for="nom">Nom</label>';
$html .= '<input type="text" name="nom" id="nom" placeholder="Nom" value="' . $data['nom'] . '" />';
$html .= '</div>';
$html .= '<div class="formField">';
$html .= '<label for="prenom">Prénom</label>';
$html .= '<input type="text" name="prenom" id="prenom" placeholder="Prénom" value="' . $data['prenom'] . '" />';
$html .= '</div>';
$html .= '<div class="formField">';
$html .= '<label for="adresse_1">Adresse 1</label>';
$html .= '<input type="text" name="adresse_1" id="adresse_1" placeholder="Adresse 1" value="' . $data['adresse_1'] . '" />';
$html .= '</div>';
$html .= '</div>';
$html .= '<div class="right">';
$html .= '<div class="formField">';
$html .= '<label for="adresse_2">Adresse 2</label>';
$html .= '<input type="text" name="adresse_2" id="adresse_2" placeholder="Adresse 2" value="' . $data['adresse_2'] . '" />';
$html .= '</div>';
$html .= '<div class="formField">';
$html .= '<label for="login">Login</label>';
$html .= '<input type="text" name="login" id="login" placeholder="Login" value="' . $data['login'] . '" />';
$html .= '</div>';
$html .= '<div class="formField">';
$html .= '<label for="password">Password</label>';
$html .= '<input type="password" name="password" id="password" placeholder="Password" value="" />';
$html .= '</div>';
$html .= '<div class="formField">';
$html .= '<label for="image">Image</label>';
$html .= '<input type="file" name="avatar" id="image" value="' . $data['avatar'] . '" />';
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';
// FORM SUBMIT
$html .= '<div class="formField">';
$html .= '<button>Mettre a jour</button>';
$html .= '</div>';
$html .= '</form>';

echo $html;
?>