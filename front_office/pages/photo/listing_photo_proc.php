<?php
$bdd = new Data();

// Suppression ToDo :) ?
if (isset($_GET['id_photo']) && !empty($_GET['id_photo'])) {
    // Suppression de l'utilisateur

    $id_photo = $_GET['id_photo'];
    $bdd->sql_delete('t_photo', $id_photo);

    // Redirection vers le listing des utilisateurs
    header("location: index.php?page=listing_photo");
}

// Listing d'une table
// => Lister les images dans la table t_photo

// Etape 1 : Creation de la requete SQL
$sql = "SELECT ";
$sql .= "p.id, p.photographie as photo, p.ordre, p.fk_user, ";
$sql .= "pt.nom as titre, pt.description, ";
$sql .= "CONCAT(u.prenom, ' ' , u.nom) as utilisateur ";
$sql .= "FROM t_photo p ";
$sql .= "INNER JOIN t_photo_trad pt ON pt.fk_photo = p.id ";
$sql .= "INNER JOIN t_user u ON u.id = p.fk_user ";

// Vérification si retour d'un formulaire
if (isset($_POST['search']) && !empty($_POST['search'])) {
    // Si je suis ici => Il y a une recherche
    $sql .= " WHERE p.titre LIKE '%" . $_POST['search'] . "%'";
    $sql .= " OR p.description LIKE '%" . $_POST['search'] . "%'";
    $sql .= " OR CONCAT(u.prenom, ' ' , u.nom) LIKE '%" . $_POST['search'] . "%'";
}

// Etape 2 : Execution de la requete sur le serveur de BDD
$datas_photos = $bdd->getData($sql);

// Préparation du retour

$html = '<div class="container">';
$html .= '<div class="titlePage">';
$html .= '<h1>Listing des photos</h1>';
$html .= '</div>';
$html .= '<div class="gridContainer">';

// Etape 3 : Test du retour de la requete
// Si je suis ici => Tout va bien ! la requete est correcte et il y a au moins un enregistrement
// Etape 4 : Je parcours les enregistrements de ma requete
if($datas_photos){
    foreach ($datas_photos as $photo) {
        $html .= '<div class="gridItem">';
        $html .= '    <img src="images/' . $photo['photo'] . '" />';
        $html .= '    <div class="overlay"></div>';
        $html .= '    <div class="bodyContent">';
        $html .=     '    <div class="title"><h2>' . $photo['titre'] . '</h2></div>';
        $html .=     '    <div class="description"><p>' . $photo['description'] . '</p></div>';
        $html .=     '    <div class="auteur"><p>Par: <a>' . $photo['utilisateur'] . '</a></p></div>';
        $html .= '   </div>';
        $html .= '</div>';
    }
} else {
    $html .= '<div class="titleListingPhoto"> Aucune photo </div>';
}
// }
$html .= '</div>';
$html .= '</div>';

?>