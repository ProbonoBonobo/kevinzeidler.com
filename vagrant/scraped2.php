<?php

// THE KEYRING
// Not an array of queries, but an array of arrays of queries. Like so:

$KEYRING = array('partition' => array("//div[@class='review review--with-sidebar']",
						     "(//div[contains(@class,'with-sidebar')])[position()>1]",
						     "(//div[contains(@itemtype,'schema.org/Review')])"),
			 'name' 	 => array("//div[@class='review review--with-sidebar']//a[@class='user-display-name']",
	                     				"//a[@class='user-display-name']"),
	              'avatar'    => array("//img[contains(@src,'60s.jpg') and contains(@src, '/photo/') or contains(@src,'default_avatars')]/@src"),

                 	 'rating'     => array("//div[@class='review review--with-sidebar']//meta[@itemprop = 'ratingValue']/@content",
						              "//div[@itemprop='reviewRating']/div/meta[@itemprop='ratingValue']/@content"),
              	 'content'   => array("//div[@class='review review--with-sidebar']//p[@itemprop='description']"),
                	 'date'       => array("//div[@class='review review--with-sidebar']//meta[@itemprop='datePublished']/@content"));

$scraped = file_get_contents("scraped.html");
$PARTITIONED = array('html' => array(range(0,19)),
				     'xpath' => array(range(0,19)),
	                 'dom' => array(range(0,19)),
                     'xml' => array(range(0,19)));


// instantiate the object model

//function needle($b) {
//    // a function of a single argument which simply
//    // checks $b for equality to "foo"
//    echo $b . ((strcmp($b, "foo") !== 0) ? " is not foo." : "is foo.");
//}
//
//echo print_r(needle("foo")), print_r(needle("bax"));
//$haystack = array("bar", "baz", "bees", "bears", "dickbutt", "foo");
//
//echo print_r(array_map("needle", $haystack));


if(!empty($scraped)) { //if any html is actually returned
    $DOM = new DOMDocument('1.0', 'UTF-8');
    $DOM->preserveWhiteSpace = false;
    $DOM->loadHTMLFile("scraped.html"); //reconstitute the DOM from html
    $DOM->formatOutput = true;
    $DOM->encoding = 'UTF-8';
    $xml = $DOM->saveXML();
    $htm = $DOM->saveHTML();
    $scraped_xpath = new DOMXPath($DOM); //load the DOM into a convenient database-like representation


    foreach ($KEYRING['partition'] as $partition_function) {
        $xpath_resultset = $scraped_xpath->query($partition_function);

        if ($xpath_resultset->length == 20) {
            $ctr = 0;

            foreach ($xpath_resultset as $frag) {
                $dom_fragment = new DOMDocument('1.0', 'UTF-8');
                $dom_fragment->preserveWhiteSpace = false;
                $dom_fragment->loadHTML($frag->nodeValue); //reconstitute the DOM from html
                $dom_fragment->formatOutput = true;
                $dom_fragment->encoding = 'UTF-8';
                $html_fragment = $dom_fragment->saveHTML();
                $xpath_fragment = new DOMXPath($dom_fragment);
                $PARTITIONED['dom'][$ctr] = $dom_fragment;
                $PARTITIONED['html'][$ctr] = $DOM->saveHTML($frag);


                echo "what's the type of partitioned[html][ctr]? " . gettype($PARTITIONED['html'][$ctr]);
//				$xpath_frag = new DOMXPath($PARTITIONED['html'][$ctr]);
                print "Element #" . $ctr . ": \n\n" . $PARTITIONED['html'][$ctr] . "\n\n";
                $thisname = $scraped_xpath->query("//a[@class='user-display-name']");
                print "name: ". $thisname->item($ctr)->nodeValue;
                $ctr += 1;
            }
            print "================================\n\n";


        }

        break;
    }

//			$my_content = $xpath_resultset->item(0);
//			$html_string = $dom->saveHTML($xpath_resultset->item(0));
//			echo $html_string;

//	foreach ($KEYRING['partition'] as $partition_function) {
//		$no_of_bins_captured_by_query = $scraped_xpath->query($partition_function)->length;
//		print "Query string " . $partition_function . " returns " . $no_of_bins_captured_by_query . " results. \n\n";
//		if ($no_of_bins_captured_by_query == 20) {
//
//			echo "Now chopping the DOM into 20 bite-sized, tasty, review morsels. \n";
//			$KEYRING['HTML_bins'] = $partition_function . "//text()";
//			print "The revised query is: " . $KEYRING['HTML_bins'] . ".\n";
//			print "Its HTML results are: ";
//			print $xpath_resultset->item(0)->nodeValue;
//			$html_string = $dom->saveHTML($xpath_resultset->item(0)->nodeValue);
//			print $html_string;
////				$DOM_fragment = new DOMDocument();
//				$DOM_fragment->saveXML($html_fragment);
//				$PARTITIONED = $XPATH_fragment->query($KEYRING['HTML_bins']);


//			print $PARTITIONED->nodeValue;
//
//		}
//	}


}
//// fancy css sprite implementation of a little picture of '5 stars'
//	$STARIMG = "<img alt='5.0 star rating' class='offscreen' height='303' src='//s3-media4.fl.yelpcdn.com/assets/srv0/yelp_styleguide/c2252a4cd43e/assets/img/stars/stars_map.png' width='84'>";
//// go get the raw HTML of the yelp page
////$scraped = file_get_contents("http://www.yelp.com/biz/mobile-iphone-repair-ipad-screen-repair-san-diego-24");
////$scraped = file_get_contents("./scraped.html");
//

// Defining the basic cURL function
	function curl($url)
	{
		$ch = curl_init();  // Initialising cURL
		curl_setopt($ch, CURLOPT_URL, $url);    // Setting cURL's URL option with the $url variable passed into the function
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // Setting cURL's option to return the webpage data
		$data = curl_exec($ch); // Executing the cURL request and assigning the returned data to the $data variable
		curl_close($ch);    // Closing cURL
		print sprintf("%s", $data);
		return $data;   // Returning the data from the function
	}


//	function openSesame($xpath, $door)
//	{
//		echo "Attempting to partition the page.\n\n";
//		if ($door == 'row') {
//			$keys = $GLOBALS['KEYRING']['partition'];
//			$attempt = 0;
//			$doorLocked = true;
//			while ($doorLocked && ($attempt < count($keys->length))) {
//				$result = $xpath->query($keys[$attempt]);
//				echo "Attempt #", $attempt, "...\n";
//				echo "The query string is \"", $keys[$attempt], "\".\n";
//				echo "The query returns ";
//				for ($i = 0; $i < $result->length; $i++) {
//					echo $i, ".  ", $result->item($i)->nodeValue, "/n";
//				}
//				echo "The query returns ", count($xpath->query($keys[$attempt])), " reviews.";
//				$doorLocked = count($xpath->query($keys[$attempt])) !== 20;
//				++$attempt;
//			}
//		}
//	}

	function DOMinnerHTML(DOMNode $element)
	{
		$innerHTML = "";
		$children = $element->childNodes;

		foreach ($children as $child) {
			$innerHTML .= $element->ownerDocument->saveHTML($child);
		}

		return $innerHTML;
	}


	libxml_use_internal_errors(TRUE); //disable libxml errors

//
//	$numerically_indexed_array = array_values($GLOBALS['KEYRING']['name']);
//	echo "Here is the partitioned DOMXPathNodeList: ";
//	foreach ($partitioned as $bin) {
//		print $bin->nodeValue;
//		echo "\n====================";
//	}

	$review_list = array();




	$extracted = array("names" => $scraped_xpath->query($GLOBALS['KEYRING']['name'][0]),
		"ratings" => $scraped_xpath->query($GLOBALS['KEYRING']['rating'][0]),
		"contents" => $scraped_xpath->query($GLOBALS['KEYRING']['content'][0]),
		"cities" => $scraped_xpath->query($GLOBALS['KEYRING']['city'][0]),
		"avatars" => $scraped_xpath->query($GLOBALS['KEYRING']['avatar'][0]),
		"dates" => $scraped_xpath->query($GLOBALS['KEYRING']['date'][0]));

	$names = $extracted['names'];
	$rating = $extracted['ratings'];
	$cities = $extracted['cities'];
	$content = $extracted['contents'];
	$avatars = $extracted['avatars'];
	$dates = $extracted['dates'];

//	foreach ($names as $name) {
//		print $name->nodeValue;
//	}


	if ($review_obj->length > 0) {
		$ctr = 0;

//	echo "[\n";

		// since index(obj) <=> identity(obj), the value of obj[key] is simply the current index of Arr[key]
		foreach ($review_obj as $pat) {

			//so just refocus data selectors on the current element of our data arrays
			$name = $names->item($ctr)->nodeValue;
			$rating = $ratings->item($ctr)->nodeValue;
			$city = $cities->item($ctr)->nodeValue;
			$content = $contents->item($ctr)->nodeValue;
			$date = $dates->item($ctr)->nodeValue;
			$avatar = $avatars->item($ctr)->nodeValue;

			//sanitize the input
			$content = filter_var($content, FILTER_SANITIZE_STRING);

			//rewrite the avatar URL to the higher resolution version
//		echo "\n\n\nNow inspecting ", substr($avatar, -7),"\n\n\n";
//		echo "strcmp returns: ", strcmp(substr($avatar, -7), "60s.jpg");

			if (strcmp(substr($avatar, -7), "60s.jpg") !== 0) {
				$avatar = "img/anon.jpg";
			} else {
				$avatar = "http://" . substr($avatar, 2, -7) . "ms.jpg";
			}


			//and push
			$review_list[] = array('date' => $date,
				'name' => $name,
				'city' => $city,
				'avatar' => $avatar,
//				'rating' => $rating,
				'stars' => "5.0",
				'content' => $content);




			//but actually what we really want is JSON, which we can simply echo to stdout
//        echo "      {\n\"id\" : \"", $ctr, "\",\n";
//        echo "      \"date\" : \"", $date, "\",\n";
//		echo "      \"name\" : \"", $name, "\",\n";
//		echo "      \"avatar\" : \"", $avatar, "\",\n";
//		echo "      \"starsprite\": \"", $GLOBALS['STARIMG'], "\",\n";
//		echo "      \"city\" : \"", $city, "\",\n";
//		echo "      \"rating\" : \"", $rating, "\",\n";
//		echo "      \"content\" : \"", $content, "\",\n";
//		echo "      \"formattedContent\" : \"", $content, "\"\n";
//		if ($ctr == 19) {
//			// drop comma from last element of JSON table
//			// todo: write a valid predicate test for this condition instead of assuming a fixed array of 20 elements
//			echo "   }\n";
//		} else {
//			echo "   },\n";
//		}
//
//        $ctr += 1;
//    }
////    print_r($review_list);
//    echo "]";
//}

			$output = json_encode($review_list);
            echo $output;
			$ctr += 1;
		}
	}



?>