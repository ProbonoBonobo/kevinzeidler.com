<?php


/**
 * Created by PhpStorm.
 * User: kevinzeidler
 * Date: 5/2/16
 * Time: 8:41 PM
 */




class Logic
{
    public $op;
    public $changed;



    function __construct()
    {
        print "Now booting. . .\n";
        $this->changed = false;
        $this->op = 0;

    }

    public static function opcode()
    {
        if ($op) {
            echo $op;
        } else {
            echo 'unset';
        }
    }

    public static function sayHello() {
        echo "Hello";
    }
}


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
//
