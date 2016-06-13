/**
 * Created by kevinzeidler on 5/14/16.
 */

//(define (make-stack)
//(let ((s '())
//(number-pushes 0)
//(max-depth 0)
//(current-depth 0))
//(define (push x)
//(set! s (cons x s))
//(set! number-pushes (+ 1 number-pushes))
//(set! current-depth (+ 1 current-depth))
//(set! max-depth (max current-depth max-depth)))
//(define (pop)
//(if (null? s)
//    (error "Empty stack -- POP")
//(let ((top (car s)))
//(set! s (cdr s))
//(set! current-depth (- current-depth 1))
//top)))
//(define (initialize)
//(set! s '())
//(set! number-pushes 0)
//(set! max-depth 0)
//(set! current-depth 0)
//'done)
//(define (print-statistics)
// (newline)
// (display (list 'total-pushes  '= number-pushes
//'maximum-depth '= max-depth)))
//(define (dispatch message)
//(cond ((eq? message 'push) push)
//((eq? message 'pop) (pop))
//((eq? message 'initialize) (initialize))
//((eq? message 'print-statistics)
//(print-statistics))
// (else
//(error "Unknown request -- STACK" message))))
//dispatch))


var root = this;
var _ = require('underscore');

var theEmptyList;
theEmptyList = [];

function car(x) {
    if (Array.isArray(x)) {
        return x[0];
    } else {
        console.log("Car called on " + x + " but " + x + "is not an array");
    }
}

var keys = function (arr) {
    var key, keys = [];
    for (i = 0; i < arr.length; i++) {
        for (key in arr[i]) {
            if (arr[i].hasOwnProperty(key)) {
                keys.push(key);
            }
        }
    }
    return keys;
};

function cdr(x) {
    if (Array.isArray(x)) {
        if ( x.length > 1 ) {
            // then move the front ptr over one
            return x.slice(1);
        } else {
            // cdr always returns an empty list when called on a single-element array, which functions like a
            // sentinel node
            return theEmptyList;
        }
    } else {
        console.log("Cdr called on " + x + " but " + x + "is not an array");
    }
}

function tailIsEmpty(val) {
    return strcmp(val,theEmptyList) === 0;
}
function square(x) {
    return x * x;
}

function strcmp ( str1, str2 ) {
    return ( ( str1 == str2 ) ? 0 : ( ( str1 > str2 ) ? 1 : -1 ) );
}

function arraysEqual(a, b, equalityCondition) {
    // utility method for evaluating whether 2 arrays are equal
    // ---------- Equality Conditions: ---------
    // "strict" = Are the two arrays materially equivalent?
    // "non-strict" = Are the two arrays materially equivalent after being sorted?
    // "loose" = Are the two arrays materially equivalent after application of any function literals?
    // -----------------------------------------

    if (equalityCondition === "strict") {
        // in which we are picky about order
        if ( a === b ) return true;
        if ( a == null || b == null ) return false;
        if ( a.length != b.length ) return false;
    } else if (equalityCondition === "non-strict") {
        // in which we sort first
        var sortedA, sortedB;

        sortedA = a.slice();
        sortedB = b.slice();

        for (var i = 0; i < a.length; ++i) {
            if (sortedA[i] !== sortedB[i]) return false;
        }
        return true;

    } else if (equalityCondition === "loose") {
        console.log("ERROR -- arraysEqual called in loose equality mode, but loose equality is not yet implemented!");
        return;
    }



}



function Stack() {
    var myStack;
    myStack =
        make_stack();
    myStack( "initialize" );


        return myStack;

    function SymbolTable() {

        var staticMethods, dynamicMethods, instanceVariables, self;
        self = this;
        self.instanceVariables = {};
        dynamicMethods = { "push" : true,
                            "pop" : true,
                            "initialize" : true
        };

        staticMethods = { "print_statistics" : true,
                          "instruction_count" : true,
                          "var_dump" : true};

        function isDynamic(msg) {
            if (dynamicMethods[msg]) {
                return true;
            }
            return false;
        }

        function isStatic(msg) {
            if (staticMethods(msg)) {
                return true;
            } return false;
        }
        function getMethodType(msg) {

            if (staticMethods[msg]) {
                return "static";
            } else {
                msgContainsArgs = function() { return !(msg === msg.split(" ")); };
                if (msgContainsArgs) {
                    msg = msg.split(" ");
                    inst = car(msg);
                    if (isDynamic(inst)) {
                       return "non-static";
                    }
                } else if (isDynamic(msg)) {
                    return "dynamic";

                }


            }

        }

        function add(symbol) {
            self.instanceVariables[symbol] = true;
            //console.log("Adding " + symbol + " to the namespace.");

        }

        function get(symbol) {
            return self.instanceVariables[symbol];
        }

        function put(symbol, val) {
            self.instanceVariables[symbol] = val;
            return symbol;
        }

        function swap(symbol, newValue) {
            var prev = get(symbol);
            put(symbol, newValue);
            return prev;

        }

        function dispatch(msg) {
            if (typeof(msg) !== "string" ) {
                console.log("ERROR -- dispatch called on " + msg + " but " + msg + " is a " + typeof(msg))
            }
            var tokenized = msg.split(" ");
            switch (car(tokenized)) {
                case "add":
                    return add( car( cdr( tokenized ) ) );
                case "getMethodType":
                    return getMethodType(msg);
            }


        }
        return dispatch;
    }

    function traceExecution() {
        var self = this;
        var ST = SymbolTable();

        function dispatch(expr) {
            // utility method for printing the literal expression as well as what it evaluates to
            // ex: show("x + 3") => x + 3 => 5 + 3 => 8
            var func, tokens, arguments, nextStep, msg, symbols;

            var symbols = ["s", "history", "number_pushes", "max_depth", "current_depth", "instruction_ctr", "trace_mode_enabled", "type_enforcement_mode_enabled", "array_value_type_parameter", "theEmptyString" ];
            symbols = this.symbols;
            msg = "";
            nextStep = expr.split(/[\{\}]+/);
            if(!arraysEqual(expr, nextStep,"non-strict")) {
                for(var i = 1; i < nextStep.length; i + 2 ) {
                    msg += traceExecution(nextStep[i]);
                }
            }



            msg = "";
            msg += expr + " => ";
            console.log("Read:  => " + msg);

            tokens = [];
            tokens = expr.split(",");
            console.log("Compile: => " + msg );

            arguments =
                tokens.map( function ( x ) { return x.split( /[\(\)]+/ ); } );



            if (!arraysEqual(tokens, arguments,"non-strict")) {
                console.log("Evaluate: => " + arguments);
                msg += car(tokens) + "(";

                arguments.map( function ( token ) {
                                   token.map( function ( x ) {

                                                  if ( !(isNaN( eval(  x  ) )
                                                      ) ) {
                                                      console.log( "Val:" + x + " " + eval( x ) );
                                                      msg +=
                                                          " (" + x + " => " + eval( x ) + ") ";
                                                  }
                                       if (ST(x)) {
                                           console.log(x);
                                       }
                                              }
                                   );
                               }
                );
                msg += ")";
            } else {
                console.log(" Print: ")
                var maybeVars;
                maybeVars =
                    expr.split( " " );
                maybeVars.map( function ( x ) {
                    console.log(x);
                                   if ( !isNaN( eval( x ) ) ) {
                                       msg +=
                                           eval( x );
                                       return;
                                   }
                               }
                );
            }
            console.log(msg);
        }



        return dispatch;


    }

    function make_stack() {
        var self = this;
        var symbols = [self.theEmptyList, self.s, self.history, self.number_pushes, self.max_depth, self.current_depth, self.instruction_ctr, self.trace_mode_enabled, self.type_enforcement_mode_enabled, self.array_value_type_parameter, self.theEmptyString, self.methodTypeDispatcher, self.stackTracer];
        var namespace = SymbolTable();
        var stackTracer = traceExecution();

        var parameters = ["max_depth", "current_depth", "instruction_ctr", "trace_mode_enabled", "type_enforcement_mode_enabled", "array_value_type_parameter", "theEmptyString", "methodTypeDispatcher", "stackTracer", "theEmptyList"];
        parameters.map(function(x) { namespace("add " + x); });



        theEmptyList = [];


        theEmptyString = "";
        s = theEmptyList.push("theEmptyString");
        number_pushes      =
            0;
        max_depth          =
            0;
        current_depth      =
            0;
        instruction_ctr    =
            0;
        history            =
            [];
        trace_mode_enabled =
            false;
        type_enforcement_mode_enabled = false;
        array_value_type_parameter = false;
        number_pushes



        function cons( x, s ) {
            return [ x,
                     s ];
        }

        function push( x ) {
            // It is interesting to note how this works in the current implementation.
            // This function is returned literally to the evaluation context.
            // What do we call this in Javascript?
            // (I believe this is what JS refers to as a 'continuation.' I have also heard it called a 'hook' in cases
            // where the argument to the continuation is itself a function.)
            // Our present definition of Stack() requires the evaluation context to invoke this function like so:
            // "root("push")(5);"
            // Thus root("push") returns a reference to this function; (5) invokes it.
            //

            console.log("Pushing " + x + ", a " + typeof(x));






            if (type_enforcement_mode_enabled) {
                if (strcmp(typeof(x), array_value_type_parameter) != 0) {
                    console.log("WARNING -- " + x + " not pushed to an array of " + array_value_type_parameter + "s."
                                + " " + x + " is a " + typeof(x) + "!");
                    instruction_ctr -= 1;
                    console.log(s);
                    return false;
                }
            }

            if (typeof(x) === "string" && x.substr(-1) == ")") {
                    console.log(x + " actually evaluates to " + eval(x) + "! Pushing that instead." );
                    push(eval(x));

            }

            var new_s = cons( x, s );
            s = new_s;

            number_pushes +=
                1;
            current_depth +=
                1;
            max_depth =
                Math.max( current_depth, max_depth );
            console.log(s);
            return s;
        }

        function pop() {
            if ( !s ) {
                console.log( "Empty stack -- POP" );
            }
            var top   = car( s );
            var new_s = cdr( s );
            s         =
                car(new_s);
            current_depth -=
                1;
            if (typeof(s) === "string" && top.substr(-2) == ')"') {
                return (eval(top));

            }
            return top;
        }

        function print_statistics() {
            console.log( '\ntotal pushes = ' + number_pushes );
        }

        function initialize() {
            var self = this;
            self.number_pushes = 0;
            self.instruction_ctr = 0;
            max_depth     =
                0;
            current_depth =
                0;
            instructions  =
                make_stack();
            console.log( "done" );
        }

        function instruction_count() {
            // 2 possible approaches:
            //
            // METHOD #1: modify dispatch to increment the instruction count every time a
            // valid instruction is issued, return the atomic value of the count
            //
            // METHOD #2: initialize a stack within the stack, modify dispatch to push
            // instructions to it, return the count of the array
            //
            // they're both valid
            return instructions.length();

        }

        function get(instanceVar) {
            switch(instanceVar) {
                case "instruction_ctr" :
                    return instruction_ctr;
            }
        }

        function var_dump() {
            console.log( "number_pushes = " + number_pushes + ";" );
            console.log( "max_depth = " + max_depth + ";" );
            console.log( "current_depth = " + current_depth + ";" );
            console.log( "instruction_ctr = " + instruction_ctr + ";" );
            console.log( "history = " + history + ";" );
            console.log( "max_depth = " + max_depth + ";" );
        }

        function updateTypeInfo(msg) {
            var tokens, maybe_fun, validated_type_parameter;
            tokens = msg.split(" ");
            maybe_fun = car(tokens);
            console.log("Updatin' type info");
            if (max_depth === 0) {
                if (maybe_fun === "just") {
                    if ( cdr( tokens ) && tailIsEmpty(cdr( cdr( tokens ) ) )) {
                        validated_type_parameter = cdr( tokens );
                        array_value_type_parameter    =
                            validated_type_parameter;
                        type_enforcement_mode_enabled =
                            true;
                        console.log("NOTICE -- Type enforcement mode enabled for type '" + validated_type_parameter + "'" );
                        return;

                    }
                    else {
                        console.log(cdr(cdr(tokens)) === [ '' ]);
                        console.log("cddr tokens: " +  tailIsEmpty( cdr( tokens ) ) );
                        console.log("cddr tokens: " +  tailIsEmpty( cdr(cdr( tokens ) ) ));
                        console.log( "ERROR -- " + tokens.slice( 1 ) + " isn't a valid type parameter" );

                    }
                } else {
                    console.log("ERROR -- I don't know how to " + msg + ". Why don't you try 'push' instead?");

                }
            } else {
                console.log("ERROR -- Unknown message: " + msg);
                console.log("maybe_fun: " + maybe_fun);
                console.log("cdr(tokens): " + cdr(tokens));
                console.log("tokens: " + tokens);
                console.log("history: " + history);
                console.log("history length: " + history.length);
            }
            instruction_ctr -=
                1; // if the instruction isn't valid, decrement the counter
            return;

        }

        function dispatch( msg ) {
            //  TODO: rewrite dispatch to parse the message on which it's being dispatched, so instead of
            // calling 'myStack("push")(5)', we could just say 'myStack("push 5")'

            self.instruction_ctr +=
                1;


            var dispatchOn = namespace(msg);

            history.push( msg );
            switch ( msg ) {

                case "push":
                    // If push is called without an argument, then return a reference to the literal function
                    return push;
                case "pop":
                    console.log("Pop!");
                    console.log("The top of the stack is: " + car(s));
                    return pop();
                case "initialize":
                    return initialize();
                case "print_statistics":
                    print_statistics();
                    break;
                case "instruction_count":
                    return instruction_count();
                case "var_dump":
                    return var_dump();
                case "instruction_ctr":
                    return self.instruction_ctr;
                case "get":
                    return get;
                case "print":
                    console.log(this.s);
                    break;

                default:
                    // if none of the above, the user may be attempting to specify a type parameter for the array
                    updateTypeInfo(msg);





            }
        }

        return dispatch;
    }
}

function Env(placename) {
    var self = this;
    var occupants, events, name;
    occupants = [];
    events = [];
    this.name = placename;
    this.events = events;
    this.occupants = occupants;
    this.print_occupants = function() { print_occupants(); };
    this.addUser = function(usr) { addUser(usr); };
    this.getOccupants = function() { getOccupants(); };
    this.isEnv = true;
    this.type = "env";


    function init() {
        var self = this;
        this.name      =
            name;
        this.occupants =
            occupants;
        this.events    =
            events;

        console.log("ENV -- new environment '" + placename + "' initialized.");
        return self;
    }

    function addUser(usr) {
        console.log("ENV[ '" + placename + "' ]  -- request to add user '" + usr.name + "'  received!");
        this.occupants = occupants.push(usr);
        console.log(self.name + " is now occupied by: " + occupants.map(function(x) {
                        return x.name; }));

        return self;
    }

    function print_occupants() {
        console.log(occupants);
    }

    function getOccupants() {
        return occupants;
    }


    function myEvents() {
        return this.events;
    }

    function ID() {
        return this.name;
    }

    init();


}

function User(name) {
    var self = this;
    var neighbors, env, action;
    neighbors = [];
    env = null;
    action = null;
    this.name = name;
    this.env = env;
    this.action = action;
    this.type = "user";
    this.neighbors = neighbors;
    this.getNeighbors = function() { getNeighbors(); };
    console.log("USER -- new user '" + name + "' initialized. Where should " + name + " go?");

    function myNeighbors() {
        console.log(env.occupants);
    }

    function init(startlocation) {
        var getNeighbors, neighbors;
        env = startlocation.addUser(self);
        this.env = env;
        this.action = action;
        return eventDispatcher;

    }
    function getNeighbors() {
        return neighbors;
    }


    function dispatch(msg) {
        switch ( msg.type ) {
            case "env":
                console.log( "You want to put "
                             + name
                             + " in "
                             + msg.name
                             + ", huh? Good choice! I'll ask "
                             + msg.name
                             + " to go ahead and add "
                             + name
                             + " now. "
                );
                init( msg );
                break;
            default:
                console.log( "USER -- Unknown place '" + msg + "'. Please specify a valid place to put " + name + "!" );
                return dispatch;
        }
    }

    function eventDispatcher(event) {
        switch ( event ) {
            case "entrance":
                break;
        }
    }







    return dispatch;
}

//var nullspace = new Env("nullspace");
//var bees = new User("bees" )(nullspace);
//var sabine = new User("sabine")(nullspace);
//var stabby = new User("stabby")(nullspace);
//nullspace.print_occupants();
//nullspace;
//



function FRP_World() {
    var app_state;
    function init() {
        var self = this;
        var app_state = {
            wire_A: "environment",
            wire_B: "none",
            components: {
                environment: {"none" : { null : { users : { "none" : { null : { things : "none"}}}}}}

            },
            componentState: {
                environment: [],
                users: [],
                things: []
            },
            eventStream: []

        };
        console.log("FRP_WORLD_CREATOR -- Request received. Creating a world.")
        return app_state;

    }



    var dispatch = {
        init : function() {
            console.log("FRP_WORLD_DISPATCHER -- Emitting request to FRP_WORLD_CREATOR...");
            app_state = init();
            console.log(this.app_state);

        },
        currentTarget: function () {
            console.log( "FRP_WORLD_DISPATCHER -- 'currentTarget' invoked as static method. Getting value of active"
                         + " register..."
            );
            var env_ptr = app_state.wire_A;
            var usr_ptr = app_state.wire_B;
            console.log( "    currentTarget: "
                         + String( env_ptr )
                         + " => "
                         + "\"" + usr_ptr + "\""
                         + " : "
                         + app_state.components[app_state.wire_A][app_state.wire_B]
            );
            return app_state.currentTarget;
        },
        create_env: function ( name ) {
            console.log("FRP_WORLD_DISPATCHER -- Emitting request 'create_env( '\"" + name + "\" )' to"
                        + " FRP_ENV_CREATOR...")

            app_state.components.environment.name =
                [];
            app_state.components.environment.name.push( create_env(name) );
            console.log(app_state.components);

        },

        create_usr: function ( name ) {
            this.app_state.components[this.app_state.wire_A][this.app_state.wire_B ].name = [];
            this.app_state.components[this.app_state.wire_A][this.app_state.wire_B ].name.push( name );

        },

        get_state: function () { return this.app_state; }
    };



    function create_env(name) {
        console.log("FRP_ENV_CREATOR -- Request received. Initializing new environment '" + name + "'...");
        app_state.wire_B = name;

        var Environment = {
            "env_name": null,
            "occupants": [],
            "maxOccupancy": null,
            "itemsPresent": [],
            "addPerson": function ( usr ) {
                console.log( "Pushing " + usr + " to " + this.env_name )
                this.occupants.push( usr );
            }

        };
        return this.Environment;
    };
    return dispatch;
}





var World = new FRP_World();
World.init();
World.currentTarget();
World.create_env("ithaca");

function flatten(arr) {

    var ret = arr.map(function(x) {
        flattened = [];
        if (Array.isArray(x)) {

            while ( x.length ) {
                x = x.map(function(i) {
                    if (!Array.isArray(i)) {
                      return flattened.concat(i)
                    }
                    for(var element = 0; element < i.length; element++) {
                        x.concat(element);
                    };

                });
                x = x.filter(function(i) {
                    return Array.isArray(i);
                });
                console.log(x);
            }
        }
        return x;
    });
    return ret;
}
console.log(flatten([1, 2, 3, 4, 5, [6, 7, [8, 9 ] [10, 11 ]]]));

console.log([1,2,3,4 ].slice(0, 4));



function sayHello(x) {
    return "root(\"pop\")";

}
//var root = new Stack();
//root("just string");
//root("push")("5");
//root("push")("old lady down a flight of stairs");
//root("push")("one more");
//root("push")(10);
//root("print");
//root("push")("let's get crazy");
//root("push")("sayHello(\"15\")");
//root("push")("15");
//root("print");
//root("pop");
//root("pop");
//root("pop");
//root("print");
//root("pop");
//root("print");

function range(min, max) {
    // god js is awful why is this necessary
    return Array.apply(null,Array(max) ).map(function(_, i) { return i+min; });

}





function mergesort(arr) {
    var helper = range(0,arr.length);
    return mergesortHelp(arr, helper, 0, arr.length-1);
}

function mergesortHelp(arr, helper, low, high) {
    var middle;
    if (low < high) {
        middle = Math.floor((low+high) / 2);
        arr = mergesortHelp(arr, helper, low, middle);
        arr = mergesortHelp(arr, helper, middle+1, high);
        arr = merge(arr, helper, low, middle, high);
    }
    return arr;
}

function merge(array, helper, low, middle, high) {
    for (var i = low; i<=high; i++ ) {
        helper[i] = array[i];
    }
    var helperLeft = low;
    var helperRight = middle + 1;
    var current = low;

    /* Iterate through helper array. Compare the left and right * half, copying back the smaller element from the two halves * into the original array. */
    while (helperLeft <= middle && helperRight <= high) {
        if (helper[helperLeft] <= helper[helperRight]) {
            array[current] = helper[helperLeft];
            helperLeft++;
        } else {
            array[current] = helper[helperRight];
            helperRight++;
        }
        current++;
    }
    //copy everything left of the pointer into the target array
    var remaining = middle - helperLeft;
    for(var i = 0; i <= remaining; i++) {
        array[current+i] = helper[helperLeft + i];

    }
    return array;
}

console.log(mergesort([ 1, 39, 20, 21, 1, 0, 492, 39, 8, 10, 3, 193, 291]));


function User(name, locale) {
    var inventory, locale;
    var self = this;

    self.inventory = [];
    self.name = name;
    self.locale= locale;


    //this.localEventStream = [];
    //this.processedEvents = [];
    //this.inventory = [];
    //this.type = "user";
    //this.id = app_state.users.length;

    function init(name, locale, localEventStream, processedEvents, inventory) {
        //var self = this;
        console.log("Initializing new user " + name);

        this.name             =
            name;
        this.locale           =
            locale;
        this.localEventStream =
            localEventStream;
        this.processedEvents  =
            processedEvents;
        this.type =
                  "user";
        this.inventory = inventory;
        this.id = Object.keys(app_state.users ).length;

        addUserToUserlist(this);



    }

    function withdrawItemFromInventory(usr, item) {
        var inventory, success;
        inventory = self.inventory;
        inventory.map(function (i) {
            // we're currently assuming the items are just strings
            if (i === item) {
                console.log("Removing " + item + " from " + self.name + "'s inventory.");
                success = true;
                return null;
            } else {
                return i;
            }
        });
        if (!success) {
            console.log("Couldn't find a " + item + "in " + self.name + "'s inventory.");
            return false;
        }
        return true;

    }

    function give(person, item) {
        var recipient, gift;
        recipient =
            checkoutUser( person );
        gift      =
            withdrawItemFromInventory(self, item );
        console.log(self.name + " would like " + person + " to have his prized " + item);
        if ( recipient ) {
            if ( gift ) {
                console.log(recipient + " takes " + self.name + "'s  " + item);
                console.log(recipient.name);
                recipient.apply
                msgToDispatch = "";
                msgToDispatch += "take " + item;
                //person.apply(function(x) { return msgToDispatch; });
            }
        }
    }

    function addToInventory(item) {

        if (typeof(item) === "string") {
            console.log(self.name + " found " + item + " on the ground and decides to put it in his backpack."
                    + " Luckeeeeh... ");
            self.inventory.push(item);
            console.log(self.inventory);
            };
    }
    function dispatch( msg ) {
        msg =
            msg.split( " " );

        switch ( msg[ 0 ] ) {
            case "enter":
                enter( user, place );
                break;
            case "give" :
                // Ex: Timothy("give Susan AIDS")
                give(msg[1], msg[2]);
                break;
            case "find" :
                // Ex: Timothy("find a_family_of_badgers")
                addToInventory(msg[1]);
                break;


            case "get":
                switch (msg[1]) {
                    case "id": // important to be able to access by index, we rely on this below for state updates
                        return self.id;
                    case "locale":
                        return self.locale;
                    case "localEventStream":
                        return self.localEventStream;
                    case "processedEvents":
                        return self.processedEvents;
                    case "name":
                        console.log(self.name + "'s name is " + self.name);
                        return self.name;
                    case "type":
                        return self.type;
                    case "inventory":
                        console.log(self.name + "' has an exciting collection of " + self.inventory);
                        return self.inventory;


                    default:
                        console.log("ERROR -- " + msg[1] + " isn't a property of a user!");
                }
            default:
                console.log( "I don't know how to " + msg[ 0 ] + " Would you like to teach me? " );
        }
        return this;
    }

    init(name, locale, self.localEventStream, self.processedEvents, self.inventory);
    return dispatch;
}


var app_state = {
    eventStream : [],
    users : {},
    userNeedsUpdate : function () {
        var userEventStreamIsUpToDate;
        userEventStreamIsUpToDate =
            app_state.users.map( function ( user ) {
                                     return user.localEventStream == app_state.eventStream;
                                 }
            );
    }


};

function addUserToUserlist(usr) {
    var new_state, usrname;
    usrname = usr.name;
    new_state = app_state;
    console.log("User list is currently: " + new_state.users);
    new_state.users[usrname] = usr;
    app_state = new_state;
    return app_state;
    console.log(app_state);

}

function checkoutUser(username) {
    // takes a string literal of the user's name, returns the object associated with the user's name
    var users = Object.keys(app_state.users);
    console.log("Iteratively searching " + users + " for " + username);
    return app_state.users[username];
    //users = users.filter(function(user) {
    //    return user.name === username;
    //});
    //if (users.length > 1) {
    //    console.log("WARNING -- Duplicate names added to user threadpool. Returning only the first " + username);
    //} else if (users.length == 0) {
    //    console.log("WARNING -- No user found by the name of " + username + ".");
    //    return;
    //}
    //return users[0];
}

var Timothy = new User("Timothy", "blackhole");
var bees = new User("bees", "blackhole");
bees("get name");
Timothy("get name");
var Susan = new User("Susan", "blackhole");
console.log(Timothy("get localEventStream"));

Timothy("get name");

Timothy("find superAIDS");
Timothy("give Susan superAIDS");
Susan("get inventory");


var S = new Set();
console.log( S.add[10, 10, 1, 10, 20]);
console.log(S.has(10));
var B = new Set([10, 5, 1]);
let union = new Set( [...[1,2,3,4,5,6,7],...[2,4,6,8,10,12]]);
console.log(union);
x =  [...union];
console.log(x);

var X = new Set();


st = {};
st["hello"] = true;
st["hello"] = true;
console.log(st);

function whatWasTheLocal(x) { if (!x) {
    var CAPTURED = "Oh hai";
} else {
    var CAPTURED = x;
}
    return function() {
        return "The local was: " + CAPTURED;
    };
}
var local = whatWasTheLocal("");
console.log(local());

function plucker(FIELD) { return function(obj) {
    return (obj && obj[FIELD]); };
}

function repeatedly(times, fun) { return _.map(_.range(times), fun);
}
repeatedly(3, function() {
    return Math.floor((Math.random()*10)+1);
});
x = [1, 2,3,4,10,39, 10,4,2,1];
console.log(_.map( _.range( 5 ), function(times) { console.log("Times: " + times);
    console.log(repeatedly(times, x.pop()));
    }));
var best = {title: "Infinite Jest", author: "DFW"};
var getTitle = plucker('title');
getTitle(best);
console.log(getTitle(best));


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



arr = "01001001 00100000 01100001 01101101 00100000 01100001 00100000 01101000 01101111 01110100 00100000 01101111 01110100 01110100 01100101 01110010 00100000 01100010 01101111 01110100 01110100 01101111 01101101 00101110";
arr = arr.split(" ");
//arr.map(function(x) {
//    var x = parseInt(x, 2);
//    console.log(String.fromCharCode(x));
//});




