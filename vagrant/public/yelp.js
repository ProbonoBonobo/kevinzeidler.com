

// JSON.flatten = function(data) {
// 	// re
// 	    var result = {};
// 	    function recurse (cur, prop) {
// 	        if (Object(cur) !== cur) {
// 	            result[prop] = cur;
// 	        } else if (Array.isArray(cur)) {
// 	             for(var i=0, l=cur.length; i<l; i++)
// 	                 recurse(cur[i], prop + "[" + i + "]");
// 	            if (l == 0)
// 	                result[prop] = [];
// 	        } else {
// 	            var isEmpty = true;
// 	            for (var p in cur) {
// 	                isEmpty = false;
// 	                recurse(cur[p], prop ? prop+"."+p : p);
// 	            }
// 	            if (isEmpty && prop)
// 	                result[prop] = {};
// 	        }
// 	    }
// 	    recurse(data, "");
// 	    return result;
// }
function randRange (max) {
	return Math.floor(Math.random()*max+1);

}



		document.addEventListener('DOMContentLoaded', function() {
			$.getJSON( "reviews.json", function( data ) {
			  var items = [];
			  var loaded = [];
			  var keys = [];
			  console.log(data);
			  console.log("Most recent review: ");
			  var counter = 0;

			  $.each(data, function(key, val) {
			  	items.push("<div class='review-wrapper' id=\"" + counter + "\">/n/t");
			  	for(var key in data[0]) {
			  		console.log(key + " : " + data[counter][key]);
			  		keys.push(key);
			  		items.push("<div class='data' id=\"" + key + "\">" + data[counter][key] + "/n/t</div>");
			  		
			  		// console.log("The " + counter + "th element is: " + data[counter].keys);
			  	}
			  	items.push("<div class='index' id=\"'counter'\">" + items[counter] + "</div></div>");

			  	counter++;
			

			  while (items !== []) {
			  	var tmp = items.pop();
			  	console.log("Popped " + tmp + ".");
			  	$(tmp).appendTo( "div.data" );
			  	

			  		}
			    });
			  	// $.each(key, function(i) {
			  	// 	console.log(key + ":" + val);
			  	// });
			  	// var name = data[0]['name'];
			  	// var date = data[0]['date'];
			  // });
			  
			  // items.push( "<div class='name'>" + name + "</div></br>" );
			  // items.push( "<div class='date'>" + date + "</div></br>");

			 //  console.log("Name: " + data[0]['name']);
			 //  while (loaded.length < 3) {
			 //  	var next = data[randRange(19)];
			 //  	if ($.inArray(next, loaded) != -1) {
			 //  		console.log("Adding " + next + "to the widget panel.");
			 //  		loaded.push(next);
			 //  	}
			 //  }
			 // */
			  // indices.push(data[randRange(19)]);
			  // indices.push(randRange(19));
			  // indices.push(randRange(19));
			  // indices.push(randRange(19));
			  // indices.push(randRange(19));
			  // console.log("Indices are: " + indices[0] + "," + indices[1] + "," + indices[2]);
			//   $.each(loaded, function(i) {
			//   	$("<div id='name'>" + loaded[i]['name'] + "</div></br>").appendTo( "div.data" );
			//   	$("<div id='date'>" + loaded[i]['date'] + "</div></br>").appendTo( "div.data" );
			//   	$("<div id='content'>" + loaded[i]['content'] + "</div></br>").appendTo( "div.data");

			// });

			});


			  
	});

			 
			  