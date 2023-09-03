<?php
require('../../../../inc/param.php');
require('../../../../class/data.class.php');
session_name(SESSION_NAME);
session_start();
$bdd = new Data();

// Todo Suppression des Images du produit
if(isset($_GET['delete_img']) && !empty($_GET['delete_img'])) {
    // On recupere le nom du fichier pour le supprimer du disque
    $file = $bdd->squery('SELECT nom_fichier FROM t_produit_image WHERE id=' . $_GET['delete_img']);

    // On supprime le fichier di disque
    @unlink('images/produit/' . $file);

    // On supprime l'entrée dans la base de données (table t_produit_image)
    $bdd->sql_delete('t_produit_image', $_GET['delete_img']);

    header('Location: index.php?page=manageProduit&id_produit=' . $_GET['id_produit']);
    exit();
}

// Traitement du formulaire
if (isset($_POST) && !empty($_POST)) {
    // On revient d'un formulaire

    // Préparation des informations récuperées du formulaire
    $h = array();
    $h['prixHT'] = $_POST['prixHT'];
    $h['poids'] = $_POST['poids'];
    $h['fk_tva'] = $_POST['tva'];
    $h['fk_promotion'] = $_POST['promotion'];
    $h['isActif'] = (isset($_POST['isActif'])?1:0);

    // Date debut promotion
    if(!empty($_POST['debut_promotion'])) {
        $tab_deb = explode('-', $_POST['debut_promotion']);
        $h['date_debut_promotion'] = mktime(0, 0, 0, $tab_deb[1], $tab_deb[2], $tab_deb[0]);
    } else {
        $h['date_debut_promotion'] = 0;
    }

    // Date fin promotion
    if(!empty($_POST['fin_promotion'])) {
        $tab_fin = explode('-', $_POST['fin_promotion']);
        $h['date_fin_promotion'] = mktime(0, 0, 0, $tab_fin[1], $tab_fin[2], $tab_fin[0]);
    } else {
        $h['date_fin_promotion'] = 0;
    }
    if ($_POST['id_produit'] > 0) {
        $isCreate = false;
        // Update de BDD
        $h['date_modification'] = time();
        $id_produit = $_POST['id_produit'];
        $bdd->sql_update('t_produit', $id_produit, $h);
    } else {
        $isCreate = true;
        // Ajout en BDD
        $h['date_creation'] = time();
        $h['date_modification'] = time();
        $h['fk_user'] = $_SESSION[SESSION_NAME]['id_user'];
        $id_produit = $bdd->sql_insert('t_produit', $h);
    }
    

    // Gestion des Images
    if(isset($_FILES) && !empty($_FILES)) {
        require('../../../../class/image.class.php');
        
        $file_array = $_FILES['image_product'];
        foreach($file_array['tmp_name'] as $key => $tmp_name) {
            
            // Génération d'un nom unique
            $tab_name = explode('.',$file_array['name'][$key]);
            $unique_name = uniqid('img_').'.'.$tab_name[count($tab_name)-1];

            // Préparation de l'upload
            $uploaddir = '../../../../images/produit/';
            $uploadfile = $uploaddir . $unique_name;

            if (move_uploaded_file($file_array['tmp_name'][$key], $uploadfile)) {

                // Enregistrement en BDD
                $h_img = array();
                $h_img['fk_produit'] = $id_produit;
                $h_img['nom_fichier'] = $unique_name;
                $bdd->sql_insert('t_produit_image',$h_img);
            }
        }  
    }

    // Gestion des traductions
    if($isCreate) {
        $h_trad = array();
        $h_trad['fk_produit'] = $id_produit;
        $h_trad['fk_langue'] = 1;
        $h_trad['titre'] = $_POST['nom_produit'];
        $h_trad['description_courte'] = $_POST['description_courte'];
        $h_trad['description_longue'] = $_POST['description_longue'];
        $bdd->sql_insert('t_produit_trad', $h_trad);
    } else {
        // Gestion des traductions
        $sql = "DELETE FROM t_produit_trad WHERE fk_produit = " . $id_produit;
        $bdd->query($sql);
    
        $sql = "SELECT * FROM t_langue";
        $datas_langue = $bdd->getData($sql);
        foreach ($datas_langue as $data_langue) {
            if (isset($_POST['nom_produit' . $data_langue['id']])) {
                $h_trad = array();
                $h_trad['fk_produit'] = $id_produit;
                $h_trad['fk_langue'] = $data_langue['id'];
                $h_trad['titre'] = $_POST['nom_produit' . $data_langue['id']];
                $h_trad['description_courte'] = $_POST['description_courte' . $data_langue['id']];
                $h_trad['description_longue'] = $_POST['description_longue' . $data_langue['id']];
                $bdd->sql_insert('t_produit_trad', $h_trad);
            }
        }
    }

    // Gestion des Stocks
    $bdd->query("DELETE FROM t_produit_stock WHERE fk_produit = ".$id_produit);
    $sql = "SELECT * FROM t_stock WHERE isActif = 1";
    $datas_stock = $bdd->getData($sql);
    foreach($datas_stock as $data_stock) {
        if(isset($_POST['stock_'.$data_stock['id']])){
            $h_stock = array();
            $h_stock['fk_produit'] = $id_produit;
            $h_stock['fk_stock'] = $data_stock['id'];
            $h_stock['qte'] = $_POST['stock_'.$data_stock['id']];
            $bdd->sql_insert('t_produit_stock',$h_stock);
        }
    }

    // Gestion des Rayons
    $bdd->query("DELETE FROM t_produit_rayon WHERE fk_produit = ".$id_produit);
    
    $h_rayon = array();
    $h_rayon['fk_produit'] = $id_produit;
    $h_rayon['fk_rayon'] = $_POST['rayon_'];
    $bdd->sql_insert('t_produit_rayon',$h_rayon);


    // Redirection
    header("location: ../../../../index.php?page=listing_produit");
}

// Verification pour Ajout / Modification
if (isset($_GET['id_produit']) && !empty($_GET['id_produit'])) {
    // Modification
    $id_produit = $_GET['id_produit'];
    $data_produit = $bdd->build_r_from_id('t_produit', $id_produit);
} else {
    // On est en creation
    $id_produit = 0;
    $data_produit = array();
    $data_produit['fk_tva'] = 0;
    $data_produit['fk_promotion'] = 0;
    $data_produit['fk_user'] = $_SESSION[SESSION_NAME]['id_user'];
    $data_produit['prixHT'] = 0;
    $data_produit['poids'] = 0;
    $data_produit['date_creation'] = time();
    $data_produit['date_modification'] = time();
    $data_produit['date_debut_promotion'] = '';
    $data_produit['date_fin_promotion'] = '';
    $data_produit['isActif'] = 1;
}

// FORMULAIRE DE MODIFICATION

// Mise en forme du formulaire
$html = '<div class="blurBG"></div>';
$html .= '<div class="close" onclick="closeForm()"><i class="bx bx-x"></i></div>';
$html .= '<h2>Modifier</h2>';
$html .= '<form action="pages/admin/shop/produit/manageProduit.php " method="POST" enctype="multipart/form-data">';

$html .= '<input type="hidden" name="id_produit" value="'.$id_produit.'" />';
$html .= '<div class="containerForm">';
$html .= '<div class="leftContent">';
// Information produit
$html .= '<div class="infoProduct">';
$html .= '<h3><span>Etape 1:</span> Information produit</h3>';
$html .= '<div class="listInfoProduct">';
$html .= '<ul>';
$html .= '<li>Date de création: <strong>'. date('d/m/Y') ." ". date('H:i:s', time() + 3600) .' </strong></li>';
$html .= '<li>Date de modification: <strong>'. date('d/m/Y') ." ". date('H:i:s', time() + 3600) .' </strong></li>';
$html .= '<li>Utilisateur: <strong>'. $_SESSION[SESSION_NAME]['nom_user'] .'</strong></li>';
$html .= '<li>';
$html .= '<label for="isActif">Actif</label>';
$html .= '<select name="isActif" id="isActif">';
$html .= '<option value="0">Non</option>';
$html .= '<option value="1">Oui</option>';
$html .= '</select>';
$html .= '</li>';
$html .= '</ul>';
$html .= '</div>';
$html .= '</div>';
$html .= '<div class="formField">';
$sql = "SELECT * FROM t_langue";
$datas_langue = $bdd->getData($sql);
foreach($datas_langue as $data_langue) {
    $sql = "SELECT * FROM t_produit_trad WHERE fk_produit = ".$id_produit." AND fk_langue = ".$data_langue['id'];
    $temp = $bdd->getData($sql);
    $temp ? $value = $temp[0]['titre'] : $value = '';
    $html .= '<label for="nom_produit'.$data_langue['id'].'">Nom ('.$data_langue['nom'].')</label>';
    $html .= '<input type="text" name="nom_produit'.$data_langue['id'].'" id="nom_produit'.$data_langue['id'].'" placeholder="Nom ('.$data_langue['nom'].')" value="'.$value.'" />';
}
$html .= '</div>';
$html .= '<div class="formField">';
foreach($datas_langue as $data_langue) {
    $sql = "SELECT * FROM t_produit_trad WHERE fk_produit = ".$id_produit." AND fk_langue = ".$data_langue['id'];
    $temp = $bdd->getData($sql);
    $temp ? $value = $temp[0]['description_courte'] : $value = '';
    $html .= '<label for="description_courte'.$data_langue['id'].'">Description Courte ('.$data_langue['nom'].')</label>';
    $html .= '<textarea name="description_courte'.$data_langue['id'].'" id="description_courte'.$data_langue['id'].'" placeholder="Description Courte ('.$data_langue['nom'].')">'.$value.'</textarea>';
}
$html .= '</div>';
$html .= '<div class="formField">';
foreach($datas_langue as $data_langue) {
    $sql = "SELECT * FROM t_produit_trad WHERE fk_produit = ".$id_produit." AND fk_langue = ".$data_langue['id'];
    $temp = $bdd->getData($sql);
    $temp ? $value = $temp[0]['description_longue'] : $value = '';
    $html .= '<label for="description_longue'.$data_langue['id'].'">Description Longue ('.$data_langue['nom'].')</label>';
    $html .= '<textarea name="description_longue'.$data_langue['id'].'" id="description_longue'.$data_langue['id'].'" placeholder="Description Longue ('.$data_langue['nom'].')">'.$value.'</textarea>';
}
$html .= '</div>';
$html .= '</div>';
$html .= '<div class="rightContent">';
$html .= '<div class="formGroupField">';
$html .= '<div class="formField">';
$html .= '<h3><span>Etape 2:</span> Gestion du Tarif, TVA et Promotion</h3>';
$html .= '<label for="prixHT">Prix HT</label>';
$html .= '<input type="text" name="prixHT" id="prixHT" placeholder="Prix HT" value="'.$data_produit['prixHT'].'" />';
$html .= '</div>';
$html .= '<div class="formField">';
$html .= '<label for="tva">TVA</label>';
$html .= '<select name="tva" id="tva">';
$sql = "SELECT * FROM t_tva";
$datas_tva = $bdd->getData($sql);
foreach($datas_tva as $data_tva) {
    $html .= '<option value="'.$data_tva['id'].'">'.$data_tva['nom_tva'].' ('.$data_tva['value'].'%)</option>';
}
$html .= '</select>';
$html .= '</div>';


// PROMOTION
$html .= '<div class="formField">';

$sql = "SELECT * FROM t_promotion_trad WHERE fk_promotion = ".$data_produit['fk_promotion']." AND fk_langue = 1";
$datas_promotion = $bdd->getData($sql);
$html .= '<label for="promotion">Promotion</label>';
$sql = "SELECT p.id as id, pt.nom as nom";
$sql .= " FROM t_promotion p";
$sql .= " LEFT JOIN t_promotion_trad pt ON pt.fk_promotion = p.id";
$sql .= " WHERE p.isActif = 1";
$sql .= " AND pt.fk_langue = 1;";
$datas_promotion = $bdd->getData($sql);
$html .= '<select name="promotion" id="promotion">';
if($datas_promotion){
    foreach ($datas_promotion as $promotion) {
        $html .= '<option value="' . $promotion['id'] . '">' . $promotion['nom'] . '</option>';
    }
}
$html .= '</select>';
$html .= '</div>';
$html .= '<div class="formField">';
$html .= '<label for="debut_promotion">Début Promotion</label>';
$html .= '<input type="date" name="debut_promotion" id="debut_promotion" placeholder="Début Promotion" value="'.date('d-m-Y',$data_produit['date_debut_promotion']).'"/>';
$html .= '</div>';
$html .= '<div class="formField">';
$html .= '<label for="fin_promotion">Fin Promotion</label>';
$html .= '<input type="date" name="fin_promotion" id="fin_promotion" placeholder="Fin Promotion" value="'.date('d-m-Y',$data_produit['date_fin_promotion']).'" />';
$html .= '</div>';
$html .= '</div>';
$html .= '<div class="formField">';
$html .= '<h3><span>Etape 3:</span> Gestion du poids et du stock</h3>';
$html .= '<label for="poids">Poids</label>';
$sql = "SELECT * FROM t_produit";
$sql .= " WHERE id = ".$id_produit;
$data_produit = $bdd->getData($sql)[0];
$html .= '<input type="number" name="poids" id="poids" placeholder="Poids" step=".01" value="'.$data_produit['poids'].'" />';
$html .= '</div>';

$html .= '<div class="formField">';
$html .= '<h4>Stock</h4>';
$sql = "SELECT * FROM t_stock WHERE isActif=1 ORDER by nom ASC";
$datas_stock = $bdd->getData($sql);
if($datas_stock){
    foreach($datas_stock as $data_stock) {
        $qte_stock = $bdd->squery('SELECT qte FROM t_produit_stock WHERE fk_produit = '.$id_produit.' AND fk_stock = '.$data_stock['id']);
        $html .= '<label for="stock_'.$data_stock['id'].'">'.$data_stock['nom'].'</label>';
        $html .= '<input type="number" name="stock_'.$data_stock['id'].'" id="stock_'.$data_stock['id'].'" placeholder="Stock" value="'.$qte_stock.'" />';
    }
}
$html .= '</div>';
$html .= '<div class="formField">';
$html .= '<h3><span>Etape 4:</span> Gestion des rayons</h3>';
$html .= '<label for="rayon_">Rayon</label>';
// SELECT RAYON
$sql = "SELECT ";
$sql.= " r.id AS id_rayon, ";
$sql.= " rt.nom AS nom";
$sql.= " FROM t_rayon r ";
$sql.= " LEFT JOIN t_rayon_trad rt ON rt.fk_rayon=r.id";
$sql.= " WHERE ";
$sql.= " r.isActif=1 ";
$sql.= " AND rt.fk_langue=".$_SESSION[SESSION_NAME]['id_langue'];
$sql.= " ORDER by nom ASC ";
$html .= '<select name="rayon_" id="rayon_">';
$datas_rayon = $bdd->getData($sql);
if($datas_rayon){
    foreach($datas_rayon as $data_rayon) {
        // $getRayon = $bdd->squery('SELECT 1 FROM t_produit_rayon WHERE fk_produit = '.$id_produit.' AND fk_rayon = '.$data_rayon['id_rayon']);
        $html .= '<option value="'.$data_rayon['id_rayon'].'">'.$data_rayon['nom'].'</option>';
    }
}
$html .= '</select>';
$html .= '</div>';
// IMAGE

$html .= '<div class="formField">';
$html .= '<h3><span>Etape 5:</span> Gestion des images</h3>';
$html .= '<label for="image_product">Image</label>';
$sql = "SELECT * FROM t_produit_image";
$sql .= " WHERE fk_produit = ".$id_produit;
$datas_image_produit = $bdd->getData($sql);
$html .= '<input type="file" name="image_product[]" id="image" placeholder="Image" multiple />';
$html .= '<div class="imageProduct">';
if(!empty($datas_image_produit)){
    foreach ($datas_image_produit as $data_image_produit) {
        $html .= '<div class="imageProductItem">';
        $html .= '<img src="images/produit/'.$data_image_produit['nom_fichier'].'" alt="" />';
        $html .= '<div class="imageProductItemAction">';
        $html .= '<a onclick="deleteImg(' . $id_produit . ','. $data_image_produit['id'] .  ')" href="#" class="delete"><i class="fa fa-trash"></i></a>';
        $html .= '</div>';
        $html .= '</div>';
    }
} else {
    $html .= '<p>Aucune image</p>';
}
$html .= '</div>';
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