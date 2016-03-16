<?php

// Initialize a hashmap to store the XPATH queries we'll use later
$QUERY = array();
$QUERY['review'] = "//div[@class='review review--with-sidebar']";
$QUERY['name'] = "//div[@class='review review--with-sidebar']//a[@class='user-display-name']";
$QUERY['avatar'] = "//div[@class='review review--with-sidebar']//div[@class='media-avatar']//img/@src";
$QUERY['city'] = "//div[@class='review review--with-sidebar']//li[@class='user-location']/b";
//$QUERY['rating'] = "//div[@class='review review--with-sidebar']//meta[@itemprop = 'ratingValue']/@content";
$QUERY['rating'] = "//div[@class='rating-very-large']/i/img/@alt";
$QUERY['content'] = "//div[@class='review review--with-sidebar']//p[@itemprop='description']";
$QUERY['date'] = "//span[@class='rating-qualifier']";

// fancy css sprite implementation of a little picture of '5 stars' 
$STARIMG = "<img alt='5.0 star rating' class='offscreen' height='303' src='//s3-media4.fl.yelpcdn.com/assets/srv0/yelp_styleguide/c2252a4cd43e/assets/img/stars/stars_map.png' width='84'>";

// go get the raw HTML of the yelp page
$scraped = file_get_contents("scraped.txt");
function br2nl($buff='') {
//	echo "Before: " . $buff;
	$buff = preg_replace('#<br[/\s]*>#si', "\n", $buff);
	$buff = trim($buff);
//	echo "After: " . $buff;
	return $buff;
}

function unescape($buff='') {
//	echo "Before: " . $buff;
	$buff = preg_replace('"', "\&quot;", $buff);
	$buff = trim($buff);
//	echo "After: " . $buff;
	return $buff;
}
//$scraped=br2nl($scraped);
$DOM = new DOMDocument();
$DOM->loadHTML($scraped);

//print_r($DOM->loadHTML(br2nl($scraped)));

//
//print_r($DOM);
//$paragraphs = $DOM->getElementsByTagName("p");

//
//function DOMinnerHTML(DOMNode $element)
//{
//	$innerHTML = "";
//	$children  = $element->childNodes;
//
//	foreach ($children as $child)
//	{
//		$innerHTML .= $element->ownerDocument->saveHTML($child);
//	}
//
//	return $innerHTML;
//}
//
//foreach ($paragraphs as $paragraph){
//	$p_text = new DOMDocument();
////	echo DOMinnerHTML($p_text);
//	$p_text->loadHTML(str_ireplace(array("<br>", "<br />"), "\r\n", DOMinnerHTML($paragraph)));
//	echo json_encode(DOMinnerHTML($paragraph));
//	//Do whatever, in this case get all of the words in an array.
////	$words = explode(" ", str_ireplace(array("\n"), " ", $p_text->textContent));
////	print_r($words);
//}
//

//
//
//$doc = new DOMDocument();
//$doc->loadHTML('<?xml encoding="UTF-8">' . $scraped);
//
//// dirty fix
////foreach ($doc->childNodes as $item)
////	if ($item->nodeType == XML_PI_NODE)
////		$doc->removeChild($item); // remove hack
////$doc->encoding = 'UTF-8'; // insert proper
////
////echo $doc;
//
//
//
//function convertBreaksToNewLines($txt) {
//	$breaks = array("\t\n");
//	$txt = str_ireplace($breaks, "\\n''", $txt);
//	echo $txt . "\n\n";
//	return $txt;
//}


//$text = str_ireplace($breaks, "\r\n", $text);
// we'll need this to preserve the formatting of the reviews
//
//echo $scraped;


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


//foreach($DOM->find('p') as $p) {
//	if ($p->text()) {
//		echo $p->next_sibling();
//	}
//}



libxml_use_internal_errors(TRUE); //disable libxml errors

if(!empty($scraped)) {
//if any html is actually returned
	$formatted = new DOMDocument();
	$formatted->loadHTML(br2nl($scraped));
//
//	$DOM->loadHTML($scraped);
	$scraped_xpath = new DOMXPath($formatted);
	$doc = new DOMDocument;
	libxml_use_internal_errors(true);
//	$doc->loadHTML($fo);
	$scraped_row = $scraped_xpath->query("//div[@class='review review--with-sidebar']");
	$nodelist = $scraped_xpath->query("//div[@class='review review--with-sidebar']//p[@itemprop='description']/..");
//// the number of nodes in the list
//
	$node_counts = $scraped_row->length; // in our example it returns 5
//	if ($node_counts) { // it will be true if the count is more than 0
//
//////
////		foreach ($nodelist as $node) {
//////			$doc = convertBreaksToNewLines($node->nodeValue);
////			echo $node->nodeName . ' => ' . $node->nodeValue . ' => ' . $node->getattribute('type') . PHP_EOL;
////		}
//	}
}
//}
//
////$node->ownerDocument->saveHTML($node);
//
//
//
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
$html = $scraped_xpath->query($GLOBALS['QUERY']['content']);
$q = $scraped_xpath->query("//div[text()='\"']");


if($review_obj->length > 0) {
	$ctr = 0;

	echo "[\n";

	// since index(obj) <=> identity(obj), the value of obj[key] is simply the current index of Arr[key]
	foreach ($review_obj as $pat) {

		//so just refocus data selectors on the current element of our data arrays
		$name = $names->item($ctr)->nodeValue;
		$rating = $ratings->item($ctr)->nodeValue;
		$rating = substr($rating, 0, 1);
		$city = $cities->item($ctr)->nodeValue;
		$content = $contents->item($ctr)->nodeValue;
		$date = $dates->item($ctr)->nodeValue;
		$avatar = $avatars->item($ctr)->nodeValue;

		//rewrite the avatar URL to the higher resolution version

		if (substr($avatar, 0, -7) == "60s.jpg") {
			$avatar = substr($avatar, 0, -7) . "ms.jpg";
		}

		//sanitize the input
//		$content = filter_var($content, FILTER_SANITIZE_STRING);
//		$content = filter_var($content, FILTER_SANITIZE_MAGIC_QUOTES);
//		$content = str_ireplace(array('"'), " ", $content);
//		$content = json_encode($content);





		//and push
		$review_list[] = array('date' => $date,
			'name' => $name,
			'city' => $city,
			'avatar' => $avatar,
			'rating' => $rating,
			'stars' => $GLOBALS['STARIMG'],
			'content' => $content);

		$weightedLength = strlen(str_replace("\\n", "                                    ", $content));

		$content = str_replace("'", "&apos;", $content);
		$content = str_replace('"', "&quot;", $content);

     	$content = str_replace("\n\n", "\n", $content);
		$weightedLength = strlen(str_replace("\\n", "                                    ",  $content));

		if (($rating == "5") & ($weightedLength < 500)) {


			//but actually what we really want is JSON, which we can simply echo to stdout
			echo "      {\n\"id\" : \"", $ctr, "\",\n";
			echo "      \"date\" : \"", $date, "\",\n";
			echo "      \"name\" : \"", $name, "\",\n";
			echo "      \"avatar\" : \"", $avatar, "\",\n";
			echo "      \"starsprite\": \"", $GLOBALS['STARIMG'], "\",\n";
			echo "      \"city\" : \"", $city, "\",\n";
			echo "      \"rating\" : \"", $rating, "\",\n";
			echo "      \"content\" : ", json_encode(nl2br($content));

//		echo "      \"formattedContent\" : \"", $content, "\"\n";
			if ($ctr == 19) {
				// drop comma from last element of JSON table
				// todo: write a valid predicate test for this condition instead of assuming a fixed array of 20 elements
				echo "   }\n";
			} else {
				echo "   },\n";
			}


		}
		$ctr += 1;
//    print_r($review_list);

	}
	echo "]";
}




