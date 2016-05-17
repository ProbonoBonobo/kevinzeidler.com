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

    function Semaphore($name, $depth=0, $isLocked=false, $r1="theEmptyString", $r2="theEmptyList", $r3="theEmptyList", $targ="0") {
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
                return (strcmp($this->get('regA'), "theEmptyString") !== 0) ? 0 : 1;
            case 'regB':
                return (strcmp($this->get('regB'), "theEmptyList") !== 0) ? 0 : 1;
            case 'regC':
                return (strcmp($this->get('regC'), "theEmptyList") !== 0) ? 0 : 1;
        }
    }

    public function isKnown($expr) {
        // Implement later (for safety)
        return true;
    }

    public function flush($reg) {
        if ($this->isLocked) {
            // Don't flush a locked register
            return false;
        } elseif (strcmp($this->get('currentTarget'), $reg) !== 0) {
            // Block incoming requests to flush any register besides the active one
            return false;
        } else {
             switch ($reg) {
                case 'regA':
                    $this->regA = "theEmptyString";
                    return true;
                case 'regB':
                    $this->regB = "theEmptyList";
                    return true;
                case 'regC':
                    // The third register is special. Don't flush it unless we're starting over, or reducing somehow.
                    return false;
                default:
                    // invalid arguments end up here
                    return false;
                }
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
                case 'currentTargetName':
                     if ($this->target == 0) {
                        return $ret .= "regA";
                     } elseif ($this->target == 1) {
                        return $ret .= "regB";
                     } elseif ($this->target == 2) {
                        return $ret .= "regC";
                     } else {
                        return "The target pointer is in a strange place.";
                     }
                case 'color':
                    if ($this->target == 0) {
                        return ($this->isLocked) ? 'green' : 'red';
                    } elseif ($this->target == 2 || 3) {
                        return ($this->isLocked) ? 'red' : 'green';
                    }
                case 'regA':
                    $ret .= $this->regA;
                    return $ret;
                case 'regB':
                     if (is_array($this->regB)) {
                        foreach($this->regB as $atom) {
                            $ret .= $atom;
                        }
                     } else {
                        $ret = 'theEmptyList';
                     }

                     return $ret;
//
                case 'regC':
                  if (is_array($this->regC)) {
                     foreach($this->regC as $maybeAtom) {
                         $ret .= $maybeAtom;
                         // implement recursion later
                        }
                     } else {
                         $ret = 'theEmptyList';
                     }
                     return $ret;

                default:
                    trigger_error($request . " is not a valid argument to get.\n", E_USER_WARNING);
            }
        return $ret;
    }

    private function curr() {
        // Private utility method for converting the 'target' index to a pointer
        $myVal = $this->val();
        $currentIndex = $this->target;
        return $myVal[$currentIndex];
    }


    public function lock() {
        if ($this->isLocked == true) {
            return true;
        } else {
            echo "current target is " . $this->get('currentTargetName') . "\n";
            echo "isEmpty: " . $this->isEmpty($this->get('currentTargetName')) . "\n";
            $this->isLocked = true;
            $this->target += 1;
            echo "*click*\n";
            return true;
        }
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

    private function atom2Action($atom,$args) {
        $dict = [
            "isJSON" => "json_decode(\$this->regB, 'TRUE');",
            "add" => "\$sum=0; foreach (\$args as \$number) { \$sum += \$number; } return \$sum;"];
        if (is_string($dict[$atom])) {
            $expr2eval = $dict[$atom];
            echo "Now evaluating: " . $expr2eval . "\n";
            $result = eval($dict[$atom]);
            echo "It returns " . $result . "!\n";
            return $result;
        } else {
            return false;
        }
    }

    private function autoEval()
    {
        return $this->atom2Action($this->regA, $this->regB);
    }

    private function isPrimitiveReducer($arg) {
        return ($arg == "equals") ? true : false;
    }






    public function push($val) {
        $status = $this->quickInspect();
        echo " Now attempting to push " . $val . " to " . $this->get('currentTargetName');
        echo " Current status is: " . $status . ". \n";
        if ($this->isLocked==true) {
            switch ($status) {
                case 0:
                    // precondition: semaphore is empty. target is 0.
                    $this->regA = $val;
                    return true;
                // postcondition: regA nonempty and unlocked. target is 1.

                case 1:

                    // precondition: regA is empty yet somehow locked
                    // postcondition: fatal error
                    return false;

                case 2:
                    // precondition: regA is nonempty and unlocked. target is 0.
                    $this->regA = $val;
                    return true;

                case 3:
                    // precondition: regA is nonempty. target is 1
                    $this->regB = array();
                    array_push($this->regB, $val);
                    return true;
                // postcondition: regA, regB are nonempty.

                case 4:
                    // precondition: impossible scenario
                    return false;
                case 5:
                    // precondition: impossible scenario
                    return false;
                case 6:
                    // precondition: regA, regB are nonempty and regB is unlocked. Target is 2.
                    //
                    // postcondition: I suppose in this case it may be best to keep pushing to the second register
                    //                to accommodate vararg functions

                    if ($this->isPrimitiveReducer($val)) {
                        $this->regC = [$this->atom2action($this->regA, $this->regB)];

                    }
                    array_push($this->regB, $val);
                    return true;


                case 7:
                    // precondition: regA, regB are nonempty, locked, and loaded. Target is 2.
                    //
                    //               This should probably trigger evaluation of the regA/regB pair.
                    //
                    //               The '$val' parameter *~might~* be useful as a reference to one or more constraints
                    //               the result must satisfy in order to proceed.
                    //
                    //               "proceed" in this context might mean several things. reduce?
                    //
                    $this->regC = array();
                    array_push($this->regC, $this->atom2Action($this->regA, $this->regB));
                    //              ($this->autoEval($val)==true) ? $this->isLocked = true : $this->isLocked = false;
                    return true;


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
                    return false;

                case 15:
                    // pc: all registers have stuff, and it's locked
                    return $this->regC;

            }


            } else {
                return false;
            }


        }




    public function probe() {
        // true if the target register is locked
        return !($this->isLocked == false);
    }
}

