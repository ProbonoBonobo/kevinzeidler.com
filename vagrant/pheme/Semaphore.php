<?php

/**
 * Created by PhpStorm.
 * User: kevinzeidler
 * Date: 5/3/16
 * Time: 11:22 AM
 */
class Semaphore
{

    public $target;
    public $regA, $regB, $regC;
    private $isLocked;
    public $name;
    public $depth;
    public $theEmptyString, $theEmptyList;
    public $ret;

    function Semaphore($name, $depth=0, $isLocked=false, $r1="theEmptyString", $r2="theEmptyList", $r3="theEmptyList", $targ=0) {
//        echo "Constructing semaphore '" . $name . "' at depth " . $depth . ". . . \n";
            $this->name = $name;
            $this->depth = $depth;
            $this->isLocked = $isLocked;
            $this->regA = $r1;
            $this->regB = $r2;
            $this->regC = $r3;
            $this->target = $targ;


    }

    public function isEmpty($reg) {
        // we define emptiness a little differently than PHP
        switch ($reg) {
            case 'regA':
                // Interesting. I think this HAS to be resolved to either true or false in the context of
                // this function. If it isn't, it doesn't get resolved in the macro body either
                return (strcmp($this->get('regA'), "theEmptyString") !== 0) ? 0: 1;
            case 'regB':
                return (strcmp($this->get('regB'), "theEmptyList") !== 0) ? 0 : 1;
            case 'regC':
                return (strcmp($this->get('regC'), "theEmptyList") !== 0) ? 0 : 1;
            }
        }



    private function val() {
        $ret = array("0" => $this->regA, "1" => $this->regB, "2" => $this->regC);
        return $ret;
    }

    public function get($request) {
        $ret = "";
            switch ($request) {
                case 'name' :
                    $ret = $this->name;
                    return $ret;
                case 'depth':
                    return "root";

                case 'currentValue':
                    $ret = $this->curr();
                    return $ret;
                case 'currentTarget':
                    $ret = $this->target;
                    return $ret;
                case 'color':
                    return ($this->isLocked) ? 'red' : 'green';
                case 'regA':
                    $ret .= $this->regA;
                    return $ret;
                case 'regB':
//                    $ret = "";
//                    if (is_array($this->regB) && count($this->regB) > 0) {
//                        $ret .= "( ";
//                        foreach ($this->regB as $datum) {
//                            $ret .= " " . $datum->valueOf . " ";
//                        }
//                        $ret .= ") ";
//                        return $ret;
//                    } else {
                        $ret .= $this->regB;
                        return $ret;
//
                case 'regC':
                    $ret = "";
//                    if (is_array($this->regC) && count($this->regC) > 0) {
//                        $ret .= "( ";
//                        foreach ($this->regC as $datum) {
//                            $ret .= " " . $datum->valueOf . " ";
//                        }
//                        $ret .= ") ";
//                        return $ret;
//                    } else {
                        $ret .= $this->regC;
                        return $ret;

                default:
                    trigger_error($request . " is not a valid argument to get.\n", E_USER_WARNING);
            }
        return ret;
    }

    private function curr() {
        // Private utility method for converting the 'target' index to a pointer
        $myVal = $this->val();
        $currentIndex = $this->target;
        return $myVal[$currentIndex];
    }


    public function lock() {
        if ($this->isLocked == true) {
            echo "Can't lock. Reason: Already locked.\n";
            return false;
        }
        $this->isLocked = true;
        echo "*click*\n";
        return true;
    }


    public function inspect($property) {
        switch($property) {
            case 'empty':
                $ret = array(isEmpty($this->regA), isEmpty($this->regB), isEmpty($this->regC));

        }
    }
    public function quickInspect() {
        // Array syntax in PHP is stupid awkward. This returns an integer that can be converted to binary
        // for a quick inspection which registers have the property, e.g. I return 8->decbin(4)->100->
        // notempty, empty, empty
        //
        $ret = 0;
        $ret += ($this->isLocked) ? 1 : 0;
        $ret += ($this->isEmpty('regA')) ? 0 : 2;
        $ret += ($this->isEmpty('regB')) ? 0 : 4;
        $ret += ($this->isEmpty('regC')) ? 0 : 8;
        return $ret;
    }





    public function push($val) {
        $status = $this->quickInspect();
        echo " Now attempting to push " . $val . " to " . $this->get('currentTarget');
        echo " Current status is: " . $status . ". \n";
        $success = false;
        switch ($status) {
           // The following status codes can't currently be true: 1, 2, 3, 4, 5, 6, 7


            case 0:
                // precondition: semaphore is empty. target is 0.
                $this->regA = $val;
                $this->target += 1;
                $success = true;
                break;
                // postcondition: regA nonempty and unlocked. target is 1.

            case 1:
                echo "Error: Impossible scenario.\n";
                echo "Reason: This is the impossible case in which regA is locked, and yet it has no contents. Double check\n";
                echo "that your logic is correct.";
                // precondition: regA is empty yet somehow locked
                // postcondition: fatal error
                break;

            case 2:
                // precondition: regA is nonempty and unlocked. target is 0.
                echo "Error: Couldn't push.\n";
                echo "Reason: There's something in regA you haven't saved yet. Save or flush regA.\n";
                break;
                // postcondition: no change. target is 0.

            case 3:
                // precondition: regA is nonempty and locked. target is 1
                $this->regB = $val;
                $this->target += 1;
                $this->isLocked = false;
                $success=true;
                // postcondition: regA, regB are nonempty. regB is unlocked. target is 2.

            case 4:
                // precondition: impossible scenario
                echo "Error: Impossible scenario.\n";
                echo "Reason: This is the impossible case in which regB is nonempty, but the value of regA has\n";
                echo "somehow been flushed.\n";
                break;
            case 5:
                // precondition: impossible scenario
                echo "Error: Impossible scenario.\n";
                echo "Reason: This is the impossible case in which regB is nonempty, but the value of regA has\n";
                echo "somehow been flushed. Additionally, regB is locked.\n";
            case 6:
                // precondition: regA, regB are nonempty and regB is unlocked. Target is 2.
                echo "Error: Couldn't push.\n";
                echo "Reason: there's something in regB you haven't saved yet. Lock or flush it.";
                // postcondition: no change. Target is 2.
                break;
            case 7:
                // precondition: regA, regB are nonempty, locked, and loaded. Target is 3.
                //
                //               This should probably trigger evaluation of the regA/regB pair.
                //
                //               The '$val' parameter *~might~* be useful as a reference to one or more constraints
                //               the result must satisfy in order to proceed.
                //
                //               "proceed" in this context might mean several things. reduce
                //
                $env = array();
               $this->regC = array_push($env, $this->regC);
               $this->regC;
                break;
            case 8:
                // precondition: only regC is nonempty. It is also unlocked.
                echo "Not yet implemented: Case 8\n";
                break;
            case 9:
                // precondition: only regC is nonempty. It is locked.
                echo "Not yet implemented: Case 9\n";
                break;
            case 10:
                // pc: regC and regA nonempty and unlocked
                echo "Not yet implemented: Case 10\n";
                break;
            case 11:
                // pc: regC and regA nonempty and locked
                echo "Not yet implemented: Case 11\n";
                break;
            case 12:
                // pc: regC and regB nonempty and unlocked
                echo "Not yet implemented: Case 12\n";
                break;
            case 13:
                // pc: regC and regB nonempty and locked
                echo "Not yet implemented: Case 13\n";
                break;
            case 14:
                // pc: all registers have stuff, but it's currently unlocked
                echo "Not yet implemented: Case 14\n";

            case 15:
                // pc: all registers have stuff, and it's locked
                echo "Not yet implemented: Case 15\n";
                break;





        }
        return $success;

    }

    public function probe() {
        // true if the target register is locked
        return !($this->isLocked == false);
    }
}

