<?php
   $bdd = new Data();

   if(isset($_POST) && !empty($_POST)) {
      // Update statut Commande
      $id_commande = $_POST['id_commande'];

      // Statut en cours
      $sql = "SELECT ";
      $sql.= " CONCAT_WS('#',c.fk_statut, IFNULL(sc.isDefault, 0) ,IFNULL(sc.isStock, 0) ,IFNULL(sc.isBlock, 0) ,IFNULL(sc.isDefault, 0) ) AS info_statut ";
      $sql.= " FROM t_commande c ";
      $sql.= " LEFT JOIN t_statut_commande sc ON sc.id=c.fk_statut ";
      $sql.= " WHERE c.id=".$id_commande;
      $tab_old_statut = explode('#',$bdd->query($sql)); // 0->id, 1->isDefault, 2->isStock, 3->isBlock

      // Futur statut
      $new_statut = $_POST['id_statut'];
      $sql = "SELECT ";
      $sql.= " CONCAT_WS('#',sc.id, IFNULL(sc.isDefault, 0) ,IFNULL(sc.isStock, 0) ,IFNULL(sc.isBlock, 0) ,IFNULL(sc.isDefault, 0) ) AS info_statut ";
      $sql.= " FROM t_statut_commande sc ";
      $sql.= " WHERE sc.id=".$new_statut;
      $tab_new_statut = explode('#',$bdd->query($sql)); // 0->id, 1->isDefault, 2->isStock, 3->isBlock


      if($tab_old_statut[3]) {
         // Statut bloquant => Modif impossible
         header('Location: index.php?page=manage_commande&id_commande=' . $id_commande);
         exit();
      }

      if($tab_old_statut[0] == $new_statut) {
         // Statut non modifié... Rien a Faire
         header('Location: index.php?page=manage_commande&id_commande='.$id_commande);
         exit();
      }

      if($tab_new_statut[2]) {
         // Gestion du Stock
         $sql = " SELECT ";
         $sql.= " fk_produit AS id_produit,";
         $sql.= " qte AS qte";
         $sql.= " FROM t_commande_produit ";
         $sql.= " WHERE fk_commande=".$id_commande;

         $datas_produit = $bdd->getData($sql);

         if($datas_produit) {
            foreach ($datas_produit as $data_produit) {
               // On recupere l'information sur le stock
               $datas_stock = $bdd->getData("SELECT * FROM t_produit_stock WHERE fk_produit=".$data_produit['id_produit']);

               if($datas_stock) {
                  foreach ($datas_stock as $data_stock) {
                     if($data_produit['qte'] <= $data_stock['qte']) {
                        $h = array();
                        $h['qte'] = $data_stock['qte'] - $data_produit['qte'];
                        $bdd->sql_update('t_produit_stock', $data_stock['id'], $h);
                        break;
                     } else {
                        $data_produit['qte'] -= $data_stock['qte'];
                        $h = array();
                        $h['qte'] = 0;
                        $bdd->sql_update('t_produit_stock', $data_stock['id'], $h);
                     }
                  }
               }
            }
         }
      }


      $h = array();
      $h['fk_statut'] = $new_statut;
      $bdd->sql_update('t_commande',$id_commande, $h);

      header('Location: index.php?page=manage_commande&id_commande='.$id_commande);
      exit();
   }

   if(isset($_GET['id_commande']) && !empty($_GET['id_commande'])) {
      $id_commande = $_GET['id_commande'];
   } else {
      // Pas d'ID => Soucis
      header('Location: index.php?page=listing_commande');
      exit();
   }

   $sql = "SELECT ";
   $sql.= " cp.qte AS qte, ";
   $sql.= " cp.fk_produit AS id_produit, ";
   $sql.= " c.fk_statut AS id_statut, ";
   $sql.= " c.n_commande AS n_commande ";
   $sql.= " FROM t_commande c ";
   $sql.= " LEFT JOIN t_commande_produit cp ON cp.fk_commande=c.id ";
   $sql.= " WHERE c.id=".$id_commande;
   $datas_commande = $bdd->getData($sql);

   $n_commande = $datas_commande[0]['n_commande'];
   $statut_commande = $datas_commande[0]['id_statut'];

   $total_price_ht = 0;
   $total_price_ttc = 0;
   $total_promo = 0;

   // Préparation du retour
   $html = '<div class="containerUsers">';
   $html .= '<div class="manageCommande">';
   $html .= '<div class="row">';
   // Gestion du titre
   $html .= '<h1 class="cmdTitle">Gestion de la commande n° '.$n_commande.'</h1>';

   // Gestion du Statut
   $html.= '<div class="containerStatus">';
   $html.= '    <form method="POST" action="index.php?page=manage_commande" enctype="multipart/form-data">';
   $html.= '        <div class="formField">';
   $html.= '               <label for="id_statut">Statut de la commande</label>';
   $html.= '               <div class="inputGroup">';
   $html.= '               <select name="id_statut" id="id_statut">';

   //Requete pour récupérer les status
   $sql = "SELECT sc.id AS id_statut, sct.nom AS label ";
      $sql.= " FROM t_statut_commande sc ";
      $sql.= " LEFT JOIN t_statut_commande_trad sct ON sct.fk_statut_commande=sc.id AND sct.fk_langue=".$_SESSION[SESSION_NAME]['id_langue'];
      $sql.= " ORDER BY id_statut ASC ";

   // Récupération des données
   $datas_statut = $bdd->getData($sql);

   // Parcours des données
   if($datas_statut) {
      foreach ($datas_statut as $data_statut) {
         $html.= '<option value="'.$data_statut['id_statut'].'" '.($data_statut['id_statut']==$statut_commande?'selected':'').'>'.$data_statut['label'].'</option>';
      }
   }
   $html .= '            </select>';
   $html .= '        <input type="hidden" name="id_commande" value="'.$id_commande.'" />';
   $html .= '        <button class="submit"><i class="bx bx-save"></i></button>';
   $html .= '        </div>';
   $html .= '    </div>';
   $html .= '    </form>';
   $html .= '</div>';
   $html .= '</div>';
   $html .= '<div class="tableContainer">';
   $html .= '<table class="table">';
   $html .= '<thead>';
   $html .= '<tr>';
   $html .= '<th>Produit</th>';
   $html .= '<th>Prix HT</th>';
   $html .= '<th>Prix TTC</th>';
   $html .= '<th>Quantité</th>';
   $html .= '<th>Sous total</th>';
   $html .= '</tr>';
   $html .= '</thead>';

   $i = 0;
   if($datas_commande){
      foreach ($datas_commande as $data_produit_cart) {
         $image = $bdd->squery("SELECT nom_fichier FROM t_produit_image WHERE fk_produit=" . $data_produit_cart['id_produit'] . " LIMIT 1");
         $sql = "SELECT * FROM t_produit WHERE id=" . $data_produit_cart['id_produit'];
         $data_produit = $bdd->getData($sql);
         $data_produit = $data_produit[0];

         $tva = $bdd->squery("SELECT value FROM t_tva WHERE id=" . $data_produit['fk_tva']);
         $sql = "SELECT titre FROM t_produit_trad WHERE fk_produit=" . $data_produit_cart['id_produit'] . " AND fk_langue=1";
         $nom = $bdd->squery($sql);

         if ($data_produit['fk_promotion']) {
            $reduction = $bdd->squery("SELECT pr.reduction AS reduction FROM t_produit p LEFT JOIN t_promotion pr ON pr.id=p.fk_promotion WHERE p.id=" . $data_produit_cart['id_produit']);
            $total_promo += (($data_produit['prixHT'] * $reduction / 100) * $data_produit_cart['qte']);
            $prixHT = $data_produit['prixHT'] - ($data_produit['prixHT'] * $reduction / 100);
            $prixTTC = $prixHT + ($prixHT * $tva / 100);
         } else {
            $prixHT = $data_produit['prixHT'];
            $prixTTC = $data_produit['prixHT'] + ($prixHT * $tva / 100);
         }

         // Gestion Total (prix)
         $total_price_ht += $prixHT * $data_produit_cart['qte'];
         $total_price_ttc += $prixTTC * $data_produit_cart['qte'];

         $html .= '<tbody>';
         $html .= '<tr>';
         $html .= '<td class="res-head">';
         $html .= '<img src="images/produit/' . $image . '" style="width:64px; height: 64px; margin: 10px; vertical-align: middle;" />&nbsp;&nbsp;';
         $html .= '<span id=' . $data_produit_cart['id_produit'] . '">' . $nom . '</span>';
         if ($data_produit['fk_promotion']) {
            ///$reduction
            $html .= '<span class="zone_information_panier_product_price_promo">&nbsp;<strong>(Promo -' . ceil($reduction) . ' %)</strong></span>';
         }
         $html .= '</td>';
         $html .= '<td class="res-head">';
         $html .= '<span id=' . $data_produit_cart['id_produit'] . '">' . number_format($prixHT, 2) . ' €</span>';
         $html .= '</td>';
         $html .= '<td class="res-head">';
         $html .= '<span id=' . $data_produit_cart['id_produit'] . '">' . number_format($prixTTC, 2) . ' €</span>';
         $html .= '</td>';
         $html .= '<td class="res-head">';
         $html .= '<span id=' . $data_produit_cart['id_produit'] . '">' . $data_produit_cart['qte'] . '</span>';
         $html .= '</td>';
         $html .= '<td class="res-head">';
         $html .= '<span id=' . $data_produit_cart['id_produit'] . '">' . number_format(($prixTTC * $data_produit_cart['qte']), 2) . ' €</span>';
         $html .= '</td>';
         $html .= '</tr>';
         $html .= '</tbody>';
      }

      // Gestion tableau Total
      $html .= '<tfoot>';
      $html .= '<tr>';
      $html .= '<td colspan="4" class="res-head">';
      $html .= '<span id="total">Total HT</span>';
      $html .= '</td>';
      $html .= '<td class="res-head">';
      $html .= '<span id="total">' . number_format($total_price_ht, 2) . ' €</span>';
      $html .= '</td>';
      $html .= '</tr>';
      $html .= '<tr>';
      $html .= '<td colspan="4" class="res-head">';
      $html .= '<span id="total">Total TTC</span>';
      $html .= '</td>';
      $html .= '<td class="res-head">';
      $html .= '<span id="total">' . number_format($total_price_ttc, 2) . ' €</span>';
      $html .= '</td>';
      $html .= '</tr>';
      $html .= '<tr>';
      $html .= '<td colspan="4" class="res-head">';
      $html .= '<span id="total">Total a payer</span>';
      $html .= '</td>';
      $html .= '<td class="res-head">';
      $html .= '<span id="total">' . number_format($total_price_ttc, 2) . ' €</span>';
      $html .= '</td>';
      $html .= '</tr>';
      $html .= '<tr>';
      $html .= '<td colspan="5" class="res-head">';
      $html .= '<span> Le client a économisé '.number_format($total_promo, 2).' sur sa commande !</span>';
      $html .= '</td>';
      $html .= '</tr>';
      $html .= '</tfoot>';

      $html .= '</table>';


      $html .= '</div>';

   }
?>
