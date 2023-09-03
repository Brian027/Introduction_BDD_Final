<?php
    $bdd = new Data();

    if(isset($_POST) && !empty($_POST)){
        // On revient d'un formulaire

        // Préparation des informations récuperées du formulaire
        $h = array();
        $h['adresse_1'] = $_POST['adresse_1'];
        $h['adresse_2'] = $_POST['adresse_2'];

        // Test pour savoir si on ajoute ou on modifie
        if($_POST['id_adresse'] > 0){
            // Update de BDD
            $id_adresse = $_POST['id_adresse'];

            // execution de la requete
            $bdd->sql_update('t_adresse',$id_adresse,$h);
        }else{
            // execution de la requete
            $id_adresse = $bdd->sql_insert('t_adresse',$h);
        }

        // Redirection
        header('Location: index.php?page=fo_user&id_user='.$id_user);
    }

    // Vérification pour Ajout / Modification
    if(isset($_GET['id_adresse']) && !empty($_GET['id_adresse'])){
        // Modification
        $id_adresse = $_GET['id_adresse'];
        $data = $bdd->build_r_from_id('t_adresse',$id_adresse);
    }else{
        // On est en creation
        $id_adresse = 0;
        $data = array();
        $data['adresse_1'] = '';
        $data['adresse_2'] = '';
    }

// Mise en forme du formulaire
$html = '<div class="manageUser">';
$html .= '<div class="wrapper">';

    // Gestion du Titre de la page (Modification ou Ajout)
    if($id_adresse > 0){
        $html .= '       <h2>Modifier l\'adresse</h2>';
    }else{
        $html .= '       <h2>Ajouter une adresse</h2>';
    }

    $sql = 'SELECT';
    $sql .= '  id,';
    $sql .= '  nom,';
    $sql .= '  prenom,';
    $sql .= '  email,';
    $sql .= '  login,';
    $sql .= '  password,';
    $sql .= '  avatar,';
    $sql .= '  fk_langue';
    $sql .= ' FROM t_user';
    $sql .= ' WHERE id = '.$_SESSION[SESSION_NAME]['id_user'].'';
    $data_user = $bdd->getData($sql);

    // Création de l'interface utilisateur

    $html =  '<div class="containerAccount">';
    $html .= '  <div class="sideBarAccount">';
    $html .= '      <div class="titleAccount">';
    $html .= '          <div class="avatarAccount">';
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
    $html .= '              <li><i class="bx bx-user"></i><a href="index.php?page=manage_user" class="active">Mes informations</a></li>';
    $html .= '              <li><i class="bx bx-map"></i><a href="index.php?page=manage_address">Mes adresses de livraison</a></li>';
    $html .= '              <li><i class="bx bx-cart"></i><a href="index.php?page=manage_order">Mes commandes</a></li>';
    $html .= '              <li><i class="bx bx-credit-card"></i><a href="index.php?page=manage_payment">Mes moyens de paiement</a></li>';
    $html .= '              <li><i class="bx bx-heart"></i><a href="index.php?page=manage_wishlist">Ma liste d\'envie</a></li>';
    $html .= '              <li><i class="bx bx-log-out"></i><a href="index.php?page=logout">Déconnexion</a></li>';
    $html .= '          </ul>';
    $html .= '      </div>';
    $html .= '  </div>';
    $html .= '  <div class="sectionAccount">';
    $html .= '      <div class="titleContentAccount">';
    $html .= '          <h2>Mes informations</h2>';
    $html .= '      </div>';
    $html .= '      <div class="contentAccount">';
    $html .= '          <div class="contentFormAccount">';
    $html .= '              <form action="index.php?page=fo_user" method="post" enctype="multipart/form-data">';
    $html .= '                  <input type="hidden" name="id_user" value="'.$_SESSION[SESSION_NAME]['id_user'].'">';
    $html .= '                  <div class="formAccount">';
    $html .= '                      <div class="formAccountLeft">';
    $html .= '                          <div class="formAccountGroup">';
    $html .= '                              <label for="nom_user">Nom</label>';
                                        foreach($data_user as $user) {
    $html .= '                              <input type="text" name="nom_user" id="nom_user" value="'.$user['nom'].'">';
                                        }
    $html .= '                          </div>';
    $html .= '                          <div class="formAccountGroup">';
    $html .= '                              <label for="prenom_user">Prénom</label>';
    $html .= '                              <input type="text" name="prenom_user" id="prenom_user" value="'.$user['prenom'].'">';
    $html .= '                          </div>';
    $html .= '                          <div class="formAccountGroup">';
    $html .= '                              <label for="email_user">Email</label>';
    $html .= '                              <input type="email" name="email_user" id="email_user" value="'.$user['email'].'">';
    $html .= '                          </div>';
    $html .= '                          <div class="formAccountGroup">';
    $html .= '                              <label for="login_user">Login</label>';
    $html .= '                              <input type="text" name="login_user" id="login_user" value="'.$user['login'].'" >';
    $html .= '                          </div>';
    $html .= '                          <div class="formAccountGroup">';
    $html .= '                              <label for="password_user">Mot de passe</label>';
    $html .= '                              <input type="password" name="password_user" id="password_user" >';
    $html .= '                          </div>';
    $html .= '                      </div>';
    $html .= '                      <div class="formAccountRight">';
    $html .= '                          <div class="imgAccount">';
    $html .= '                              <img src="images/avatar/'.$user['avatar'].'" alt="Avatar" class="avatar">';
    $html .= '                          </div>';
    $html .= '                          <div class="formAccountGroup">';
    $html .= '                              <label for="avatar_user">Avatar</label>';
    $html .= '                              <input type="file" name="avatar_user" id="avatar_user">';
    $html .= '                          </div>';
    $html .= '                          <div class="formAccountGroup">';
    $html .= '                              <label for="langue_user">Langue</label>';
    $html .= '                              <select name="langue_user" id="langue_user">';
    $html .= '                                  <option value="1">Français</option>';
    $html .= '                                  <option value="2">Anglais</option>';
    $html .= '                              </select>';
    $html .= '                          </div>';
    $html .= '                      </div>';
    $html .= '                  </div>';
    //Submit
    $html .= '                  <div class="formAccountGroup">';
    $html .= '                      <button type="submit" name="submit">Enregistrer</button>';
    $html .= '                  </div>';
    $html .= '              </form>';
    $html .= '          </div>';
    $html .= '      </div>';
    $html .= '  </div>';
    $html .= '</div>';

?>