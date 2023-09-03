<?php
    $bdd = new Data();

    if(isset($_POST) && !empty($_POST)){
        // On revient d'un formulaire

        // Préparation des informations récuperées du formulaire
        $h = array();
        $h['nom'] = $_POST['nom_user'];
        $h['prenom'] = $_POST['prenom_user'];
        $h['email'] = $_POST['email_user'];
        $h['fk_langue'] = $_POST['langue_user'];
        $h['login'] = $_POST['login_user'];
        if(!empty($_POST['password_user']))
            $h['password'] = md5($_POST['password_user']);

        // Gestion de l'avatar
        if(isset($_FILES) && !empty($_FILES) && !empty($_FILES['avatar_user']['name'])){

            // Generation d'un nom unique
            $tab_name = explode('.',$_FILES['avatar_user']['name']);
            $unique_name = uniqid('img_').'.'.$tab_name[count($tab_name)-1];

            // Préparation de l'upload
            $uploaddir = 'images/avatar/';
            $uploadfile = $uploaddir . $unique_name;
            if (move_uploaded_file($_FILES['avatar_user']['tmp_name'], $uploadfile)) {
                $h['avatar'] = $unique_name;
            }
        }

        // Test pour savoir si on ajoute ou on modifie
        if($_POST['id_user'] > 0){
            // Update de BDD
            $id_user = $_POST['id_user'];
            $bdd->sql_update('t_user',$id_user, $h);
        }else{
            // Ajout en BDD
            $id_user = $bdd->sql_insert('t_user',$h);
        }

        // Redirection
        header('Location: index.php?page=fo_user&id_user='.$id_user);
    }

    // Vérification pour Ajout / Modification
    if (isset($_GET['id_user']) && !empty($_GET['id_user'])) {
        // Modification
        $id_user = $_GET['id_user'];
        $data_user = $bdd->build_r_from_id('t_user',$id_user);
    }else{
        // On est en Creation
        $id_user = 0;
        $data_user = array();
        $data_user['nom'] = '';
        $data_user['prenom'] = '';
        $data_user['login'] = '';
        $data_user['password'] = '';
        $data_user['avatar'] = '';
        $data_user['fk_langue'] = 0;
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
    $html .= '                              <input type="password" name="password_user" id="password_user"  autocomplete="off">';
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
    $sql = 'SELECT';
    $sql .= '  id,';
    $sql .= '  nom';
    $sql .= ' FROM t_langue';
    $data_langue = $bdd->getData($sql);
    foreach($data_langue as $langue) {
    $html .= '                                  <option value="'.$langue['id'].'">'.$langue['nom'].'</option>';
    }
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