<?php
$bdd = new Data();

// Suppression ToDo :) ?
if (isset($_GET['id_rayon']) && !empty($_GET['id_rayon'])) {
    // Suppression de l'utilisateur

    $id_rayon = $_GET['id_rayon'];
    $bdd->sql_delete('t_rayon', $id_rayon);

    // Redirection vers le listing des utilisateurs
    header("location: index.php?page=listing_rayon");
}

// Préparation de la requete
$sql = 'SELECT';
$sql .= ' r.id as id,';
$sql .= ' rt.nom as nom,';
$sql .= ' r.isActif as isActif';
$sql .= ' FROM t_rayon r';
$sql .= ' LEFT JOIN t_rayon_trad rt ON rt.fk_rayon = r.id';
$sql .= " AND rt.fk_langue = 1";

// Execution de la requete sur le serveur de BDD
$datas_rayon = $bdd->getData($sql);

// Préparation du retour
$html = '<div class="blurBG"></div>';
$html .= '<div class="manageUser">';
$html .= '<div class="wrapper">';
$html .= '<div class="close" onclick="closeForm();"><i class="bx bx-x"></i></div>';
$html .= '<h2>Ajouter un rayon</h2>';
// Formulaire d'ajout
$html .= '<form action="pages/admin/shop/rayon/manageRayon.php " method="POST" enctype="multipart/form-data">';
$html .= '<input type="hidden" name="id_rayon" value="0" />';
$html .= '<div class="formField">';
$html .= '<label for="nom_rayon">Nom Rayon</label>';
$html .= '<input type="text" name="nom_rayon" id="nom_rayon" placeholder="Nom du Rayon" />';
$html .= '</div>';
$html .= '<div class="formField">';
$html .= '<label for="isActif">Statut</label>';
$html .= '<select name="isActif" id="isActif">';
$html .= '<option>Choisir un statut</option>';
$html .= '<option value="1">Actif</option>';
$html .= '<option value="0">Inactif</option>';
$html .= '</select>';
$html .= '</div>';
// FORM SUBMIT
$html .= '<div class="formField">';
$html .= '<button>Ajouter</button>';
$html .= '</div>';
$html .= '</form>';
$html .= '</div>';
$html .= '</div>';
$html .= '<button class="btnAddUser"><i class="bx bx-plus"></i></button>';

$html .= '<div class="containerUsers">';
$html .= '<div class="listingUser">';
$html .= '<h1>Listing des rayons</h1>';
$html .= '<div class="tableContainer">';

// Etape 3 : Test du retour de la requete

if(empty($datas_rayon)){
    $html .= '<h1>Aucun rayon trouvé</h1>';
} else {
    $html .= '<table class="table">';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th>Id</th>';
    $html .= '<th>Nom du rayon</th>';
    $html .= '<th>Statut</th>';
    $html .= '<th>Action</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    foreach($datas_rayon as $rayon) {

        $html .= '<tbody>';
        $html .= '<tr>';
        $html .= '<td class="res-head">';
        $html .= '<span id=' . $rayon['id'] . '">' . $rayon['id'] . '</span>';
        $html .= '</td>';
        $html .= '<td class="res-head">';
        $html .= '<span id=' . $rayon['id'] . '">' . $rayon['nom'] . '</span>';
        $html .= '</td>';
        $html .= '<td class="res-head">';
        if($rayon['isActif'] == 1){
            $html .= '<span id=' . $rayon['id'] . '">Actif</span>';
        } else {
            $html .= '<span id=' . $rayon['id'] . '">Inactif</span>';
        }
        $html .= '<td class="action">';
        $html .= '<a href="#" onclick="rayonForm(' . $rayon['id'] . ');" class="btnEditUser"><i class="bx bx-edit"></i></a>';
        $html .= '<a href="index.php?page=listing_rayon&id_rayon=' . $rayon['id'] . '" onclick="return confirm(\'Etes vous sur de vouloir supprimer cet utilisateur ?\')"><i class="bx bx-trash"></i></a>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</tbody>';
    }
}

$html .= '</table>';
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';

$page = new Page(true);
$page->build_content($html);
$page->show();
