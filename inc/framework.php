<?php
     // Parametres
     require('inc/param.php');

     // Initialisation des Sessions ($_SESSION)
     session_name(SESSION_NAME);
     session_start();
 
     // Coeur du moteur PHP
     require('inc/func_common.php');
     require('inc/route.php');
     require('class/data.class.php');
     require('class/page.class.php');
     require('class/page_fo.class.php');
     require('class/stripe.payment.class.php');
?>