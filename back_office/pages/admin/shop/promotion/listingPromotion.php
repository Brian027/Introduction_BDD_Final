<?php
$bdd = new Data();

// Suppression ToDo :) ?
if (isset($_GET['id_promotion']) && !empty($_GET['id_promotion'])) {
    // Suppression de l'utilisateur

    $id_promotion = $_GET['id_promotion'];
    $bdd->sql_delete('t_promotion', $id_promotion);

    // Redirection vers le listing des utilisateurs
    header("location: index.php?page=listing_promotion");
}

// Préparation de la requete
$sql = 'SELECT';
$sql .= ' p.id as id,';
$sql .= ' pt.nom as nom,';
$sql .= ' isActif,';
$sql .= ' reduction';
$sql .= ' FROM t_promotion p';
$sql .= ' LEFT JOIN t_promotion_trad pt ON pt.fk_promotion = p.id';
$sql .= " AND pt.fk_langue = 1";

// Execution de la requete sur le serveur de BDD
$datas_promotion = $bdd->getData($sql);

// Préparation du retour
$html = '<div class="blurBG"></div>';
$html .= '<div class="manageUser">';
$html .= '<div class="wrapper">';
$html .= '<div class="close" onclick="closeForm();"><i class="bx bx-x"></i></div>';
$html .= '<h2>Ajouter une promotion</h2>';
// Formulaire d'ajout
$html .= '<form action="pages/admin/shop/promotion/managePromotion.php " method="POST" enctype="multipart/form-data">';
$html .= '<input type="hidden" name="id_promotion" value="0" />';
$html .= '<div class="formField">';
$html .= '<label for="nom_promotion">Nom Promotion</label>';
$html .= '<input type="text" name="nom_promotion" id="nom_promotion" placeholder="Nom de la Promotion" />';
$html .= '</div>';
$html .= '<div class="formField">';
$html .= '<label for="reduction">Reduction(%)</label>';
$html .= '<input type="number" name="reduction" id="reduction" placeholder="Reduction" />';
$html .= '</div>';
$html .= '<div class="formField">';
$html .= '<label for="statut">Statut</label>';
$html .= '<select name="statut" id="statut">';
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
$html .= '<h1>Listing des promotions</h1>';
$html .= '<div class="tableContainer">';

// Etape 3 : Test du retour de la requete

if(empty($datas_promotion)){
    $html .= '<h1>Aucune promotion pour l\'instant !</h1>';
} else {
    $html .= '<table class="table">';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th>Id</th>';
    $html .= '<th>Code Promotion</th>';
    $html .= '<th>Reduction(%)</th>';
    $html .= '<th>Statut</th>';
    $html .= '<th>Action</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    foreach($datas_promotion as $promotion) {

        $html .= '<tbody>';
        $html .= '<tr>';
        $html .= '<td class="res-head">';
        $html .= '<span id=' . $promotion['id'] . '">' . $promotion['id'] . '</span>';
        $html .= '</td>';
        $html .= '<td class="res-head">';
        $html .= '<span id=' . $promotion['id'] . '">' . $promotion['nom'] . '</span>';
        $html .= '</td>';
        $html .= '<td class="res-head">';
        $html .= '<span id=' . $promotion['id'] . '">' . $promotion['reduction'] . '</span>';
        $html .= '</td>';
        $html .= '<td class="res-head">';
        if ($promotion['isActif'] == 1) {
            $html .= '<span id=' . $promotion['id'] . '">Actif</span>';
        } else {
            $html .= '<span id=' . $promotion['id'] . '">Inactif</span>';
        }
        $html .= '</td>';
        $html .= '<td class="action">';
        $html .= '<a href="#" onclick="promotionForm(' . $promotion['id'] . ');" class="btnEditUser"><i class="bx bx-edit"></i></a>';
        $html .= '<a href="index.php?page=listing_promotion&id_promotion=' . $promotion['id'] . '" onclick="return confirm(\'Etes vous sur de vouloir supprimer cet utilisateur ?\')"><i class="bx bx-trash"></i></a>';
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

?>