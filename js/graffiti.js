// 2/22: It would appear we need to construct a model of the JSON object in order to filter that object on the client's side.
$.ajax({
           url: "blogfeed-dev.json",
           dataType: 'json',
           success: function (res) {
               localStorage.setItem("dataCache", JSON.stringify(res));
           }
       });

var manifest, db;


if(localStorage.getItem("dataCache")) {
    manifest = JSON.parse(localStorage.getItem("dataCache"));
}


var table, row, tags;
table = [];
console.log("Now constructing a model from ", manifest);
for (i in manifest['posts']) {
    row = [];
    row.push(i,  (manifest['posts'][i]['month'] + " " + manifest['posts'][i]['year']),  manifest['posts'][i]['category'], manifest['posts'][i]['title'],manifest['posts'][i]['lede'], manifest['posts'][i]['image'],manifest['posts'][i]['tags' ].join());
    table.push(row);
}
console.log("Model constructed. ", table);


function get(i, field) {
    return manifest['posts'][i][field];
}


var viewModel = function(){
    var self = this;
    self.posts = ko.observableArray();
    self.appendRow = function(data, event) {
        self.posts.push({ID: table[data][0], monthYear: table[data][1], flytitle: table[data][2], headline: table[data][3], lede:table[data][4], img: table[data][5], tags: table[data][6]});

    };
    self.appendAll = function(data, event) {
        for(i in data) {
            self.appendRow(i);
        }
    };

    self.headers = [
        {title:'ID',sortPropertyName:'ID', asc: true, active: false},
        {title:'Publication Date',sortPropertyName:'monthYear', asc: true, active: false},
        {title:'H2',sortPropertyName:'flytitle', asc: true, active: false },
        {title:'H1',sortPropertyName: 'headline', asc: true, active: false },
        {title: 'Lede', sortPropertyName: 'lede', asc: true, active: false},
        {title: 'Image', sortPropertyName: 'img', asc: true, active: false},
        {title: 'Tags', sortPropertyName: 'tags', asc: true, active: false}
    ];
    self.filters = [
        {title:'Show All', filter: null},
        {title:'Kittens', filter: function(item){return item.tags.includes("kittens");}},
        {title:'jQuery', filter: function(item){return item.tags.includes("jQuery");}},
        {title:'Eels', filter: function(item){return item.tags.includes("eels"); }},
        {title:'Show All', filter: null},
        {title:'February 2016', filter: function(item){return item.monthYear==("February 2016");}},
        {title:'March 2019', filter: function(item){return item.monthYear==("March 2019");}}
    ];


    self.activeSort = ko.observable(function(){return 0;}); //set the default sort
    self.sort = function(header, event){
        //if this header was just clicked a second time
        if(header.active) {
            header.asc = !header.asc; //toggle the direction of the sort
        }
        //make sure all other headers are set to inactive
        ko.utils.arrayForEach(self.headers, function(item){ item.active = false; } );
        //the header that was just clicked is now active
        header.active = true;//our now-active header

        var prop = header.sortPropertyName;
        var ascSort = function(a,b){ return a[prop] < b[prop] ? -1 : a[prop] > b[prop] ? 1 : a[prop] == b[prop] ? 0 : 0; };
        var descSort = function(a,b){ return a[prop] > b[prop] ? -1 : a[prop] < b[prop] ? 1 : a[prop] == b[prop] ? 0 : 0; };
        var sortFunc = header.asc ? ascSort : descSort;

        //store the new active sort function
        self.activeSort(sortFunc);
    };

    self.activeFilter = ko.observable(self.filters[0].filter);//set a default filter
    self.setActiveFilter = function(model,event){
        self.activeFilter(model.filter);
    };

    self.filteredPeople = ko.computed(function(){
        var result;
        if(self.activeFilter()){
            result = ko.utils.arrayFilter(self.posts(), self.activeFilter());
        } else {
            result = self.posts();
        }
        return result.sort(self.activeSort());
    });
};
vm = new viewModel();
ko.applyBindings(vm);