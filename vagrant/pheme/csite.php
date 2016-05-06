<?php

/**
 * Created by PhpStorm.
 * User: kevinzeidler
 * Date: 5/2/16
 * Time: 4:43 PM
 */

    class csite {
        private $headers;
        private $footers;
        private $page;

        public function __construct() {
            $this->headers = array();
            $this->footers = array();
            $this->table = array();
        }

        public function __destruct() {
            // clean up here
        }

        public function render() {
            foreach($this->headers as $header) {
                include $header;
            }

            $this->page->render();

            foreach($this->footers as $footer) {
                include $footer;
            }
        }

        public function addHeader($file) {
            $this->headers[] = $file;
        }

        public function addFooter($file) {
            $this->footers[] = $file;
        }

        public function setPage(cpage $page) {
            $this->page = $page;
        }
    }

?>