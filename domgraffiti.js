					// Here's the target dom node for reference
					//
					// <div class="post image" id="0">
					// 	<span class="date" id="day"><br><small id="month"></small></span>
					// 	<div class="post-title">
					// 		<h4><a href="blog-single.html" id="title"></a></h4>
					// 		<div class="post-meta">
					// 			<h6 id="lede"></h6>						
					// 		</div>
					// 	</div>
					// 	<div class="post-media" id="image">
					// 	</div>				
							
					// 	<div class="post-body" id="content-head"></div>
					// </div><!-- END -->

function getJSONValueOf(param, index) {
	return data['posts'][index][param];
}

$.getJSON( "blogfeedv2.json", function( data ) { 
		var len = data['length'];
		for (var i in data['posts']) {
			console.log(len, i, data['posts'][i]);
			$(getJSONValueOf('day',i)).insertAfter(".post#" + (len - i) + " #day");
			$('<div class="ribbon"><div class="ribbon-stitches-top"></div><strong class="ribbon-content"><h2>' + data['posts'][i]['title'] + '</h2></strong><div class="ribbon-stitches-bottom"></div></div>').insertAfter((".post#" + (i - data) +" .title"));
			$("<div>By " + data['posts'][i]['author'] + "</div>").insertAfter((".post#" + (i - data) + " .author"));
			$("<img src =\""+data['posts'][i]['avatar']+"\" width = 100px>").insertAfter((".post#" + (i - data) + " .avatar"));
			$("<div>" + data['posts'][i]['content'] + "</div>").insertAfter((".post#" + (i - data) + " .content"));
					}});