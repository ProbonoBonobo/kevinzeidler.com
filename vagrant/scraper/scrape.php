<?php
$success = array();
$fail = array();
$in=file_get_contents("queries.json");
$queryobj=json_decode($in);
$KEYRING = array();
foreach ($queryobj as $daddy => $kids) {
    $KEYRING[$daddy] = array();
    foreach ($kids as $kid) {
        array_push($KEYRING[$daddy], $kid);
    }
}
var_dump($KEYRING);
$fp = fopen('results.json', 'w');
fwrite($fp, json_encode($KEYRING, JSON_FORCE_OBJECT));
fclose($fp);



$scraped = file_get_contents("./cached/new.html");


// instantiate the object model



if(!empty($scraped)) { //if any html is actually returned
    $DOM = new DOMDocument('1.0', 'UTF-8');
    $DOM->preserveWhiteSpace = true;
    $DOM->loadHTMLFile("./cached/yelp.html"); //reconstitute the DOM from html
    $DOM->formatOutput = true;
    $DOM->encoding = 'UTF-8';
    $htm = $DOM->saveXML();
    $scraped_xpath = new DOMXPath($DOM); //load the DOM into a convenient database-like representation
    $x = $scraped_xpath->query("//div[@class='review review--with-sidebar']//b[contains(.,',')]");


    $json = array();

    foreach ($KEYRING as $category => $value) {
        foreach ($KEYRING[$category] as $querystring) {
            $xpath_results = $scraped_xpath->query($querystring);
            $json[$querystring] = array();
            $reviewIndex = 0;
            if ($xpath_results->length == 20) {
                array_push($success, $category);
                foreach ($xpath_results as $res) {
                    $json[$reviewIndex][$category] = $xpath_results->item($reviewIndex)->nodeValue;
                    $reviewIndex++;
                }
                break;

            }
        }
    }
}


$json = json_encode($json);
$out = fopen('results.json', 'w');
fwrite($out, $json);
fclose($out);
?>