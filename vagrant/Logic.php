<?php

/**
 * Created by PhpStorm.
 * User: kevinzeidler
 * Date: 5/2/16
 * Time: 8:41 PM
 */

$isGreen;


class Logic
{
    private $changed = false;
    private $opcode = 0;


    function __construct() {
        print "Now booting. . .\n";
    }

    public static function opcode() {
        if ($opcode) {
            echo $opcode;
        } else {
            echo 'unset';
        }
    }

}



class Semaphore {
    private $target = $activeRegister;
    private $isLocked = false;

    function __construct($activeRegister) {

    }

    function static function currentTarget() {
        return $target;
    }

    public static function probe() {
        // true if the target register is locked
        return !($isLocked == false);
    }
}

$semaphoreA = new Semaphore($r1);
$semaphoreB = new Semaphore($r2);

$control = new Logic();

//class OtherSubClass extends BaseClass {
//    // inherits BaseClass's constructor
//}
//
//// In BaseClass constructor
//$obj = new BaseClass();
//
//// In BaseClass constructor
//// In SubClass constructor
//$obj = new SubClass();
//
//// In BaseClass constructor
//$obj = new OtherSubClass();
//?>
