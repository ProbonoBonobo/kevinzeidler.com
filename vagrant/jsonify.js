document.addEventListener('DOMContentLoaded', function() {

function query(query) {
	var items = document.evaluate(query, document, null, XPathResult.ANY_TYPE, null);
	var thisHeading = items.iterateNext(); 
	var results = ("Results for query \"" + query + "\" in this document are:\n");
	var ctr = 0;
	while (thisHeading) {
		results += (ctr + ". " + thisHeading.textContent + "\n");
	  	thisHeading = items.iterateNext();
	  	ctr += 1;
	}
	return results;
}

function serialize(query) {
	// returns an array
	items = document.evaluate(query, document, null, XPathResult.ANY_TYPE, null);
	arr = [];
	var thisHeading = items.iterateNext(); 
	var results = ("Results for query \"" + query + "\" in this document are:\n");
	var ctr = 0;
	while (thisHeading) {
		arr.push(thisHeading.textContent);
	  	thisHeading = items.iterateNext();
	}
	return arr;
}

function snapshot(query) {
	// returns an array
	items = document.evaluate(query, document, null, XPathResult.ORDERED_NODE_SNAPSHOT_TYPE, null);
	for ( var i=0 ; i < nodesSnapshot.snapshotLength; i++ )
	{
  		myObj = nodesSnapshot.snapshotItem(i).textContent;
  		console.log("my object: %o", myObj)
	}
	return arr;
}

var reviews = snapshot("//div[@itemprop=\'review\']");

function get(nodeType) {
	if (nodeType === "users") {
		return query("//a[@class=\'user-display-name\']")
	} 

}
			 
			  $( "<ul/>", {
			    "class": "my-new-list",
			    html: getReviews();
			  }).appendTo( "body" );


		};