<?php

/**
 * Created by PhpStorm.
 * User: kevinzeidler
 * Date: 5/2/16
 * Time: 4:44 PM
 */
class cpage
{
    private $title;
    private $content;

    public function __construct($title) {
        $this->title = $title;
    }

    public function __destruct() {
        // clean up here
    }

    public function render() {
//        echo "<H1>{$this->title}</H1>";
        echo $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
    }
}
?>
