<?php

function curl($url) {
    $ch = curl_init();  // Initialising cURL
    curl_setopt($ch, CURLOPT_URL, $url);    // Setting cURL's URL option with the $url variable passed into the function
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // Setting cURL's option to return the webpage data
    $data = curl_exec($ch); // Executing the cURL request and assigning the returned data to the $data variable
    curl_close($ch);    // Closing cURL
    print sprintf("%s", $data);
    return $data;   // Returning the data from the function
}

$cached = file_get_contents("http://www.yelp.com/biz/mobile-iphone-repair-ipad-screen-repair-san-diego-26");
echo $cached;
//$out = fopen('cached.html', 'w');
//fwrite($out, $cached);
//fclose($out);

?>