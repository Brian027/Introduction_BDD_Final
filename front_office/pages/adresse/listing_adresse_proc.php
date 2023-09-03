<?php 
$bdd = new Data();

// Suppression ToDo :) ?
if (isset($_GET['id_adresse']) && !empty($_GET['id_adresse'])) {
    // Suppression de l'utilisateur

    $id_adresse = $_GET['id_adresse'];
    $bdd->sql_delete('t_adresse', $id_adresse);

    // Redirection vers le listing des utilisateurs
    header("location: index.php?page=fo_listing_adresse");
}

// Préparation de la requete
$sql = 'SELECT';
$sql .= ' id,';
$sql .= ' adresse,';
$sql .= ' code_postal,';
$sql .= ' ville,';
$sql .= ' pays';
$sql .= ' FROM t_adresse;';

// Execution de la requete sur le serveur de BDD
$datas_adresse = $bdd->getData($sql);

// Préparation du retour
// Création de l'interface utilisateur

$html =  '<div class="containerAccount">';
$html .= '  <div class="sideBarAccount">';
$html .= '      <div class="titleAccount">';
$html .= '          <div class="avatarAccount">';
$sql = 'SELECT';
$sql .= '  id,';
$sql .= '  avatar';
$sql .= ' FROM t_user';
$sql .= ' WHERE id = '.$_SESSION[SESSION_NAME]['id_user'].'';
$data_user = $bdd->getData($sql);
foreach($data_user as $user) {
    $html .= '              <img src="images/avatar/'.$user['avatar'].'" alt="Avatar" class="avatar">';
}
$html .= '          </div>';
$html .= '          <div class="nameUser">';
$html .= '              <p>'.$_SESSION[SESSION_NAME]['nom_user'].'</p>';
$html .= '          </div>';
$html .= '      </div>';
$html .= '      <div class="menuAccount">';
$html .= '          <ul>';
$html .= '              <li><i class="bx bx-user"></i><a href="index.php?page=fo_user">Mes informations</a></li>';
$html .= '              <li><i class="bx bx-map"></i><a href="index.php?page=fo_listing_adresse">Adresses</a></li>';
$html .= '              <li><i class="bx bx-cart"></i><a href="index.php?page=manage_order">Mes commandes</a></li>';
$html .= '              <li><i class="bx bx-credit-card"></i><a href="index.php?page=manage_payment">Mes moyens de paiement</a></li>';
$html .= '              <li><i class="bx bx-heart"></i><a href="index.php?page=manage_wishlist">Ma liste d\'envie</a></li>';
$html .= '              <li><i class="bx bx-log-out"></i><a href="index.php?page=logout">Déconnexion</a></li>';
$html .= '          </ul>';
$html .= '      </div>';
$html .= '  </div>';
$html .= '  <div class="sectionAccount">';
$html .= '      <div class="titleContentAccount">';
$html .= '          <h2>Mes adresses</h2>';
$html .= '      </div>';
$html .= '      <div class="contentAccount">';
$html .= '          <div class="listingAddress">';
// Listing des adresses de l'utilisateur
$sql = 'SELECT';
$sql .= ' u.id,';
$sql .= ' u.adresse_1,';
$sql .= ' u.adresse_2,';
$sql .= ' u.cp,';
$sql .= ' v.nom as nom_ville,';
$sql .= ' p.nom as nom_pays';
$sql .= ' FROM t_user u';
$sql .= ' LEFT JOIN t_ville v ON v.id=u.fk_ville';
$sql .= ' LEFT JOIN t_pays p ON p.id=u.fk_pays';
$sql .= ' WHERE u.id = '.$_SESSION[SESSION_NAME]['id_user'].'';

$datas_address = $bdd->getData($sql);

if($datas_address){
    foreach($datas_address as $address) {
        $html .= '              <div class="address">';
        $html .= '                  <div class="addressContent">';
        $html .= '                      <div class="addressTitle">';
        $html .= '                          <h3><strong>Adresse 1</strong>:</h3>';
        $html .= '                      </div>';
        $html .= '                      <div class="addressTitle">';
        $html .= '                          <h3>'.$address['adresse_1'].'</h3>';
        $html .= '                      </div>';
        $html .= '                      <div class="addressInfo">';
        $html .= '                         <div class="cpVille">';
        $address['cp'] ? $html .= '                          <p>'.$address['cp'].', </p>' : $html .=' <p>Vous n\'avez pas défini de code postal</p>';
        $address['nom_ville'] ? $html .= '            <p>'.$address['nom_ville'].'</p> ' : $html .= ' <p>Vous n\'avez pas choisi de Ville</p>';
        $html .= '                          </div>';
        $address['nom_pays'] ? $html .= '           <p>'.$address['nom_pays'].'</p>' : $html .=' <p>Vous n\'avez pas choisi de Pays</p>';
        $html .= '                      </div>';
        $html .= '                  </div>';
        $html .= '                  <div class="addressActions">';
        $html .= '                      <a href="index.php?page=fo_manage_adresse&id_adresse='.$address['id'].'" class="edit"><i class="bx bx-edit"></i></a>';
        $html .= '                      <a href="index.php?page=fo_listing_adresse&id_adresse='.$address['id'].'" class="delete"><i class="bx bx-trash"></i></a>';
        $html .= '                  </div>';
        $html .= '              </div>';

        if($address['adresse_2']){
            $html .= '              <div class="address">';
            $html .= '                  <div class="addressContent">';
            $html .= '                      <div class="addressTitle">';
            $html .= '                          <h3><strong>Adresse 2</strong>:</h3>';
            $html .= '                      </div>';
            $html .= '                      <div class="addressTitle">';
            $html .= '                          <h3>'.$address['adresse_2'].'</h3>';
            $html .= '                      </div>';
            $html .= '                      <div class="addressInfo">';
            $html .= '                         <div class="cpVille">';
            $address['cp'] ? $html .= '                          <p>'.$address['cp'].', </p>' : $html .=' <p>Vous n\'avez pas défini de code postal</p>';
            $address['nom_ville'] ? $html .= '            <p>'.$address['nom_ville'].'</p> ' : $html .= ' <p>Vous n\'avez pas choisi de Ville</p>';
            $html .= '                          </div>';
            $address['nom_pays'] ? $html .= '           <p>'.$address['nom_pays'].'</p>' : $html .=' <p>Vous n\'avez pas choisi de Pays</p>';
            $html .= '                      </div>';
            $html .= '                  </div>';
            $html .= '                  <div class="addressActions">';
            $html .= '                      <a href="index.php?page=fo_manage_adresse" class="edit"><i class="bx bx-edit"></i></a>';
            $html .= '                      <a href="index.php?page=fo_listing_adresse&id_adresse='.$address['id'].'" class="delete"><i class="bx bx-trash"></i></a>';
            $html .= '                  </div>';
            $html .= '              </div>';
        } else {
            $html .= '              <div class="address">';
            $html .= '                  <div class="addressContent">';
            $html .= '                      <div class="addressTitle">';
            $html .= '                          <h3><strong>Adresse 2</strong>:</h3>';
            $html .= '                      </div>';
            $html .= '                      <div class="addressTitle">';
            $html .= '                          <h3 class="notAddress">Vous n\'avez pas encore défini d\'adresse !</h3>';
            $html .= '                      </div>';
            // Ajouter une deuxième adresse
            $html .= '                      <div class="addressInfo">';
            $html .= '                          <a href="index.php?page=fo_manage_adresse" class="addAddress">Ajouter une adresse</a>';
            $html .= '                      </div>';
            $html .= '                  </div>';
            $html .= '              </div>';
        }
    }
} else {
    $html .= '              <div class="address">';
    $html .= '                  <div class="addressContent">';
    $html .= '                      <div class="addressTitle">';
    $html .= '                          <h3><strong>Adresse 1</strong>:</h3>';
    $html .= '                      </div>';
    $html .= '                      <div class="addressTitle">';
    $html .= '                          <h3 class="notAddress">Vous n\'avez pas encore défini d\'adresse !</h3>';
    $html .= '                      </div>';
    // Ajouter une adresse
    $html .= '                      <div class="addressInfo">';
    $html .= '                          <a href="index.php?page=fo_manage_adresse" class="addAddress">Ajouter une adresse</a>';
    $html .= '                  </div>';
    $html .= '              </div>';
}

?>