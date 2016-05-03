<?php
/**
 * Created by PhpStorm.
 * User: kevinzeidler
 * Date: 5/2/16
 * Time: 4:45 PM
 */

    include 'stdlib.php';

    $site = new csite();

    // this is a function specific to this site!
    initialise_site($site);

    $page = new cpage("Welcome to my site!");
    $site->setPage($page);

    $content = <<<EOT
Greetings! My name's Glenby and I'm a violent, PHP-smoking bear.
<img src='img/php_bear.png'>
EOT;
    $page->setContent($content);

    $site->render();
?>