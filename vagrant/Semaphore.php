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
    private $regA, $regB, $regC;
    private $isLocked;

    function Semaphore($r1, $r2, $r3, $targ='0', $isLocked=false) {
        echo "Constructing semaphore. . .\n";

        $this->regA = $r1;
        $this->regB = $r2;
        $this->regC = $r3;



        $this->target = 0;
        $this->isLocked = $isLocked;


    }



    private function val() {
        $ret = array($this->regA, $this->regB, $this->regC);
        return $ret;
    }

    public function get($request) {

            switch ($request) {
                case 'currentValue':
                    $ret = $this->curr();
                    return $ret;
                case 'currentTarget' :
                    return $this->target;
                case 'all':
                    return $this->val();
                case 'color':
                    return ($this->isLocked) ? 'red' : 'green';
                case 'regA' || 0:
                    return $this->regA;
                case 'regB' || 1:
                    return $this->regB;
                case 'regC' || 2:
                    return $this->regC;
                default:
                    echo $request . " is not a valid argument to get.\n";
            }
    }

    private function curr() {
        // Private utility method for converting the 'target' index to a pointer
        $myVal = $this->val();
        return $myVal[$this->target];
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
                $ret = array(empty($this->regA), empty($this->regB), empty($this->regC));

        }
    }
    public function quickInspect() {
        // Array syntax in PHP is stupid awkward. This returns an integer that can be converted to binary
        // for a quick inspection which registers have the property, e.g. I return 8->decbin(4)->100->
        // notempty, empty, empty
        //
        $ret = 0;
        $ret += (!($this->isLocked)) ? 0 : 1;
        $ret += (empty($this->regA)) ? 0 : 2;
        $ret += (empty($this->regB)) ? 0 : 4;
        $ret += (empty($this->regC)) ? 0 : 8;
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
                $this-> target += 1;
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
                echo "\n\nNot yet implemented.\n";
                echo "But here's what it would look like if we added them to an array.\n";
                $this->regC =  array('car' => $this->regA, 'cdr' => $this->regB);
                echo $this->get($this->regC) .  "\nYou could explore the status codes of impossible cases\n";
                echo "that would be uniquely triggered, should you hypothetically elect to flush regA and regB right now.\n\n";
                echo "Maybe the '", $val, "' parameter could be a test of regC's sanity.\n";
                $success = true;
                break;
            case 8:
                // precondition: only regC is nonempty. It is also unlocked.
                echo "Not yet implemented: Case 10\n";
                break;
            case 9:
                // precondition: only regC is nonempty. It is locked. regA and regB have both been flushed.
                echo "Not yet implemented: Case 9\n";
                break;

            case 15:
                echo "Not yet implemented\n";
                echo "This is the case where regA, regB, and regC are not only nonempty, but locked. \n";
                echo "This likely means we want to keep regC and return the pointer to regA. \n";
                $this->regB=null;
                $this->regA=null;
                $this->target=0;
                break;





        }
        return $success;

    }

    public function probe() {
        // true if the target register is locked
        return !($this->isLocked == false);
    }
}

