<?php

// Initialize a hashmap to store the XPATH queries we'll use later
$QUERY = array();
$QUERY['review'] = "//div[@class='review review--with-sidebar']";
$QUERY['name'] = "//div[@class='review review--with-sidebar']//a[@class='user-display-name']";
$QUERY['avatar'] = "//div[@class='review review--with-sidebar']//div[@class='media-avatar']//img/@src";
$QUERY['city'] = "//div[@class='review review--with-sidebar']//li[@class='user-location']/b";
$QUERY['rating'] = "//div[@class='review review--with-sidebar']//meta[@itemprop = 'ratingValue']/@content";
$QUERY['content'] = "//div[@class='review review--with-sidebar']//p[@itemprop='description']";
$QUERY['date'] = "//div[@class='review review--with-sidebar']//meta[@itemprop='datePublished']/@content";

$FILTER = array();
$FILTER['mode'] = "200";

ob_start(); // Start output buffering

// fancy css sprite implementation of a little picture of '5 stars' 
$STARIMG = "<img alt='5.0 star rating' class='offscreen' height='303' src='//s3-media4.fl.yelpcdn.com/assets/srv0/yelp_styleguide/c2252a4cd43e/assets/img/stars/stars_map.png' width='84'>";


function br2nl($buff = '') {
	$buff = preg_replace('#<br[/\s]*>#si', "&#60;br&#62;", $buff);
	$buff = trim($buff);

	return $buff;
}

// go get the raw HTML of the yelp page
$scraped = br2nl(file_get_contents("scraped.html"));
$DOM = new DOMDocument();



// Defining the basic cURL function
function curl($url) {
    $ch = curl_init();  // Initialising cURL
    curl_setopt($ch, CURLOPT_URL, $url);    // Setting cURL's URL option with the $url variable passed into the function
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // Setting cURL's option to return the webpage data
    $data = curl_exec($ch); // Executing the cURL request and assigning the returned data to the $data variable
    curl_close($ch);    // Closing cURL
    print sprintf("%s", $data);
    return $data;   // Returning the data from the function    
}



function analyze($rev) {
//	$r = var_dump($rev);
	$rating = substr($rev["stars"], 10, 3);
	$five_stars = "5.0";
	$newlines = substr_count( $rev["content"], "&#60;br&#62;" );
	$charsPerLine = 30;
	$reviewLength = strlen($rev["content"]);
	$weightedLength = $reviewLength + ($charsPerLine * $newlines);

	if($weightedLength > 407) {
	    return "413";
	} else if (strcmp($rating, $five_stars)) {
		return "417";
	} else {
		return "200";
	}
}

function rewriteImgSrc($orig) {
    // current format (60x60): http://s3-media4.fl.yelpcdn.com/photo/Z8c-DdGx8V-5N5Q_jbmC3w/60s.jpg
    // desired format (100x100): http://s3-media4.fl.yelpcdn.com/photo/Z8c-DdGx8V-5N5Q_jbmC3w/ms.jpg
	$path = substr($orig, -7, 3);
	return substr($orig, 0, -7) . "ms.jpg";
}


function estimateLength($rev) {
    $rating = substr($rev["stars"], 10, 3);
	$five_stars = "5.0";
	$newlines = substr_count( $rev["content"], "&#60;br&#62;" );
	$charsPerLine = 30;
	$reviewLength = strlen($rev["content"]);
	$weightedLength = $reviewLength + ($charsPerLine * $newlines);
	return $weightedLength;
}
//function explain_why_we_rejected($rev)
//{
//	$rating = substr($rev["stars"], 10, 3);
//	$five_stars = "5.0";
//	echo " Rejected ", $rev["name"], "'s review.";
//	if (strlen($rev["content"]) > 490) {
//		echo " Reason: Review too long.";
//	} else if (strcmp($rating, $five_stars)) {
//		echo " Reason: Cannot verify that it is a five-star review. (Star rating extracted: ", $rating, ")";
//		if ((!strcmp($rating, $five_stars)) & ($rating !== "4.0" ^ "3.0" ^ "2.0" ^ "1.0")) {
//			echo "*** ATTENTION: Scrape.php has encountered A Serious Error. *** \n";
//			echo "    	DETAILS: An invalid star rating has been extracted. \n";
//			echo "               This error is triggered when the review is \n";
//			echo "               something other than '4.0', '3.0', '2.0', \n";
//			echo "               or '1.0'. It probably means the structure \n";
//			echo "               of the page has changed. Check that the \n";
//			echo "               xPath 'rating' selector is valid. If it is \n";
//			echo "               valid, check that the img node has an 'alt' \n";
//			echo "               property.                                   \n";
//		}
//	}
//}

libxml_use_internal_errors(TRUE); //disable libxml errors

if(!empty($scraped)){ //if any html is actually returned

	$DOM->loadHTML($scraped);
	$scraped_xpath = new DOMXPath($DOM);  
	$scraped_row = $scraped_xpath->query("//div[@class='review review--with-sidebar']");
}


$review_list = array();

// We need to serialize the values of our data fields in a way that preserves order, so that the object's
// index maps uniquely to its identity

$review_obj = $scraped_xpath->query($GLOBALS['QUERY']['content']);
$names = $scraped_xpath->query($GLOBALS['QUERY']['name']);
$ratings = $scraped_xpath->query($GLOBALS['QUERY']['rating']);
$contents = $scraped_xpath->query($GLOBALS['QUERY']['content']);
$cities = $scraped_xpath->query($GLOBALS['QUERY']['city']);
$avatars = $scraped_xpath->query($GLOBALS['QUERY']['avatar']);
$dates = $scraped_xpath->query($GLOBALS['QUERY']['date']);






if($review_obj->length > 0) {
	$ctr = 0; // standard counter
	$filteredLength = 0; // only reviews that match the criteria
	$reviews = array();
	echo "[";
	// since index(obj) <=> identity(obj), the value of obj[key] is simply the current index of Arr[key]
	foreach ($review_obj as $pat) {

		$name = $names->item($ctr)->nodeValue;
		$rating = $ratings->item($ctr)->nodeValue;
		$city = $cities->item($ctr)->nodeValue;
		$content = $contents->item($ctr)->nodeValue;
		$date = $dates->item($ctr)->nodeValue;
		$avatar = $avatars->item($ctr)->nodeValue;

		//		//and push
		$review_list = array('date' => $date,
			'name' => $name,
			'city' => $city,
			'avatar' => $avatar,
			'rating' => $rating,
			'stars' => $GLOBALS['STARIMG'],
			'content' => $content);

		if (!strcmp(analyze($review_list), $GLOBALS['FILTER']['mode'])) {
		    if ($filteredLength > 0) {
		        echo ",";
		    }
			if (analyze($review_list) == $GLOBALS['FILTER']['mode']) {

				$row = array('id' => $filteredLength,
					'date' => $date,
					'name' => $name,
					'avatar' => rewriteImgSrc($avatar),
					'starsprite' => $GLOBALS['STARIMG'],
					'city' => $city,
					'rating' => $rating,
					'content' => $content,
					'responseCode' => analyze($review_list),
					'computedLength' => estimateLength($review_list));
				$filteredLength += 1;
				echo json_encode($row, true);
			}

		}
		$ctr += 1;


	}
	echo "]";

	//echo $variable;

}

$list = ob_get_contents(); // Store buffer in variable

ob_end_clean(); // End buffering and clean up

return json_decode($list,true); // will contain the contents





//	    echo "Json is ", $json, "\n\n\n";

//		//so just refocus data selectors on the current element of our data arrays
//		$name = $names->item($ctr)->nodeValue;
//		$rating = $ratings->item($ctr)->nodeValue;
//		$city = $cities->item($ctr)->nodeValue;
//		$content = $contents->item($ctr)->nodeValue;
//		$date = $dates->item($ctr)->nodeValue;
//		$avatar = $avatars->item($ctr)->nodeValue;
//
//

////        $json = json_encode(array(
//		if ($GLOBALS['FILTER']['mode'] == "none") {
//		    // catenate this review to previous review (if there is one)
//            		if ($filteredLength > 0) {
//            		    echo ",\n";
//                    }
//
//			//but actually what we really want is JSON, which we can simply echo to stdout
//			echo "\"[", $filteredLength, "\": {\n";
//			echo "      \"date\" => \"", $date, "\",\n";
//			echo "      \"name\" => \"", $name, "\",\n";
//			echo "      \"avatar\"  \"", $avatar, "\",\n";
//			echo "      \"starsprite\": \"", $GLOBALS['STARIMG'], "\",\n";
//			echo "      \"city\" : \"", $city, "\",\n";
//			echo "      \"rating\" : \"", $rating, "\",\n";
//			echo "      \"content\" : \"", $content, "\",\n";
//			echo "      \"responseCode\" : \"", analyze($review_list), "\"\n";
//			echo "   }]";
//			$filteredLength += 1;
//		}
//		if (!strcmp(analyze($review_list), $GLOBALS['FILTER']['mode'])) {
//		        // catenate this review to previous review (if there is one)
//			if ($filteredLength > 0) {
//				echo ",\n";
//				}

//		$this_review =
//		echo "This review is: ", json_encode($this_review);




			



?>