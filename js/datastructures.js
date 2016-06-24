/**
 * Created by kevinzeidler on 5/16/16.
 */


// linked list

function LinkedList() {
    var myLL, curr;
    myLL =
        make_list();
    myLL( "initialize" );
    return myLL;

    function make_list( args ) {
        var lst, size, first, last;
        size  =
            0;
        last  =
            null;
        first =
            new Node( null, null );

        function Node( prev, val ) {
            var prevPtr, nextPtr, myVal;

            function initialize() {
                {
                    myVal   =
                        val;
                    prevPtr =
                        prev;
                    nextPtr =
                        last;
                    self    =
                        this;
                    return self;
                }

                function getVal() {
                    return myVal;
                }

                function getNext() {
                    return next;
                }

                function getPrev() {
                    return prev;
                }

                function print_statistics() {
                    console.log( "Previous node is . : " + prevPtr + "\n" );
                    console.log( "My value is . . .  : " + myVal + "\n" );
                    console.log( "Next node is . . . : " + nextPtr + "\n" );
                }

                function dispatch( msg ) {
                    switch ( msg ) {
                        case ("initialize"
                        ):
                            return initialize();
                        case ("getVal"
                        ):
                            return getVal();
                        case("getNext"
                        ):
                            return getNext();
                        case("getPrev"
                        ):
                            return getPrev();
                        case("print_statistics"
                        ):
                            print_statistics();

                    }
                }
            }

            return dispatch;


        }

        function getSize() {
            return size;
        }

        function getFirst() {
            return first;
        }

        function dispatch( msg ) {
            switch ( msg ) {
                case ("initialize"
                ):
                    return make_list();
                case ("first"
                ):
                    return getFirst()("getVal");
                case ("getSize"):
                    return getSize();
            }
        }

        return dispatch;
    }

}

LL = new LinkedList();
console.log(LL("getSize"));
console.log(LL("first"));








function RLE(input) {
    var ctr, curr, last;
    if ( input ) {
        ctr =
            0;
        while ( input[ 0 ]) {
            last = input[0];
            if ( curr ) {
                if ( curr === input[ 0 ] ) {
                    ctr +=
                        1;
                    input = input.substr(1);
                }
                else {
                    return curr + ctr + RLE( input );
                }
            }
            else {
                curr =
                    input[ 0 ];
            }
        }
        return last + ctr;
    }
    return "";
}

console.log(RLE("mississippi nipple bottom"));

var testInputs =  [[10,10], [3,-3,0], [-6,10,1,-19,1,5,-10,-27,6,2,-10,2,1,0,3,-1,-5,-10,3,1,-1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1], [-1, 3, -4, 5, 1, -6, 2, 1], [-2147483648],  [500, 1, -2, -1, 2], [-1,1,1], [1, 2, -3, 0],  [0], [100]];
function cumulativeSum(arr) {
    if (Array.isArray(arr) && arr.length > 0){
        return arr.reduce(function(total, num) { return total + num; });
    }
    return 0;
}

function partitionAt(arr, index) {
    var arrCopy = arr;
    return [arrCopy.slice(0,index), arrCopy.slice(index+1)];
}

function getEquilibriumIndices(arr) {
    var eqIndices = [];
    for (var i = 0; i < arr.length; i++) {
        var splitArr, left, right;
        splitArr = partitionAt(arr, i);
        left = splitArr[0];
        right = splitArr[1];
        console.log("Evaluating " + splitArr);
        console.log("Left side: " + left);
        console.log("right side: " + right);
        console.log("Left side (Reduced): " + cumulativeSum(left));
        console.log("Right side: " + cumulativeSum(right));
        if (cumulativeSum(splitArr[0]) === cumulativeSum(splitArr[1])) {
            return i;
        }
    }
    return eqIndices;
}
console.log("Testing cumulativeSum(arr)...");
console.log("Does cumulativeSum([1,2,3,4,5,6])' return 21?");
(cumulativeSum([1,2,3,4,5,6]) === 21) ? console.log("It does! Assertion passed") :
console.log("Nope! Check your logic.");

results = new Map();

testInputs.map(function(x) {
    results.set(x, getEquilibriumIndices((x)));
});

console.log(results);
