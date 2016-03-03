#!/usr/bin/php
<?php
/**
 * Yelp API v2.0 code sample.
 *
 * This program demonstrates the capability of the Yelp API version 2.0
 * by using the Search API to query for businesses by a search term and location,
 * and the Business API to query additional information about the top result
 * from the search query.
 * 
 * Please refer to http://www.yelp.com/developers/documentation for the API documentation.
 * 
 * This program requires a PHP OAuth2 library, which is included in this branch and can be
 * found here:
 *      http://oauth.googlecode.com/svn/code/php/
 * 
 * Sample usage of the program:
 * `php sample.php --term="bars" --location="San Francisco, CA"`
 */
// Enter the path that the oauth library is in relation to the php file
require_once('lib/OAuth.php');
// Set your OAuth credentials here  
// These credentials can be obtained from the 'Manage API Access' page in the
// developers documentation (http://www.yelp.com/developers)
$CONSUMER_KEY = 'xRKOToTGhl9i8i3GuktiVA';
$CONSUMER_SECRET = 'f80Z4ZJ-pokVxMQIzNsyy6tLUxI';
$TOKEN = '6dNPdedN0pvbW79QOYUacYfdEK0Z9BN5';
$TOKEN_SECRET = 'qt9b6UWqPJqVsAe-Up3VXx9X1FU';
$API_HOST = 'api.yelp.com';
$DEFAULT_TERM = 'mobile iphone repair ipad screen repair';
$DEFAULT_LOCATION = 'San Diego, CA';
$SEARCH_LIMIT = 5;
$SEARCH_PATH = '/v2/search/';
$BUSINESS_PATH = '/v2/business/mobile-iphone-repair-ipad-screen-repair-san-diego-24';
/** 
 * Makes a request to the Yelp API and returns the response
 * 
 * @param    $host    The domain host of the API 
 * @param    $path    The path of the APi after the domain
 * @return   The JSON response from the request      
 */
function request($host, $path) {
    $unsigned_url = "https://" . $host . $path;
    // Token object built using the OAuth library
    $token = new OAuthToken($GLOBALS['TOKEN'], $GLOBALS['TOKEN_SECRET']);
    // Consumer object built using the OAuth library
    $consumer = new OAuthConsumer($GLOBALS['CONSUMER_KEY'], $GLOBALS['CONSUMER_SECRET']);
    // Yelp uses HMAC SHA1 encoding
    $signature_method = new OAuthSignatureMethod_HMAC_SHA1();
    $oauthrequest = OAuthRequest::from_consumer_and_token(
        $consumer, 
        $token, 
        'GET', 
        $unsigned_url
    );
    
    // Sign the request
    $oauthrequest->sign_request($signature_method, $consumer, $token);
    
    // Get the signed URL
    $signed_url = $oauthrequest->to_url();
    
    // Send Yelp API Call
    $ch = curl_init($signed_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $data = curl_exec($ch);
    curl_close($ch);
    
    return $data;
}
/**
 * Query the Search API by a search term and location 
 * 
 * @param    $term        The search term passed to the API 
 * @param    $location    The search location passed to the API 
 * @return   The JSON response from the request 
 */
function search($term, $location) {
    $url_params = array();
    
    $url_params['term'] = $term ?: $GLOBALS['DEFAULT_TERM'];
    $url_params['location'] = $location?: $GLOBALS['DEFAULT_LOCATION'];
    $url_params['limit'] = $GLOBALS['SEARCH_LIMIT'];
    $search_path = $GLOBALS['SEARCH_PATH'] . "?" . http_build_query($url_params);
    
    return request($GLOBALS['API_HOST'], $search_path);
}
/**
 * Query the Business API by business_id
 * 
 * @param    $business_id    The ID of the business to query
 * @return   The JSON response from the request 
 */
function get_business($business_id) {
    $business_path = $GLOBALS['BUSINESS_PATH'] . $business_id;
    
    return request($GLOBALS['API_HOST'], $business_path);
    // return request("https://api.yelp.com/v2/business/urban-curry-san-francisco?actionlinks=True");
}
/**
 * Queries the API by the input values from the user 
 * 
 * @param    $term        The search term to query
 * @param    $location    The location of the business to query
 */
function query_api($term, $location) {
    $raw_json = search($term, $location);
    $response = json_decode($raw_json);
    $business_id = $response->businesses[0]->id;

    if ($business_id == 'mobile-iphone-repair-ipad-screen-repair-san-diego-24') {
        // This is the success case. We're adding an error_code field to every response in order to make our JSON objects comparable as we can't make any assumptions
        // what fields a failure case contains. (It might have an id; it might not.) 
        $modified_json = json_decode($raw_json,true);
        $modified_json['response_code']=200;
        $modified_json['response_msg']="SUCCESS";
    } else {
        // Well dang. This means either there's something wrong with the response we got, or we didn't get a response. Possible causes for this
        // include:
        //
        //   1. Yelp is down
        //   2. Client's Yelp URL has changed
        //   3. Client has changed his business name on Yelp
        //   4. Yelp has decided the client is no longer the most relevant result for 'mobile iphone repair ipad screen repair' 
        //
        //   ** Note: 'mobile iphone repair ipad screen repair' is the client's actual Yelp business name (not just his keywords), 
        //      so this scenario is pretty unlikely in the event (3) hasn't happened
        //  
        //
        //  Best thing to do in this case? Probably alert the client via email something to the effect of "I'M BROKEN AHH!! FIX ME" I could
        //  configure the mailserver to alert someone in case of major errors like this. it'd take me a few hours. In the meantime, let's just 
        //  assign that to good ol' Apache error bin #418

        $modified_json = json_decode($raw_json,true);
        $modified_json['response_code']=418;
        $modified_json['response_msg']="I'M A TEAPOT";
    }
    // Finally, reserialize the JSON response to which we added those fields, and dump it to stdout
    print sprintf("%s", json_encode($modified_json));

    
}
/**
 * User input is handled here 
 */
$longopts  = array(
    "term::",
    "location::",
);
    
$options = getopt("", $longopts);
$term = $options['term'] ?: '';
$location = $options['location'] ?: '';
query_api($term, $location);
?>