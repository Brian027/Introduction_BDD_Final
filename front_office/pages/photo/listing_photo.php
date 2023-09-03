<?php
    $page = new Page_FO("Listing des photos", array("listing_photo.php", "listing_photo_proc.php"), true);
    $page->build_content($html);
    $page->show();
?>