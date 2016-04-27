

				$.getJSON( "reviews.json", function( data ) {
				  var items = [];
				  
				  items.push("<li>" + data[0]['name'] + </li>);
				  items.push(data[0]['avatar']);
				  items.push(data[0]['city']);
				  console.log(items);
				  return items;
			});
			$(items[0]).appendTo("#name");


	})