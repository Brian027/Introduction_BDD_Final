<?php
$bdd = new Data();

// Suppression ToDo :) ?
if (isset($_GET['id_user']) && !empty($_GET['id_user'])) {
    // Suppression de l'utilisateur

    $id_user = $_GET['id_user'];
    $bdd->sql_delete('t_user', $id_user);

    // Redirection vers le listing des utilisateurs
    header("location: index.php?page=listing_user");
}

// Préparation de la requete
$sql = 'SELECT';
$sql .= ' id,';
$sql .= ' nom,';
$sql .= ' prenom,';
$sql .= ' adresse_1,';
$sql .= ' adresse_2,';
$sql .= ' login,';
$sql .= ' avatar';
$sql .= ' FROM t_user;';

// Execution de la requete sur le serveur de BDD
$datas_user = $bdd->getData($sql);

// Préparation du retour
$html = '<div class="blurBG"></div>';
$html .= '<div class="manageUser">';
$html .= '<div class="wrapper">';
$html .= '<div class="close" onclick="closeForm();"><i class="bx bx-x"></i></div>';
$html .= '<h2>Ajouter un utilisateur</h2>';

// Formulaire d'ajout
$html .= '<form action="pages/admin/user/manageUser.php" method="POST" enctype="multipart/form-data">';

// Champ caché
$html .= '<input type="hidden" name="id_user" value="0" />';

// Nom
$html .= '<div class="formField">';
$html .= '<label for="nom">Nom</label>';
$html .= '<input type="text" name="nom" id="nom" placeholder="Nom" />';
$html .= '</div>';

// Prenom
$html .= '<div class="formField">';
$html .= '<label for="prenom">Prénom</label>';
$html .= '<input type="text" name="prenom" id="prenom" placeholder="Prénom" />';
$html .= '</div>';

// Adresse 1
$html .= '<div class="formField">';
$html .= '<label for="adresse_1">Adresse 1</label>';
$html .= '<input type="text" name="adresse_1" id="adresse_1" placeholder="Adresse 1" />';
$html .= '</div>';

// Adresse 2
$html .= '<div class="formField">';
$html .= '<label for="adresse_2">Adresse 2</label>';
$html .= '<input type="text" name="adresse_2" id="adresse_2" placeholder="Adresse 2"/>';
$html .= '</div>';

// Login
$html .= '<div class="formField">';
$html .= '<label for="login">Login</label>';
$html .= '<input type="text" name="login" id="login" placeholder="Login" />';
$html .= '</div>';

// Image
$html .= '<div class="formField">';
$html .= '<label for="image">Image</label>';
$html .= '<input type="file" name="avatar" id="image"/>';
$html .= '</div>';
// FORM SUBMIT
$html .= '<div class="formField">';
$html .= '<button>Ajouter</button>';
$html .= '</div>';

// Fin du formulaire
$html .= '</form>';
$html .= '</div>';
$html .= '</div>';
$html .= '<button class="btnAddUser"><i class="bx bx-plus"></i></button>';
$html .= '<div class="containerUsers">';
$html .= '<div class="listingUser">';
$html .= '<h1>Listing des utilisateurs</h1>';
$html .= '<div class="tableContainer">';
$html .= '<table class="table">';
$html .= '<thead>';
$html .= '<tr>';
$html .= '<th>Id</th>';
$html .= '<th>Nom</th>';
$html .= '<th>Prenom</th>';
$html .= '<th>Adresse 1</th>';
$html .= '<th>Adresse 2</th>';
$html .= '<th>Login</th>';
$html .= '<th>Image</th>';
$html .= '<th>Action</th>';
$html .= '</tr>';
$html .= '</thead>';

// Etape 3 : Test du retour de la requete

foreach ($datas_user as $data) {

    $html .= '<tbody>';
    $html .= '<tr>';
    $html .= '<td class="res-head">';
    $html .= '<span id=' . $data['id'] . '">' . $data['id'] . '</span>';
    $html .= '</td>';
    $html .= '<td class="res-head">';
    $html .= '<span id=' . $data['id'] . '">' . $data['nom'] . '</span>';
    $html .= '</td>';
    $html .= '<td class="res-head">';
    $html .= '<span ' . $data['id'] . '">' . $data['prenom'] . '</span>';
    $html .= '</td>';
    $html .= '<td class="res-head">';
    $html .= '<span' . $data['id'] . '">' . $data['adresse_1'] . '</span>';
    $html .= '</td>';
    $html .= '<td class="res-head">';
    $html .= '<span' . $data['id'] . '">' . $data['adresse_2'] . '</span>';
    $html .= '</td>';
    $html .= '<td class="res-head">';
    $html .= '<span' . $data['id'] . '">' . $data['login'] . '</span>';
    $html .= '</td>';
    $html .= '<td class="res-head">';
    $html .= '<img src="images/avatar/' . $data['avatar'] . '" alt="avatar"class="avatar"/>';
    $html .= '</td>';
    $html .= '<td class="action">';
    $html .= '<a href="#" onclick="userForm(' . $data['id'] . ');" class="btnEditUser"><i class="bx bx-edit"></i></a>';
    $html .= '<a href="index.php?page=listing_user&id_user=' . $data['id'] . '" onclick="return confirm(\'Etes vous sur de vouloir supprimer cet utilisateur ?\')"><i class="bx bx-trash"></i></a>';
    $html .= '</td>';
    $html .= '</tr>';
    $html .= '</tbody>';
}

$html .= '</table>';
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';

$page = new Page(true);
$page->build_content($html);
$page->show();
?>