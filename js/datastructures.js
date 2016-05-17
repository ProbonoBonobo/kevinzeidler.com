/**
 * Created by kevinzeidler on 5/16/16.
 */


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