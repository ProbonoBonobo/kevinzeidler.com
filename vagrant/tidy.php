<?php
/**
 * Created by PhpStorm.
 * User: kevinzeidler
 * Date: 3/16/16
 * Time: 3:10 AM
 */

function tidy_html($input_string, $format = 'html') {
    if ($format == 'xml') {
        $config = array(
            'input-xml' => true,
            'indent' => true,
            'wrap'           => 800
        );
    } else {
        $config = array(
            'output-html'   => true,
            'indent' => true,
            'wrap'           => 800
        );
    }
    // Detect if Tidy is in configured
    if( function_exists('tidy_get_release') ) {
        $tidy = new tidy;
        $tidy->parseString($input_string, $config, 'raw');
        $tidy->cleanRepair();
        $cleaned_html  = tidy_get_output($tidy);
    } else {
        # Tidy not configured for this computer
        $cleaned_html = $input_string;
    }
    return $cleaned_html;
};

$scraped = file_get_contents("http://www.yelp.com/biz/mobile-iphone-repair-ipad-screen-repair-san-diego-24");
$DOM = new DOMDocument();
echo tidy_html($scraped);

