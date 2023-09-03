<?php

/**
 * Class  Page
 *
 * Permet de gérer la création d'une page HTML (du <body> au </body>
 *
 * Auteur : MOI
 * Date : 25-07-20232
 * Version : 1.0
 */

class Page_FO
{
    private $header = '';
    private $footer = '';
    private $corps = '';

    /** public function __construct($show_interface=true, $title='',$link=array())
     *
     * Description : Constructeur de la class, Initialise le header et footer de la page
     * Type        : Public
     * Parametre   : $show_interface : permet d'afficher ou non l'interface (Menu, Header, Footer de page...)
     *               $title : Titre de la page (afficher dans le Header)
     *               $link : Array() qui permettra d'afficher ou non des sous menu dans les page (dans le Header)
     * */
    public function __construct($show_interface = true, $title = '', $link = array())
    {
        if ($show_interface) {
            $this->build_header($title, $link);
            $this->build_footer();
        } else {
            $this->header = '<body>';
            $this->footer = '</body>';
        }
    }

    /** public function build_content($html='')
     *
     * Description : Permet d'ajouter le contenu de la page dans l'interface
     * Type        : Public
     * Parametre   : $html chaine de caractère contenu le code HTML de la page a afficher (le contenu)
     * */
    public function build_content($html = '')
    {
        $this->corps = $html;
    }

    /** public function show()
     *
     * Description : Permet d'afficher la poge à l'ecran
     * Type        : Public
     * Parametre   : null
     * */
    public function show()
    {
        echo $this->header;
        echo $this->corps;
        echo $this->footer;
    }

    /** public function build_header($title,$link)
     *
     * Description : Permet de préparer le header du corps de fichier (le logo, le menu etc )
     * Type        : Privée
     * Parametre   : $title => Titre afficher dans le Header de la page
     *               $link => Array() qui va contenir eventuellement un sous menu qu'on pourra afficher dans le Header
     * */
    private function build_header($title, $link){
        $dataBDD = new Data();
        $this->header = '<body>';
        $this->header .= '<header class="bo">
            <nav class="navTop">
                <div class="logo">
                    <p>TechStore</p>
                </div>
                <!-- Menu -->
                <div class="menu">
                    <ul>';
                    $sql = "SELECT m.*, m.id AS id_menu, ";
                    $sql.= " mt.libelle AS libelle ";
                    $sql.= " FROM t_menu m";
                    $sql.= " LEFT JOIN t_menu_trad mt ON mt.fk_menu=m.id AND mt.fk_langue=".$_SESSION[SESSION_NAME]['id_langue'];
                    $sql.= " WHERE fk_parent=0 ORDER BY ordre ASC";
                    $datas_menu = $dataBDD->getData($sql);
                    if($datas_menu){
                        foreach($datas_menu as $data_menu){
                            if($data_menu['url']){
                                $this->header .= '<li><a href="'.$data_menu['url'].'">'.$data_menu['libelle'].'</a></li>';
                            }
                        }
                    }
        $this->header .='<div class="groupBtn">
                    <div class="search">
                        <form action="index.php" method="post">
                            <div class="formField">
                                <input type="text" name="search" placeholder="Rechercher" />
                            </div>
                            <button><i class="bx bx-search"></i></button>
                        </form>
                    </div>
                    <div class="user">
                        <div class="cover">';
                        if (isset($_SESSION[SESSION_NAME]['avatar'])) {
                            if (!empty($_SESSION[SESSION_NAME]['avatar'])) {
                                $this->header .= '<img src="images/avatar/' . $_SESSION[SESSION_NAME]['avatar'] . '" alt="cover" />';
                            }
                        } else {
                            $this->header .= '<a href="index.php?page=login"><i class="bx bx-user"></i></a>';
                        }
                           $this-> header .= '<span class="account">';
                            if (isset($_SESSION[SESSION_NAME]['id_user'])) {
                                $this->header .= '<a href="index.php?page=fo_user&id='.$_SESSION[SESSION_NAME]['id_user'].'" target="_blank">
                                    <i class="bx bx-cog"></i>
                                </a>';
                            }
                            $this-> header .= '</span>
                        </div>
                    </div>
                    <button class="login">
                        <a href="index.php?page=logout">
                            <i class="bx bx-log-out"></i>
                        </a>
                    </button>
                </div>';
        $this->header .= '<div class="cartShop">
                            <div class="shopFrontOffice">
                                <button class="shop">
                                    <a href="index.php?page=fo_home"><i class="bx bx-store"></i></a>
                                </button>
                            </div>
                            <button class="cart">';
                                $nb_item = 0;
                                if (count($_SESSION[SESSION_NAME]['panier'])) {
                                    foreach ($_SESSION[SESSION_NAME]['panier'] as $data) {
                                        $nb_item += $data['qte'];
                                    }
                                    $this->header .= '               <span class="count"> ' . $nb_item . ' </span>';
                                }
                                $this->header .= '<a href="index.php?page=fo_panier"><i class="bx bx-cart"></i></a>
                            </button>
                        </div>
                </div>
            </nav>';
            // Si l'admin est connecté
            if(isset($_SESSION[SESSION_NAME]['id_user']) && $_SESSION[SESSION_NAME]['id_user'] == 1){
                $this->header .= '<div class="sideNav">
                <nav class="navLateral">
                    <div class="buttonGroupNav">
                        <button class="photo">
                            <a href="index.php?page=listing_photo"><i class="bx bx-image-alt"></i></a>
                        </button>
                        <button class="user">
                            <a href="index.php?page=listing_user"><i class="bx bx-user"></i></a>
                        </button>
                        <button class="country">
                            <a href="index.php?page=listing_pays"><i class="bx bx-globe"></i></a>
                        </button>
                        <button class="city">
                            <a href="index.php?page=listing_ville"><i class="bx bxs-city"></i></a>
                        </button>
                        <button class="menu">
                            <a href="index.php?page=listing_menu"><i class="bx bx-menu"></i></a>
                        </button>
                        <div class="shopDropdown">
                            <button class="shop">
                                <i class="bx bx-store"></i>
                            </button>
                            <div class="dropdownMenu">
                                <div class="close">
                                    <i class="bx bx-x"></i>
                                </div>
                                <!-- Produit -->
                                <a href="index.php?page=listing_produit">
                                <i class="bx bx-barcode"></i>
                                </a>
                                <!-- Commande -->
                                <a href="index.php?page=listing_commande">
                                <i class="bx bx-cart"></i>
                                </a>
                                <!-- Promotion -->
                                <a href="index.php?page=listing_promotion">
                                    <i class="fa-solid fa-percent"></i>
                                </a>
                                <!-- Rayon -->
                                <a href="index.php?page=listing_rayon">
                                    <i class="bx bxs-category"></i>
                                </a>
                                <!-- Stock -->
                                <a href="index.php?page=listing_stock">
                                    <i class="bx bx-box"></i>
                                </a>
                                <!-- Tva -->
                                <a href="index.php?page=listing_tva">
                                    <i class="bx bx-dollar"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>';
            }
        $this-> header .= '</header>';
    }

    /** public function build_footer()
     *
     * Description : Permet de préparer le footer du corps de fichier (copyright, reseaux sociaux, information contact... )
     * Type        : Privée
     * Parametre   : null
     * */
    private function build_footer()
    {
        $this->footer .= '</body>';
    }
}

?>