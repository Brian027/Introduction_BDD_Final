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
    $sql.= " LEFT JOIN t_statut_commande_trad sct ON sct.fk_statut_commande=c.fk_statut AND sct.fk_langue=1 ";
    $sql.= " GROUP BY c.id ";
    $sql.= " ORDER BY c.date_creation DESC ";

    $datas_commande = $bdd->getData($sql);

    // Préparation du retour
    $html = '<div class="containerUsers">';
    $html .= '<div class="listingUser">';
    $html .= '<button class="btnAddUser"><i class="bx bx-plus"></i></button>';
    $html .= '<h1>Listing des commandes</h1>';
    $html .= '<div class="tableContainer">';
    $html .= '<table class="table">';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th>N° commande</th>';
    $html .= '<th>Client</th>';
    $html .= '<th>Date</th>';
    $html .= '<th>Montant TTC</th>';
    $html .= '<th>Status</th>';
    $html .= '<th>Action</th>';
    $html .= '</tr>';
    $html .= '</thead>';

    // Si je suis ici => Tout va bien ! la requete est correcte et il y a au moins un enregistrement
    // Etape 3 : Je parcours les enregistrements de ma requete
    if($datas_commande) {
        foreach ($datas_commande as $data_commande) {
            $html .= '<tbody>';
            $html .= '<tr>';
            $html .= '<td class="res-head">';
            $html .= '<span id=' . $data_commande['id_commande'] . '">' . $data_commande['n_cmd'] . '</span>';
            $html .= '</td>';
            $html .= '<td class="res-head">';
            $html .= '<span id=' . $data_commande['id_commande'] . '">' . $data_commande['client'] . '</span>';
            $html .= '</td>';
            $html .= '<td class="res-head">';
            $html .= '<span id=' . $data_commande['id_commande'] . '">' . date('d/m/Y H:i',$data_commande['date_creation']) . '</span>';
            $html .= '</td>';
            $html .= '<td class="res-head">';
            $html .= '<span id=' . $data_commande['id_commande'] . '">' . number_format($data_commande['totalTTC'],2). ' €</span>';
            $html .= '</td>';
            $html .= '<td class="res-head">';
            $html .= '<span id=' . $data_commande['id_commande'] . '">' . $data_commande['statut'] . '</span>';
            $html .= '</td>';
            $html .= '<td class="action">';
            $html .= '<a href="index.php?page=manage_commande&id_commande=' . $data_commande['id_commande'] . '"><i class="bx bx-edit"></i></a>';
            $html .= '</td>';
            $html .= '</tr>';
            $html .= '</tbody>';
        }
    }
    $html .= '</table>';
    $html .= '</div>';
?>