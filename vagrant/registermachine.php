<?php
header('Content-type: text/plain');
/**
 * Created by PhpStorm.
 * User: kevinzeidler
 * Date: 5/2/16
 * Time: 1:49 PM
 */

include 'Logic.php';
include 'Semaphore.php';



function init()
{
    $control = new Logic();
    $A = new Semaphore(null, null, null);
    $B = new Semaphore("", array(), array());
    

    function which($semaphore) {
        return $semaphore->ptr('ref');
    }
    function peek($semaphore) {
        return $semaphore->ptr('val');
    }

    function flatten($maybeNestedValue) {
        if (is_array($maybeNestedValue)) {
            echo "(";
            foreach ($maybeNestedValue as $v) {
                if (is_array($v)) {
                    echo " (" . flatten($v) . ") ";
                } else {
                    echo " " . $v . " ";
                }
            }
            echo ") ";
        }  else {
            echo " " . $maybeNestedValue . " ";
        }
    }
    function look($semaphore) {
        echo "Color: " . $semaphore->get('color');
        echo "regA: " . $semaphore->get('regA');
        echo "regB: " . flatten($semaphore->get('regB'));
        echo "regC: " . flatten($semaphore->get('regC'));
    }

    function bin($semaphor) {
        // returns the status code as a binary string
        $intCode = $semaphor->quickInspect();
        $converted = bindec(decbin($intCode));
        return $converted;
    }

    function statusCode($semaphore)
    {
        return $semaphore->quickInspect();
    }


    echo "\n Initialized. \n";
    echo "Destination of A: " . $A->get('currentTarget') . ". \n";
    echo "Destination of B: " . $B->get('currentTarget') . ". \n";
    echo "Value of A: " . look($A) . ". \n";
    echo "Value of B: " . look($B) . ". \n";
    echo look($A);
    echo look($B);
//    echo "Initializing the pointers. . . \n";
//    echo $A->initPtr();
//    echo $B->initPtr();
    echo "Register A will probably be an atomic value that is the output of some other source. \n";
    $A->push("add");
    $B->push("add");
    echo "\nLet's lock them in there.\n";
    echo "Current status (A B): (" . bin($A) . bin($B) . ")\n";
    $A->lock();
    $B->lock();
    //echo $A->get('all');
    echo "Current status (A B): (" . bin($A) . bin($B) . ")\n";
    echo "Try pushing an array to A and atomic values to B \n";
    $A->push(array(5,6));
    $B->push(5);
    $C->push(6);
//    $B->push("kick");


}
init();




?>