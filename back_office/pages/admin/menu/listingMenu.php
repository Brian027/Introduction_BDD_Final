<?php 
    $bdd = new Data();

    // Suppression ToDo :) ?
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        // Suppression de l'utilisateur

        $id_menu = $_GET['id'];
        $bdd->sql_delete('t_menu', $id_menu);

        // Redirection vers le listing des utilisateurs
        header("location: index.php?page=listing_menu");
    }

    // Préparation de la requete
    $sql = "SELECT ";
    $sql .= " m.id AS id,";
    $sql .= " m.url AS url,";
    $sql .= " mt.libelle AS libelle,";
    $sql .= " (SELECT COUNT(id) FROM t_menu WHERE fk_parent = m.id) AS nb_sous_menu";
    $sql .= " FROM t_menu m";
    $sql .= " LEFT JOIN t_menu_trad mt ON mt.fk_menu = m.id";
    $sql .= " WHERE";
    $sql .= " m.fk_parent = 0";
    $sql .= " AND mt.fk_langue = 1";
    $sql .= " ORDER BY m.ordre ASC";

    // Compter le nombre de sous menu


    // Execution de la requete sur le serveur de BDD
    $datas_menu = $bdd->getData($sql);

    // Préparation du retour
    $html = '<div class="blurBG"></div>';
    $html .= '<div class="manageUser">';
    $html .= '<div class="wrapper">';
    $html .= '<div class="close" onclick="closeForm()"><i class="bx bx-x"></i></div>';
    $html .= '<h2>Ajouter un menu</h2>';
    $html .= '<form action="pages/admin/menu/manageMenu.php" method="POST" enctype="multipart/form-data">';
    $html .= '<div class="formField">';
    $html .= '<label for="url">Url</label>';
    $html .= '<input type="text" name="url" id="url" placeholder="Url"/>';
    $html .= '</div>';
    $html .= '<div class="formField">';
    $html .= '<label for="ordre">Ordre</label>';
    $html .= '<input type="text" name="ordre" id="ordre" placeholder="Ordre"/>';
    $html .= '</div>';
    $html .= '<div class="formField">';
    $html .= '<label for="libelle">Libelle</label>';
    $html .= '<input type="text" name="libelle" id="libelle" placeholder="Libelle"/>';
    $html .= '</div>';
    $html .= '<div class="formField">';
    $html .= '<label for="fk_parent">Parent</label>';
    $html .= '<select name="fk_parent" id="fk_parent">';
    $html .= '<option value="0">Aucun</option>';
    if(!empty($datas_menu)){
        foreach($datas_menu as $menu) {
            $html .= '<option value="'.$menu['id'].'">'.$menu['libelle'].'</option>';
        }
    }
    $html .= '</select>';
    $html .= '</div>';
    $html .= '<input type="hidden" name="id_menu" id="id_menu" value="0"/>';
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
    $html .= '<h1>Listing des Menu</h1>';
    $html .= '<div class="tableContainer">';
    
// Etape 3 : Test du retour de la requete

if(!empty($datas_menu)){
   $html .= '<table class="table">';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th>Id</th>';
    $html .= '<th>Url</th>';
    $html .= '<th>Libelle</th>';
    $html .= '<th>Sous menu</th>';
    $html .= '<th>Action</th>';
    $html .= '</tr>';
    $html .= '</thead>';

    foreach($datas_menu as $menu) {

        $html .= '<tbody>';
        $html .= '<tr>';
        $html .= '<td class="res-head">';
        $html .= '<span id=' . $menu['id'] . '">' . $menu['id'] . '</span>';
        $html .= '</td>';
        $html .= '<td class="res-head">';
        $html .= '<span id=' . $menu['id'] . '">' . $menu['url'] . '</span>';
        $html .= '</td>';
        $html .= '<td class="res-head">';
        $html .= '<span id=' . $menu['id'] . '">' . $menu['libelle'] . '</span>';
        $html .= '</td>';
        $html .= '<td class="res-head">';
        $html .= '<span id=' . $menu['id'] . '">' . $menu['nb_sous_menu'] . '</span>';
        $html .= '</td>';
        $html .= '<td class="action">';
        $html .= '<a href="#" onclick="menuForm('.$menu['id'].')"><i class="bx bx-edit"></i></a>';
        $html .= '<a href="pages/menu/manageMenu.php?id=' . $menu['id'] . '"><i class="bx bx-trash"></i></a>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</tbody>';
    }
} else {
    $html .= '<h3>Aucun menu</h3>';
}

$html .= '</table>';
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';

$page = new Page(true);
$page->build_content($html);
$page->show();
?>