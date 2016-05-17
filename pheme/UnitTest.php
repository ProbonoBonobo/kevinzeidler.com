<?php

/**
 * Created by PhpStorm.
 * User: kevinzeidler
 * Date: 5/4/16
 * Time: 12:42 PM
 */
class UnitTest
{

    function UnitTest($fileToTest,$saveTo) {
        $this->fileToTest = $fileToTest;
        $this->outputURL = $saveTo;
        $this->content = fopen($saveTo, 'r');
        $this->
    }
}