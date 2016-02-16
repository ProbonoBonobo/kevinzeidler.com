

var callback;
callback = function (data) {
    var jsonResponse = data.responseJSON;
    var postArray = data.responseJSON.posts;
};

$.getJSON("blogfeedv2.json", callback);


var dateTags = new Set();
var contentTags = new Set();
var intervalTags = new Set();
var tags = {"monthYear": dateTags, "relatedContent": contentTags, "histogram": intervalTags};

postArray.map(function (obj) {
    dateTags.add(obj['month'] + " " + obj['year']);
});

postArray.map(function (obj) {
    obj['tags'].map(function (topic) {
        contentTags.add(topic);
    });
});

for (i = 0; i < posts.length; i++) {
    if (i % 5 === 0) {
        intervalTags.add(i);
    }
}


postArray.map(function (obj) {
    console.log(obj);
});

function getJSONValueOf(param, index) {
    return data.responseJSON['posts'][index][param];
}

function quoteattr(s, preserveCR) {
    // For sanitizing strings.
    preserveCR = preserveCR ? '&#13;' : '\n';
    return ('' + s) /* Forces the conversion to string. */
        .replace(/&/g, '&amp;') /* This MUST be the 1st replacement. */
        .replace(/'/g, '&apos;') /* The 4 other predefined entities, required. */
        .replace(/"/g, '&quot;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        /*
         You may add other replacements here for HTML only
         (but it's not necessary).
         Or for XML, only if the named entities are defined in its DTD.
         */
        .replace(/\r\n/g, preserveCR) /* Must be before the next replacement. */
        .replace(/[\r\n]/g, preserveCR);
}


//$.getJSON("blogfeedv2.json", function (data) {
//    var len = data['length'];
//    for (var i in data['posts']) {
//        console.log(len, i, data['posts'][i]);
//        var abbreviatedMonth = getJSONValueOf('month', i).slice(0, 3);
//        console.log(abbreviatedMonth);
//        $('<span class="date" id="day">' + getJSONValueOf('day', i) + '<br><small id="month">' + abbreviatedMonth + '</small></span>').insertAfter((".post#" + (len - i) + " .date"));
//        $('<h4>' + getJSONValueOf('title', i) + '</h4>').insertAfter(('.post#' + (len - i) + ' .post-title'));
//        $('<h6 id="lede">' + getJSONValueOf('lede', i) + '</h6>').insertAfter(('.post#' + (len - i) + ' .post-meta'));
//        $('<img src = \"' + getJSONValueOf('image', i) + '\" alt=\"' + getJSONValueOf('title', i) + '\">').insertAfter((".post#" + (len - i) + " .post-media"));
//        $(getJSONValueOf('content', i)).insertAfter((".post#" + (len - i) + " .post-body"));
//    }
//});
// $('<div class="ribbon"><div class="ribbon-stitches-top"></div><strong class="ribbon-content"><h2>' + data['posts'][propertyName]['title'] + '</h2></strong><div class="ribbon-stitches-bottom"></div></div>').insertAfter((".post#" + (i - data['posts'][propertyName]['id']) +" .title"));
// $("<div>By " + data['posts'][propertyName]['author'] + "</div>").insertAfter((".post#" + (i - data['posts'][propertyName]['id']) + " .author"));
// $("<img src =\""+data['posts'][propertyName]['avatar']+"\" width = 100px>").insertAfter((".post#" + (i - data['posts'][propertyName]['id']) + " .avatar"));					$("<div>" + data['posts'][propertyName]['content'] + "</div>").insertAfter((".post#" + (i - data['posts'][propertyName]['id']) + " .content"));
// 		}});