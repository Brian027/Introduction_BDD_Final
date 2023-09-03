<?php
    $bdd = new Data();

    if(isset($_POST) && !empty($_POST)){
        // On revient d'un formulaire

        // Préparation des informations récuperées du formulaire
        $h = array();
        $h['isActif'] = (isset($_POST['form_isActif'])?1:0);

        // Test pour savoir si on ajoute ou on modifie
        if($_POST['id_statut'] > 0){
            // Update de BDD
            $id_statut = $_POST['id_statut'];
            $bdd->sql_update('t_statut_commande',$id_statut, $h);
        }else{
            // Ajout en BDD
            $id_statut = $bdd->sql_insert('t_statut_commande',$h);
        }

        // Gestion Traduction
        $sql = "DELETE FROM t_statut_commande_trad WHERE fk_statut_commande=".$id_statut;
        $bdd->query($sql);

        $sql = "SELECT * FROM t_langue";
        $datas_langue = $bdd->getData($sql);
        foreach($datas_langue as $data_langue) {
            $h = array();
            $h['fk_statut_commande'] = $id_statut;
            $h['fk_langue'] = $data_langue['id'];
            $h['nom'] = $_POST['form_nom_'.$data_langue['id']];
            $bdd->sql_insert('t_statut_commande_trad',$h);
        }

        // Redirection
        header('Location: index.php?page=manage_statut_cmd&id_statut='.$id_statut);
    }

    // Vérification pour Ajout / Modification
    if (isset($_GET['id_statut']) && !empty($_GET['id_statut'])) {
        // Modification
        $id_statut = $_GET['id_statut'];
        $data_statut = $bdd->build_r_from_id('t_statut_commande',$id_statut);
    }else{
        // On est en Creation
        $id_statut = 0;
        $data_statut = array();
        $data_statut['isActif'] = 1;
    }

    // Mise en forme du formulaire
    $html = '   <div class="form-style">';

    // Gestion du Titre de la page (Modification ou Ajout)
    if($id_statut > 0){
        $html .= '       <h1>Modification Statut<span>Modifier un statut de commande...</span></h1>';
    }else{
        $html .= '       <h1>Ajout Statut<span>Ajouter un statut de commande...</span></h1>';
    }

    // Debut du Formulaire HTML
    $html.= '       <form method="POST" action="index.php?page=manage_statut_cmd" enctype="multipart/form-data">';

    // url et libellé
    $html.= '           <div class="section"><span>1</span>Information Statut</div>';
    $html.= '           <div class="inner-wrap-l">';
    $sql = "SELECT * FROM t_langue";
    $datas_langue = $bdd->getData($sql);
    foreach($datas_langue as $data_langue) {
        $sql = "SELECT nom FROM t_statut_commande_trad WHERE fk_statut_commande=".$id_statut." AND fk_langue=".$data_langue['id'];
        $trad = $bdd->squery($sql);
        $html.= '               <label>Nom du Statut ('.$data_langue['nom'].') <input type="text" name="form_nom_'.$data_langue['id'].'" value="'.$trad.'"/></label>';
    }


    $html.= '           </div>';
    $html.= '           <div class="inner-wrap-r">';
    if($data_statut['isActif'])
        $html .= '               <label>Actif ? <input type="checkbox"  name="form_isActif" value="1" checked="checked"/></label>';
    else
        $html .= '               <label>Actif ? <input type="checkbox"  name="form_isActif" value="1" /></label>';

    $html.= '           </div>';
    $html.= '           <div style="clear:both;"></div>';

    // Bouton Enregistrer
    $html.= '           <div class="button-section">';
    $html.= '               <input type="submit" value="Enregistrer" />';
    $html.= '           </div>';

    // Champs caché...
    $html.= '           <input type="hidden" name="id_statut" id="id_statut" value="'.$id_statut.'" />';

    $html.= '       </form>';
    $html.= '   </div>';
?>