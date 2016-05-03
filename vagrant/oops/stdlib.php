<?php
/**
 * Created by PhpStorm.
 * User: kevinzeidler
 * Date: 5/2/16
 * Time: 4:46 PM
 */

function __autoload($class) {
    include "$class.php";
}

function initialise_site(csite $site) {
    $site->addHeader("header.php");
    $site->addFooter("footer.php");
}
?>