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
Greetings! My name's <img src="/img/php_bear.png">Glenby</a> and I'm a violent, PHP-smoking bear. I am a Lead Engineer at Yahoo! where I am responsible for the CSS backend, as well as for the spate of deadly bear attacks that have claimed the lives of at least twelve Yahoo! employees this year.

<img src='img/php_bear.png'>
EOT;
    $page->setContent($content);

    $site->render();
?>