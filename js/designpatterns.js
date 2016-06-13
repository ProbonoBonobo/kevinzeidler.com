/**
 * Created by kevinzeidler on 6/12/16.
 */

function ObserverList(){
    this.observerList = [];
}

ObserverList.prototype.add = function( obj ){
    return this.observerList.push( obj );
};

ObserverList.prototype.count = function(){
    return this.observerList.length;
};

ObserverList.prototype.get = function( index ){
    if( index > -1 && index < this.observerList.length ){
        return this.observerList[ index ];
    }
};

ObserverList.prototype.indexOf = function( obj, startIndex ){
    var i = startIndex;

    while( i < this.observerList.length ){
        if( this.observerList[i] === obj ){
            return i;
        }
        i++;
    }

    return -1;
};

ObserverList.prototype.removeAt = function( index ){
    this.observerList.splice( index, 1 );
};

function Subject(){
    this.observers = new ObserverList();
}

Subject.prototype.addObserver = function( observer ){
    this.observers.add( observer );
};

Subject.prototype.removeObserver = function( observer ){
    this.observers.removeAt( this.observers.indexOf( observer, 0 ) );
};

Subject.prototype.notify = function( context ){
    var observerCount = this.observers.count();
    for(var i=0; i < observerCount; i++){
        this.observers.get(i).update( context );
    }
};

// The Observer
function Observer(){
    this.update = function(){
        // ...
    };
}


var basketModule = (function () {

    // privates

    var basket = [];
    var needsUpdate = new Map();
    var subtotal = 0;

    function doSomethingPrivate() {
        console.log("yeah I work");
    }

    function updateView() {
        console.log(needsUpdate);
        var root = document.getElementById("basket" ),
            itemsInBasket = document.getElementsByClassName("ordered" ),
            itemQuantities = document.getElementsByClassName("quantity" ),
            itemCounter = this.getItemCounter();
        itemCounter.forEach(function(item) {
            console.log(item +  " needs update: " + needsUpdate.get(item));
            if (needsUpdate.get(item)) {
                if (updateHandler(item)) {
                    needsUpdate.set(item,false);
                }
            }
        });
    }

    function doSomethingElsePrivate() {
        //...
    }

    // Return an object exposed to the public
    return {

        // Add items to our basket
        addItem: function( values ) {
            if (Array.isArray(values)) {
                values.map( function ( x ) {
                                if ( x instanceof (Orderable
                                    ) ) {
                                    basket.push( x );
                                    needsUpdate.set(x["item"], true);
                                }
                                ;
                            }
                );
            } else {
                if (values instanceof(Orderable)) {
                    basket.push(values);
                    needsUpdate.set(values["item"], true);
                }
            }

            this.update();
        },

        // Get the count of items in the basket
        getItemCount: function () {
            return basket.length;
        },

        update: updateView,

        // Public alias to a private function
        doSomething: doSomethingPrivate,

        // Get the total value of items in the basket
        getTotal: function () {

            var q = this.getItemCount(),
                p = 0;

            while (q--) {
                p += basket[q].price;
            }

            return p;
        },

        getOrderableObjects: function(db) {
            orderables = [];
            for (var row in db) {
                if (db[row] instanceof(Orderable)) {
                    console.log(db[row]);
                    orderables.push( db[ row ] );
                }
            }
            return orderables;
        },

        getObjects: function() {
            return basket;
        },

        getItems: function() {
            var names = [];
            for (orderable in basket) {
                names.push(basket[orderable]["item"]);
            };
            return names;

        },

        getPrices: function() {
            var prices = [];
            for (orderable in basket) {
                prices.push(basket[orderable]["price"]);
            }
            return prices;
        },

        getItemCounter: function() {
            var ctr = new Counter(this.getItems());
            return ctr;
        },


    };
})();

function Orderable(item, price, quantity, rendered) {
    this.item  =
        item;
    this.price =
        price;
    this.quantity =
        quantity;
    this.rendered =
        new Map().set("Static", rendered);

}

function Counter(arr) {
    var unique = new Set(arr);
    unique.forEach(function(value) {
        unique[value] = arr.filter(function(x) {
            return x === value;
        } ).length;
    });
    return unique;
}



Orderable.prototype.info = function() {
    return this.item + " costs " + this.price;
};