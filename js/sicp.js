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

function car(x) {
    if (Array.isArray(x)) {
        return x[0];
    } else {
        alert("Car called on " + x + " but " + x + "is not an array");
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
            return x.slice(1);
        } else {
            return [];
        }
    } else {
        alert("Car called on " + x + " but " + x + "is not an array");
    }
}
function make_stack () {
    var instance_variables, s, theEmptyString, history, number_pushes, max_depth, current_depth, instruction_ctr, trace_mode_enabled, instructions;
    theEmptyString = "*null*";
    s             =
        theEmptyString;
    number_pushes =
        0;
    max_depth     =
        0;
    current_depth =
        0;
    instruction_ctr =
        0;
    history = [];
    trace_mode_enabled = false;



    function kons( x, s ) {
        return [ x,
                 s ];
    }

    function push( x ) {
        var new_s = kons( x, s );
        s         =
            new_s;
        number_pushes +=
            1;
        current_depth +=
            1;
        max_depth =
            Math.max( current_depth, max_depth );
    }

    function pop() {
        if ( !s ) {
            alert( "Empty stack -- POP" );
        }
        var top   = car( s );
        var new_s = cdr( s );
        s         =
            new_s;
        current_depth -=
            1;
        return top;
    }

    function print_statistics() {
        console.log( '\ntotal pushes = ' + number_pushes );
    }

    function initialize() {
        s             =
            [theEmptyString];
        number_pushes =
            0;
        max_depth     =
            0;
        current_depth =
            0;
        instructions = make_stack();
        console.log( "done" );
    }

    function instruction_count() {
        // there would seem to be two ways to do this.
        //
        // METHOD #1: modify dispatch to increment the instruction count every time a
        // valid instruction is issued. this function would simply return the atomic
        // value of the count.
        //
        // METHOD #2: initialize a stack within the stack, modify dispatch to push
        // instructions to it, and count the length of that array?

    }

    function var_dump() {
        console.log("s = " +  s + ";");
        console.log("number_pushes = " +  number_pushes + ";");
        console.log("max_depth = " +  max_depth + ";");
        console.log("current_depth = " + current_depth + ";");
        console.log("instruction_ctr = " + instruction_ctr + ";");
        console.log("instructions = " + instructions + ";");
        console.log("history = " + history + ";");
        console.log("max_depth = " + max_depth + ";");
    }

    function dispatch( msg ) {
        instruction_ctr += 1;
        history.push(msg);
        switch ( msg ) {
            case "push":
                return push;
            case "pop":
                return pop();
            case "initialize":
                return initialize();
            case "print_statistics":
                print_statistics();
                break;
            case "instruction_count":
                return instruction_count;
            case "var_dump":
                return var_dump();

            default:
                instruction_ctr -= 1; // if the instruction isn't valid, decrement the counter
                alert( "Unknown request -- STACK" );

        }
    }

    return dispatch;
}

