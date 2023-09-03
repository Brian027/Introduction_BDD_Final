<?php
    $bdd = new Data();

    // Todo Listing des Commandes
    $sql = " SELECT ";
    $sql.= "    c.id AS id_commande, ";
    $sql.= "    c.n_commande AS n_cmd, ";
    $sql.= "    c.date_creation AS date_creation, ";
    $sql.= "    CONCAT_WS(' ', u.prenom, u.nom) AS client, ";
    $sql.= "    SUM( cp.qte * cp.prixTTC ) AS totalTTC, ";
    $sql.= "    sct.nom AS statut ";
    $sql.= " FROM t_commande c ";
    $sql.= " LEFT JOIN t_commande_produit cp ON cp.fk_commande=c.id ";
    $sql.= " LEFT JOIN t_user u ON u.id = c.fk_user ";
    $sql.= " LEFT JOIN t_statut_commande_trad sct ON sct.fk_statut_commande=c.fk_statut AND sct.fk_langue=".$_SESSION[SESSION_NAME]['id_langue']." ";
    $sql.= " WHERE c.fk_user=".$_SESSION[SESSION_NAME]['id_user'];
    $sql.= " GROUP BY c.id ";
    $sql.= " ORDER BY c.date_creation DESC ";

    $datas_commande = $bdd->getData($sql);

    // Préparation du retour
    $html  = '   <div class="form-style">';
    $html .= '       <h1>Listing de mes Commandes<span>Listing de mes Commandes sur la boutique en ligne...</span></h1>';

    // Première ligne du tableau
    $html .= '        <table style="width:80%;margin:auto;padding:20px;" cellspacing="0" cellpadding="0">';
    $html .= '              <tr class="tab_header">';
    $html .= '                  <td class="tab_td">N° Commande</td>';
    $html .= '                  <td class="tab_td">Client</td>';
    $html .= '                  <td class="tab_td">Date</td>';
    $html .= '                  <td class="tab_td">Montant TTC</td>';
    $html .= '                  <td class="tab_td">Statut</td>';
    $html .= '                  <td class="tab_td">&nbsp;</td>';
    $html .= '              </tr>';

    // Si je suis ici => Tout va bien ! la requete est correcte et il y a au moins un enregistrement
    // Etape 3 : Je parcours les enregistrements de ma requete
    $i = 0;
    if($datas_commande) {
        foreach ($datas_commande as $data_commande) {
            if ($i % 2)
                $html .= '       <tr class="tab_row_1">';
            else
                $html .= '       <tr class="tab_row_2">';
            $html .= '            <td class="tab_td">' . $data_commande['n_cmd'] . '</td>';
            $html .= '            <td class="tab_td">' . $data_commande['client'] . '</td>';
            $html .= '            <td class="tab_td">' . date('d/m/Y H:i',$data_commande['date_creation']) . '</td>';
            $html .= '            <td class="tab_td">' . number_format($data_commande['totalTTC'],2). ' €</td>';
            $html .= '            <td class="tab_td">' . $data_commande['statut'] . '</td>';
            $html .= '            <td class="tab_td">' ;
            $html .= '                <a href="index.php?page=fo_manage_commande&id_commande=' . $data_commande['id_commande'] . '"><img src="images/interface/edit.png"></a>';
            $html .= '            </td>';
            $html .= '        </tr>';
            $i++;
        }
    }
    $html.= '        </table>';
    $html.= '   </div>';
?>