<?php 
    $bdd = new Data();
    if(isset($_POST) && !empty($_POST)) {
        // Retour du formulaire
        // Recuperer les informations du formumlaire
        $login_user = $_POST['form_user'];
        $password_user = md5($_POST['form_password']);

        // 1 requete SQL avec les informations
        // Requete Solution 2 : SELECT * FROM t_user WHERE login="" AND password="'.md5($_POST['form_password']).'"
        // Si tout se passe bien => Enregistrement en SESSION des informations
        $sql = 'SELECT * FROM t_user WHERE login="'.$login_user.'" AND password="'.($password_user).'"';
        
        $rs = $bdd->query($sql);

        if($rs && mysqli_num_rows($rs)) {
            $data = mysqli_fetch_assoc($rs);
            if(!empty($password_user) && $password_user == $data['password']) {
                // Enregistrement des informations en session
                $_SESSION[SESSION_NAME]['id_user'] = $data['id'];
                $_SESSION[SESSION_NAME]['nom_user'] = $data['prenom'].' '.$data['nom'];
                $_SESSION[SESSION_NAME]['avatar'] = $data['avatar'];
                $_SESSION[SESSION_NAME]['id_langue'] = $data['fk_langue'];
                $_SESSION[SESSION_NAME]['isAdmin'] = $data['isAdmin'];
                $_SESSION[SESSION_NAME]['panier'] = array();
                
                header('Location: index.php?page=home');
            } else {
                $html = '<div class="login_info_error">Mot de passe incorrect !</div>';
            }

        } else {
            $html = '<div class="login_info_error">Login Introuvable</div>';
        }

        // Redirection page d'accueil
        header('Location: index.php');
    }

    // Mise en page du formulaire de connexion
    $html = '<div class="login-container">';
    $html .= '<div class="login-form">';
    $html .= '<div class="circle">';
    $html .= '<h1>Connexion</h1>';
    $html .= '<i class="bx bxs-user"></i>';
    $html .= '</div>';
    $html .= '<form action="index.php?page=login" method="post">';

    // Login
    $html .= '<div class="form-group">';
    $html .= '<label for="form_user">Login</label>';
    $html .= '<input type="text" name="form_user" id="form_user" placeholder="Nom d\'utilisateur">';
    $html .= '</div>';

    // Mot de passe
    $html .= '<div class="form-group">';
    $html .= '<label for="form_password">Mot de passe</label>';
    $html .= '<input type="password" name="form_password" id="form_password" placeholder="Mot de passe">';
    $html .= '</div>';

    // Bouton de connexion
    $html .= '<div class="form-group">';
    $html .= '<button>Connexion</button>';
    $html .= '</div>';
    // Pas encore inscrit ?
    $html .= '<div class="nonExistentAccount">';
    $html .= '<p>Pas encore inscrit ? <a href="index.php?page=signin">S\'inscrire</a></p>';
    $html .= '</div>';
    $html .= '</form>';
    $html .= '</div>';
    $html .= '</div>';

    $page = new Page_FO();
    $page->build_content($html);
    $page->show();
?>