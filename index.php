<?php
$html_wrap = true;
require('inc/framework.php');

// Gestion de la session par defaut
if(!isset($_SESSION[SESSION_NAME])) {
    $_SESSION[SESSION_NAME]['id_langue'] = DEFAULT_ID_LANGUE;
    $_SESSION[SESSION_NAME]['panier'] = array();
}

// Gestion des routes !
if (isset($_GET['page']) && isset($route[$_GET['page']])) {
    // La page demandé existe => on va pouvoir l'afficher !
    $url_php = $route[$_GET['page']];
} else {
    // Forcer l'affichage de la page d'accueil du Front Office
    $url_php = $route['fo_home'];
}

// Gestion de la procedure
$url_php_proc = str_replace('.php','_proc.php',$url_php);
if(is_file($url_php_proc)){
    include $url_php_proc;
}
if($html_wrap){
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/back_office.css">
    <title>
        <?php
        $url_php_title = str_replace('.php', '_title.php', $url_php);
        if (is_file($url_php_title)) {
            include $url_php_title;
        } else {
            echo "Formation IFR | Accueil";
        }
        ?>
    </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <?php
    $url_php_head = str_replace('.php', '_head.php', $url_php);
    if (is_file($url_php_head)) {
        include $url_php_head;
    }
    ?>
</head>
<?php
        } // Fin du test if($html_wrap){
        include $url_php;
        if($html_wrap){
    ?>
    <script>

        const activePage = window.location.href;
        let url = "index.php?page=fo_user&id=";

        if(activePage.includes(url)){
            document.querySelector(".menuAccount ul li:first-child a").classList.add("active");
        }
        
        const navLinksUser = document.querySelectorAll(".menuAccount ul li a").forEach(link => {
            if(link.href.includes(`${activePage}`)){
                link.classList.add("active");
            }
        })
       
        // Navbar Sticky Scroll
        window.addEventListener("scroll", function() {
            var nav = document.querySelector("nav");
            nav.classList.toggle("sticky", window.scrollY > 50);
        })
        const dropdown = document.querySelector(".shopDropdown");
        const btnDropdown = document.querySelector(".shopDropdown .shop");
        const closeBtnDropdown = document.querySelector(".shopDropdown .close");
        const userBtn = document.querySelector("header.bo .navTop .groupBtn .user .cover");
        const userDropdown = document.querySelector("header.bo .navTop .groupBtn .user .cover .account");

        userBtn.addEventListener("click", () => {
            userDropdown.classList.toggle("active");
        })

        btnDropdown.addEventListener("click", () => {
            dropdown.classList.toggle("active");
        })
        closeBtnDropdown.addEventListener("click", () => {
            if (dropdown.classList.contains("active")) {
                dropdown.classList.remove("active");
            }
        })
    </script>
    <script src="ajax/ajaxForm.js"></script>

</html>
<?php
        } // Fin du test if($html_wrap){
?>