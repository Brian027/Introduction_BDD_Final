<?php
    $bdd = new Data();

    // Suppression ToDo :) ?
    if (isset($_GET['delete_id']) && !empty($_GET['delete_id'])) {
        $id_statut = $_GET['delete_id'];

        // Suppression de la traduction
        $bdd->squery("DELETE FROM t_commande_statut_trad WHERE fk_statut_commande=".$id_statut);

        // Suppression du rayon
        $bdd->sql_delete('t_statut_commande',$id_statut);

        // Redirection vers le listing des rayon
        header("location: index.php?page=listing_statut_cmd");
    }

    // Etape 1 : Préparation de la requete
    $sql = 'SELECT';
    $sql.= '    s.id AS id_statut,';
    $sql.= '    st.nom AS nom,';
    $sql.= '    s.isActif AS isActif ';
    $sql.= ' FROM t_statut_commande s';
    $sql.= ' LEFT JOIN t_statut_commande_trad st ON st.fk_statut_commande = s.id';
    $sql.= ' WHERE';
    $sql.= '    st.fk_langue = '.$_SESSION[SESSION_NAME]['id_langue'];
    $sql.= ' ORDER BY id_statut ASC';

    // Etape 2 : Execution de la requete sur le serveur de BDD
    $datas_statut = $bdd->getData($sql);

    // Préparation du retour
    $html  = '   <div class="form-style">';
    $html .= '       <h1>Listing Statut<span>Listing des Statuts des Commandes...</span></h1>';

    // Lien Ajout utilisateur
    $html .= '   <div class="new_statut">';
    $html .= '       <a href="index.php?page=manage_statut_cmd">';
    $html .= '           <img src="images/interface/add.png" />';
    $html .= '       </a>';
    $html .= '   </div>';

    // Première ligne du tableau
    $html .= '        <table style="width:80%;margin:auto;padding:20px;" cellspacing="0" cellpadding="0">';
    $html .= '              <tr class="tab_header">';
    $html .= '                  <td class="tab_td">ID</td>';
    $html .= '                  <td class="tab_td">Nom</td>';
    $html .= '                  <td class="tab_td">Actif</td>';
    $html .= '                  <td class="tab_td" style="width:100px;">&nbsp;</td>';
    $html .= '              </tr>';

    // Si je suis ici => Tout va bien ! la requete est correcte et il y a au moins un enregistrement
    // Etape 3 : Je parcours les enregistrements de ma requete
    $i = 0;
    if($datas_statut) {
        foreach ($datas_statut as $data) {
            if ($i % 2)
                $html .= '       <tr class="tab_row_1">';
            else
                $html .= '       <tr class="tab_row_2">';
            $html .= '            <td class="tab_td">' . $data['id_statut'] . '</td>';
            $html .= '            <td class="tab_td">' . $data['nom'] . '</td>';
            if ($data['isActif'])
                $html .= '            <td class="tab_td"><img src="images/interface/actif.png" /></td>';
            else
                $html .= '            <td class="tab_td">&nbsp;</td>';

            // Actions
            $html .= '            <td class="tab_td">';
            $html .= '                <a href="index.php?page=manage_statut_cmd&id_statut=' . $data['id_statut'] . '"><img src="images/interface/edit.png"></a>';
            $html .= '                <a onclick="if(window.confirm(\'Etes vous sur ?\')){ return true; }else{ return false;}" href="index.php?page=listing_statut_cmd&delete_id=' . $data['id_statut'] . '" > <img src="images/interface/suppr.png"> </a>';
            $html .= '            </td>';
            $html .= '        </tr>';
            $i++;
        }
    }
    $html.= '        </table>';
    $html.= '   </div>';
?>