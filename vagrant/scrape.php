<?php

// THE KEYRING
// Not an array of queries, but an array of arrays of queries. Like so:

// $KEYRING = array('name' 	 => array("//meta[contains(@itemprop,'author')]/@content",
//                                        "//div[@class='review review--with-sidebar']//a[@class='user-display-name']",
//	                     				"//a[@class='user-display-name']"),
//                 'city'      => array("//div[@class='review review--with-sidebar']//b[contains(.,',')]"),
//	             'avatar'    => array("//img[contains(@src,'60s.jpg') and contains(@src, '/photo/') or contains(@src,'default_avatars')]/@src"),
//
//                 'rating'    => array("//div[contains(@itemtype, \"http://schema.org/Rating\")]/div/i[contains(@class, 'star-img') and contains(@title, 'star rating')]/img/@alt",
//                                      "//div[@class='review review--with-sidebar']//meta[@itemprop = 'ratingValue']/@content",
//						              "//div[@itemprop='reviewRating']/div/meta[@itemprop='ratingValue']/@content"),
//                 'content'   => array("//div[@class='review review--with-sidebar']//p[@itemprop='description']"),
//                 'date'      => array("//meta[contains(@itemprop,'datePublished')]/@content",
//                                      "//div[@class='review review--with-sidebar']//meta[@itemprop='datePublished']/@content"));





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



$scraped = file_get_contents("scraped.html");
$PARTITIONED = array('html' => array(range(0,19)),
				     'xpath' => array(range(0,19)),
	                 'dom' => array(range(0,19)),
                     'xml' => array(range(0,19)));

//partition\' => array("//div[@class=\'review review--with-sidebar\']",
//							 		  	"(//div[contains(@class,\'with-sidebar\')])[position()>1]",
echo '<div class="outer">
        <h2>Variables</h2>
            <span class="vartable">$KEYRING</span>
            <span class="vartable">$PARTITIONED</span>
            <span class="vartable">$OUTPUT</span>
</div>';

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
    $DOM->preserveWhiteSpace = true;
    $DOM->loadHTMLFile("scraped.html"); //reconstitute the DOM from html
    $DOM->formatOutput = true;
    $DOM->encoding = 'UTF-8';
    $htm = $DOM->saveXML();
    $scraped_xpath = new DOMXPath($DOM); //load the DOM into a convenient database-like representation
    $x = $scraped_xpath->query("//div[@class='review review--with-sidebar']//b[contains(.,',')]");



//    foreach ($KEYRING['partition'] as $partition_function) {
//        $xpath_resultset = $scraped_xpath->query($partition_function);
//
//        if ($xpath_resultset->length == 20) {
//            $ctr = 0;

//            foreach ($xpath_resultset as $frag) {
//                $dom_fragment = new DOMDocument('1.0', 'UTF-8');
//                $dom_fragment->preserveWhiteSpace = false;
//                $dom_fragment->loadHTML($frag->nodeValue); //reconstitute the DOM from html
//                $dom_fragment->formatOutput = true;
//                $dom_fragment->encoding = 'UTF-8';
//                $html_fragment = $dom_fragment->saveHTML();
//                $xpath_fragment = new DOMXPath($dom_fragment);
//                $PARTITIONED['dom'][$ctr] = $dom_fragment;
//                $PARTITIONED['html'][$ctr] = $DOM->saveHTML($frag);
//
//
////                echo "what's the type of partitioned[html][ctr]? " . gettype($PARTITIONED['html'][$ctr]);
////				$xpath_frag = new DOMXPath($PARTITIONED['html'][$ctr]);
////                print "Element #" . $ctr . ": \n\n" . $PARTITIONED['html'][$ctr] . "\n\n";
//                $thisname = $scraped_xpath->query("//a[@class='user-display-name']");
////                print "name: ". $thisname->item($ctr)->nodeValue;
//                $ctr += 1;
////            }
////            print "================================\n\n";
//
//
//            }

//        break;
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


//    }
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

//    $review_list = function() {

$json = array();

    foreach ($KEYRING as $field => $value) {
//        echo "value is" . "$value";
//        echo "field is" . "$field";

        foreach ($KEYRING[$field] as $querystring) {
            $xpath_results = $scraped_xpath->query($querystring);


//            echo "\n Query: " . $querystring;
            $json[$querystring] = array();
//            echo "\n Result: ";
            $ctr = 0;
            if ($xpath_results->length == 20) {
                echo "success\n\n             ";
                foreach ($xpath_results as $res) {

//                $results_are_nodes = strcmp(gettype($xpath_results), "DOMNodeList") !== 0;
//                var_dump($res);
//                var_dump($results_are_nodes);
                    $json[$ctr][$field] = $xpath_results->item($ctr)->nodeValue;
//                print $xpath_results->item($ctr)->nodeValue . "\n";
                    $ctr++;
                }
                break;
            } else {
                echo "boo\n\n      ";

            }
        }
    }


echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

var_dump($scraped_xpath->query($KEYRING["city"][0]));
$json = json_encode($json);
var_dump($json);
//var_dump($json);
//print $json;













?>