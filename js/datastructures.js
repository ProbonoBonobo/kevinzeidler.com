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