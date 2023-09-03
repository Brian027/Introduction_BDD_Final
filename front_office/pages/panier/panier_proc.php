<?php
   $bdd = new Data();
   require_once 'vendor/autoload.php';

   if(isset($_GET['del_id_produit']) && !empty($_GET['del_id_produit'])) {
      // L'utilisateur a voulu retier un produit au panier

      foreach ($_SESSION[SESSION_NAME]['panier'] as $key => $data_produit) {
         if($_SESSION[SESSION_NAME]['panier'][$key]['id_produit'] == $_GET['del_id_produit']) {
             unset($_SESSION[SESSION_NAME]['panier'][$key]);
         }
      }

      header('Location: index.php?page=fo_panier');
   }

   if(isset($_GET['update_panier']) && !empty($_GET['update_panier'])) {
      // Update du panier
      $id_produit = $_GET['id_produit'];
      $new_qte = $_GET['new_qte'];
      foreach ($_SESSION[SESSION_NAME]['panier'] as $key => $data_produit) {
         if($_SESSION[SESSION_NAME]['panier'][$key]['id_produit'] == $id_produit) {

            // On vérifie si la nouvelle qte est disponible
            $qte = $bdd->squery("SELECT SUM(qte) FROM t_produit_stock WHERE fk_produit=".$id_produit);
            if($qte >= $new_qte)
               $_SESSION[SESSION_NAME]['panier'][$key]['qte'] = $new_qte;
            else
               $_SESSION[SESSION_NAME]['panier'][$key]['qte'] = $qte;
         }
      }
      header('Location: index.php?page=fo_panier');
   }

   $info_paiement = '';
   if(isset($_POST) && !empty($_POST)) {

      // Vérifier si l'utilisateur est connecté
      if(!isset($_SESSION[SESSION_NAME]['id_user']) || empty($_SESSION[SESSION_NAME]['id_user'])) {
         $info_paiement = "Vous devez être connecté pour valider votre commande";
      } else {
      
      // Traitement du formulaire => On prepare la commande
      // Gestion table t_commande
      $h = array();
      $h['fk_user'] = $_SESSION[SESSION_NAME]['id_user'];
      $h['date_creation'] = time();
      $h['fk_statut'] = 1; // A modifier : A rendre plus intelligent : définir un statut par defaut pour les commandes

      $last_cmd = $bdd->squery("SELECT n_commande FROM t_commande ORDER BY id DESC LIMIT 1");
      if($last_cmd) {
         $next_cmd = intval(str_replace('CWEB_','',$last_cmd)) + 1;
         $h['n_commande'] = 'CWEB_'.str_pad($next_cmd, 7, '0', STR_PAD_LEFT);
      } else {
         // Premiere commande
         $h['n_commande'] = 'CWEB_0000001';
      }

      $id_commande = $bdd->sql_insert('t_commande',$h);

      // Gestion table t_commande_produit
      foreach ($_SESSION[SESSION_NAME]['panier'] as $key => $data_produit) {
         $h = array();
         $h['fk_commande'] = $id_commande;
         $h['fk_produit'] = $data_produit['id_produit'];
         $h['qte'] = $data_produit['qte'];

         // Gestion prixHT, tva, prixTTC, reduction
         $sql = "SELECT ";
         $sql.= " p.prixHT AS prixHT, ";
         $sql.= " t.value AS tva, ";
         $sql.= " pr.reduction AS reduction ";
         $sql.= " FROM t_produit p ";
         $sql.= " LEFT JOIN t_tva t ON t.id = p.fk_tva ";
         $sql.= " LEFT JOIN t_promotion pr ON pr.id = p.fk_promotion ";
         $sql.= " WHERE p.id=".$data_produit['id_produit'];

         $info_produit = $bdd->getData($sql);
         $info_produit = $info_produit[0];

         $h['tva'] = $info_produit['tva'];

         if($info_produit['reduction']>0) {
            $h['prixHT'] = $info_produit['prixHT'] - ($info_produit['prixHT'] * $info_produit['reduction'] / 100);
            $h['prixTTC'] = $h['prixHT'] + ($h['prixHT'] * $info_produit['tva'] / 100);
            $h['reduction'] = $info_produit['reduction'];
         } else {
            $h['prixHT'] = $info_produit['prixHT'];
            $h['prixTTC'] = $info_produit['prixHT'] + ($info_produit['prixHT'] * $info_produit['tva'] / 100);
            $h['reduction'] = 0;
         }
         $bdd->sql_insert('t_commande_produit',$h);
      }

      // On réinitialise le panier
      $_SESSION[SESSION_NAME]['panier'] = array();
      $info_paiement = "Votre Commande est passée. Nous faisons tout pour la traiter le plus rapidement possible.<br/><br/>";
      $info_paiement.= "Vous pouvez suivre l'avancement de votre commande dans la gestion de votre profil (historique des commandes)";
      }
   }
   
   $total_price_ht = 0;
   $total_price_ttc = 0;
   $total_promo = 0;



   $html = '<div class="wrap cf">
               <div class="heading cf">
                     <h1>Mon panier</h1>
                     <a href="index.php?page=fo_produit" class="continue">Retour boutique</a>
               </div>
               <div class="cart">
                  <!-- <ul class="tableHead">
                           <li class="prodHeader">Product</li>
                           <li>Quantité</li>
                           <li>Total</li>
                           <li>Supprimer</li>
                  </ul>-->';

   if($info_paiement) {
      $html.= '<div class="information_paiement">';
      $html .= $info_paiement;
      $html.= '</div>';
   }
   
   if(!empty($_SESSION[SESSION_NAME]['panier'])){

      $html .=       '<ul class="cartWrap">';

         $i = 0;
         foreach ($_SESSION[SESSION_NAME]['panier'] as $data_produit_cart) {
            $image = $bdd->squery("SELECT nom_fichier FROM t_produit_image WHERE fk_produit=".$data_produit_cart['id_produit']." LIMIT 1");
            $sql = "SELECT * FROM t_produit WHERE id=".$data_produit_cart['id_produit'];
            $data_produit = $bdd->getData($sql);
            $data_produit = $data_produit[0];

            $tva = $bdd->squery("SELECT value FROM t_tva WHERE id=".$data_produit['fk_tva']);
            $sql = "SELECT titre FROM t_produit_trad WHERE fk_produit=".$data_produit_cart['id_produit']." AND fk_langue=1";
            $nom = $bdd->squery($sql);

            if($data_produit['fk_promotion']) {
               $reduction = $bdd->squery("SELECT pr.reduction AS reduction FROM t_produit p LEFT JOIN t_promotion pr ON pr.id=p.fk_promotion WHERE p.id=".$data_produit_cart['id_produit']);
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

            $html .=       '<li class="items odd">
                              <div class="infoWrap">
                                 <div class="cartSection">
                                    <img src="images/produit/'.$image.'" alt="" class="itemImg" />
                                    <p class="itemNumber">'.$data_produit_cart['id_produit'].'</p>
                                    <h3>'.$nom.'</h3>
                                    
                                       <p><input type="text" class="qty" value="'.$data_produit_cart['qte'].'" id="produit_'.$data_produit_cart['id_produit'].'" attr="'.$data_produit_cart['id_produit'].'" /></p>
                                    
                                    <p class="stockStatus">';
                                       $sql = "SELECT SUM(qte) AS qte FROM t_produit_stock WHERE fk_produit=".$data_produit_cart['id_produit'];
                                       $qte = $bdd->squery($sql);
                                       if($qte > 0){
                                          $html .= '<span class="available">En stock</span>';
                                       }else{
                                          $html .= '<span class="outOfStock">En rupture</span>';
                                       }
            $html .=                '</p>
                                 </div>
                                 <div class="prodTotal cartSection">
                                    <p>'.number_format($prixTTC, 2, ',', ' ').' €</p>
                                 </div>
                                 <div class="cartSection removeWrap">
                                    <a onclick="if(window.confirm(\'Etes vous sur de retirer ce produit ?\')) return true; else return false;" href="index.php?page=fo_panier&del_id_produit='.$data_produit_cart['id_produit'].'" class="remove">
                                       <i class="bx bx-trash"></i>
                                    </a>
                                 </div>
                              </div>
                           </li>
                     </ul>';
         }

         $html .= '<div class="promoCode">
                        <label for="promo">Vous avez un code promo ?</label>
                        <input type="text" name="promo_code" placholder="Enter Code" />
                        <a href="#" class="btn"></a>
                     </div>
                     <div class="subtotal cf">
                        <ul>
                           <li class="totalRow">
                              <span class="label">Sous total</span>
                              <span class="value">
                                 ' . number_format($total_price_ht,2) . ' €
                              </span>
                           </li>
                           <li class="totalRow">
                              <span class="label">Taxe</span>
                              <span class="value">';
                                 // Total TVA
                                 $total_tva = $total_price_ttc - $total_price_ht;
                                 $html .= number_format($total_tva, 2) . ' €
                              </span>
                           </li>
                           <li class="totalRow final">
                              <span class="label">Total TTC</span>
                              <span class="value">' . number_format($total_price_ttc, 2) . '€</span>
                           </li>
                           <li class="totalRow">
                              <form method="post" action="index.php?page=fo_panier">
                                 <input type="submit" class="btn continue" value="Valider la commande" />
                                 <input type="hidden" name="valide_cart" id="valide_cart" value="1" />
                              </form>
                              <div class="info_promo_panier">Vous avez économisez ' . number_format($total_promo, 2) . ' € sur votre commande !</div>
                           </li>
                        </ul>
                     </div>';
                  
   } else {
      $html .= '<p>Votre panier est vide</p>';
   }
   $html .= '</div>';
   
//   dbug($_SESSION[SESSION_NAME]['panier']);
