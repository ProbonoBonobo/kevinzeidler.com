<?php

$variable = "\";";

$response =  include "scrape.php";
$jsonResponse = json_encode($response, true);
//$jsonArr = json_encode($jsonResponse, true);


echo $jsonResponse;



//echo var_dump($jsonArr);





?>
