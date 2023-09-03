<?php 
    $bdd = new Data();
   
    // Création d'un système d'inscription

    if(isset($_POST) && !empty($_POST)){
        // Retour du formulaire
        // Recuperer les informations du formumlaire

        $login_user = $_POST['login_user'];
        $password_user = md5($_POST['password_user']);
        $nom_user = $_POST['nom_user'];
        $prenom_user = $_POST['prenom_user'];
        $email_user = $_POST['email_user'];

        // Gestion de l'avatar
        if (isset($_FILES) && !empty($_FILES) && !empty($_FILES['avatar']['name'])) {
            // Generation d'un nom unique
            $tab_name = explode('.', $_FILES['avatar']['name']);
            $unique_name = uniqid('img_') . '.' . $tab_name[1];

            // Préparation de l'upload
            $uploaddir = '../../../images/avatar/';
            $uploadfile = $uploaddir . $unique_name;
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadfile)) {
                $h['avatar'] = $unique_name;
            }

            // Vérifier si l'utilisateur existe déjà
            $sql = 'SELECT * FROM t_user WHERE login="'.$login_user.'" AND password="'.($password_user).'"';
            $rs = $bdd->query($sql);

            // Si l'utilisateur n'existe pas, on l'ajoute à la base de données
            if($rs && mysqli_num_rows($rs) == 0) {
                $sql = 'INSERT INTO t_user (nom, prenom, login, password, email, avatar) VALUES ("'.$nom_user.'", "'.$prenom_user.'", "'.$login_user.'", "'.$password_user.'", "'.$email_user.'", "'.$unique_name.'")';
                $rs = $bdd->query($sql);
                header('Location: index.php?page=fo_user');
            } else {
                $html = '<div class="login_info_error">Cet utilisateur existe déjà !</div>';
            }
        }
        // Redirection page d'accueil
        header('Location: index.php');
    }

    // Mise en page du formulaire d'inscription
    $html = '<div class="login-container">';
    $html .= '<div class="login-form">';
    $html .= '<div class="circle">';
    $html .= '<h1>Inscription</h1>';
    $html .= '<i class="bx bxs-user"></i>';
    $html .= '</div>';
    $html .= '<form action="index.php?page=signin" method="post" enctype="multipart/form-data">';

    // Nom
    $html .= '<div class="form-group">';
    $html .= '<label for="nom_user">Nom</label>';
    $html .= '<input type="text" name="nom_user" id="nom_user" placeholder="Nom">';
    $html .= '</div>';

    // Prénom
    $html .= '<div class="form-group">';
    $html .= '<label for="prenom_user">Prénom</label>';
    $html .= '<input type="text" name="prenom_user" id="prenom_user" placeholder="Prénom">';
    $html .= '</div>';

    // Email
    $html .= '<div class="form-group">';
    $html .= '<label for="email_user">Email</label>';
    $html .= '<input type="email" name="email_user" id="email_user" placeholder="Email">';
    $html .= '</div>';

    // Login
    $html .= '<div class="form-group">';
    $html .= '<label for="login_user">Login</label>';
    $html .= '<input type="text" name="login_user" id="login_user" placeholder="Nom d\'utilisateur">';
    $html .= '</div>';

    // Mot de passe
    $html .= '<div class="form-group">';
    $html .= '<label for="password_user">Mot de passe</label>';
    $html .= '<input type="password" name="password_user" id="password_user" placeholder="Mot de passe">';
    $html .= '</div>';

    // Avatar
    $html .= '<div class="form-group">';
    $html .= '<label for="avatar">Avatar</label>';
    $html .= '<input type="file" name="avatar" id="avatar" placeholder="Avatar">';
    $html .= '</div>';

    // Bouton d'inscription
    $html .= '<div class="form-group">';
    $html .= '<button>S\'inscrire</button>';
    $html .= '</div>';

    // Déja inscrit
    $html .= '<div class="existingAccount">';
    $html .= '<a href="index.php?page=login">Déja inscrit ?</a>';
    $html .= '</div>';

    // Fin du formulaire
    $html .= '</form>';

    $html .= '</div>';
    $html .= '</div>';

    $page = new Page_FO();
    $page->build_content($html);
    $page->show();
?>