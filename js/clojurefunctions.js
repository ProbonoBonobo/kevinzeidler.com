/**
 * Created by kevinzeidler on 5/19/16.
 */


function mapcat (proc, arr1, arr2) {
    if (Array.isArray(arr1)) {
        if (Array.isArray(arr2)) {
            if (proc.length == 1) {
                ret = []
                arr1 = arr1.map(proc);
                arr2 = arr2.map(proc);
                for (var i = 0; i < Math.max(arr1.length, arr2.length); i++) {
                    ret.push(arr1[i]);
                    ret.push(arr2[i]);
                }
                return ret;
            }

            console.log("ERROR: Cannot apply " + proc + " -- mapcat expects 1-arity function")
        }
        console.log("ERROR: Not an array -- " + arr2);
    }
    console.log("ERROR: Not an array -- " + arr1);
}

function juxt (f1, f2) {
    return function (x) {
        return {f1: f1(x), f2: f2(x)};
    }
}

function identity (x) {
    return x;
}

function capitalize (x) {
    return x.toUpperCase();
}

//function partition_by (applyf, arr) {
//    var transformedArr, sortedArr;
//    transformedArr =  arr.map(function() { return 1; });
//    return transformedArr;
//}
arr = [1, 2, 3, 4, 5];
newArr = arr.map(function(x) { return x;});
console.log(newArr);

function partition(arr) {
    var curr, partitioned;
    while (arr.length > 2) {
        currPart = [];
        console.log(currPart);
    if (arr[0] == arr[1]) {l';;;;;;;;;;;'1  SZAQm ,/**/
        bv
            currPart.push(arr.pop());
        bv
            currPart.push(arr.pop());
            console.log(currPart);

        } else {
            currPart.push(arr.pop());
            return [currPart, partition(arr)];
        }
        return currPart;
    }



}


function partition_by(f, arr) {
    var newArr;
    newArr = arr.map(function(x) { return f(x); });
    newArr = newArr.sort();
    return partition(newArr);
}

console.log(partition_by(identity, [1, 2, 3, 4, 5, 1, 2, 1, 1, 1, 3, 3, 2, 1, 1, 2, 2, 5]));
console.log(partition([1,1,1,1,12,2,2,3,2,1,2,1,2,1,21,1]))


console.log(mapcat(function(x) { return x.toUpperCase(); }, ["a","c","e","h"], ["b", "d", "f", "i"] ));
console.log(mapcat(juxt(identity, capitalize), ["a","c","e","h"], ["b", "d", "f", "i"] ));