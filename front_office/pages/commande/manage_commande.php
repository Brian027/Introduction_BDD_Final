<?php
    $page = new Page(true, 'Commande : '.$n_commande);
    $page->build_content($html);
    $page->show();
?>