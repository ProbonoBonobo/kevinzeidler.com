<?php

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

//    echo nl2br("Are strings empty? " . ((empty("")) ? 'Yes they are.' : '...Nope.') .  "\n" .
//        "Blank arrays have a count of " .
//        count(array()) . ".\n" .
//        "Blank arrays are arrays? " . is_array(array()) . "\n" .
//        "Identical strings are the same? (===) " .   ("nil" === "nil") . "\n" .
//        "With strcmp: " . strcmp("nil", "nil") . "\n");
//    $control = new Logic();
//
//    $A = new Semaphore("A");
//    $B = new Semaphore("B");




    function archive()
    {
//        $previousVersion = fopen('myScore.json','r');
//        $contents = fread($previousVersion, filesize($previousVersion));
//        $toJSON = json_decode($contents);

        date_default_timezone_set('America/Los_Angeles');
        $script_tz = date_default_timezone_get();

        $fp = 'myScore.json';
        $archiveAs = 'myScore.json';

        $timestamped = './archive/' . date('Y-m-d-h:m_') . $archiveAs;
//        $timestamped = printf($timestamped);
        copy($fp, $timestamped);

//        rename($archiveAs, $timestamped);

//        $f = fopen($fp,'r');
//        $content = fread($f, 10000);
//        fclose($f);
//
//        $toJSON = json_decode($content);
//        $newFP = var_dump($archiveAs);
//        $f = fopen($newFP, 'w');
//        fwrite($f, $toJSON);
//        fclose($f);
//


//
//        $archived = fopen($archiveAs, "w");
//        fwrite($archived,json_encode($contents));
//        fclose($archived);


    }


    function which($semaphore)
    {
        return $semaphore->ptr('ref');
    }

    function peek($semaphore)
    {
        return $semaphore->ptr('val');
    }

    function flatten($maybeNestedValue)
    {


        if (is_array($maybeNestedValue)) {
            if (count($maybeNestedValue) == 2) {
                $car = $maybeNestedValue[0];
                $car = $maybeNestedValue[1];
                // then keep recursing
                echo "(";
                if (empty($car)) {
                    echo "'blank' ";
                } else {
                    echo $car . " ";
                }
                if (count($cdr) == 2) {
                    echo " (" . flatten($cdr[2]) . ") ";
                } else {
                    echo " '() )";

                }
                echo ")";
            } elseif (count($maybeNestedValue) == 0) {
                // then we're looking at a tail but it's the empty tail.
                return "'()'";
            } else {
                // then we're looking at I dunno what.
                trigger_error("Not a pair: " . $maybeNestedValue, E_USER_ERROR);
            }
        }
        trigger_error("flatten called on an atomic value. Is the list structure alright?", E_USER_ERROR);
//        else {
//                if (empty($maybeNestedValue)) {
//                    // atomic null value
//                    echo "'blank'";
//                } else {
//                    // an atom
//                    echo " '" . $maybeNestedValue . "' ";
//                }
//        }
    }

    function look($semaphore)
    {
        $jsonizable = "{ 'name' : " . $semaphore->get('name') . "," .
            "  'depth' : " . $semaphore->get('depth') . "," .
            "  'color' : " . $semaphore->get('color') . "," .
            "  'regA' : " . $semaphore->get('regA') . "," .
            "  'regB' : " . $semaphore->get('regB') . "," .
            "  'regC' : " . $semaphore->get('regC') . " }";
        echo nl2br($jsonizable);
    }

    function bin($semaphor)
    {
        // returns the status code as a binary string
        $intCode = $semaphor->quickInspect();
        $converted = decbin($intCode);
        return $converted;
    }

    function statusCode($semaphore)
    {
        return $semaphore->quickInspect();
    }

    function isDynamic($result) {
        // Operations that have side effects are those that return an object (like $pheme)
        return ($result == "\$pheme") ? true : false;
    }

    $semA = new Semaphore("A");
    $semA->isKnown("true");

    $f = fopen('skeleton2.json', 'r');
    $content = fread($f, 100000);
    $jsonized = json_decode($content, "TRUE");
    foreach ($jsonized[0] as $field) {
        echo $field[0] . "\n";
        echo "Checking that '" . $field[1] . "' has been registered for execution...";
        echo "\n";
        if (true && $semA->isKnown($field[1])) {
            echo "Verified. \nExecuting '" . $field[1] . "'  \n";
            eval($field[1]);
            foreach ($field[2] as $rownum => $value) {
                $testIndex = $field[2][$rownum][0];
                $testDescription = $field[2][$rownum][1];
                $testExpression = $field[2][$rownum][2];
                $expectedResult = "" . $field[2][$rownum][3];
                if (isDynamic($expectedResult)) {
                    echo "" . $testExpression . " appears to be a non-static expression. I'll try to explode it";
                    eval($testExpression . ";");
                    $field[2][$rownum][5] = "N/A";
                    $field[2][$rownum][4] = var_dump($expectedResult);
                } else {


                // package the expression and expected value into a one-line macroexpr that always returns either true
                // or false, never anything else
                $binarySafeExecutable = "return ( (" . $testExpression . ")==" . $field[2][$rownum][3] . ") ? true : false;";
                $field[2][$rownum][5] = eval($binarySafeExecutable) ? "PASS" : "FAIL";
                $field[2][$rownum][4] = eval("return " . " $testExpression" . ";");
                $result = $field[2][$rownum][4];



                echo "\n";
                echo "Unmodified expression         : " . $testExpression . "\n";
                echo "Binary-safe macroexpansion    : " . $binarySafeExecutable . "\n";
                echo "Value required to return true : " . $expectedResult . "\n";
                echo "Value received                : " . $result . "\n";
                echo "Value to write                : " . $field[2][$rownum][5] . "\n";
//                $field[2][$rownum][5] = (strcmp($field[2][$rownum][4], $expectedResult) !== 0)) ? "FAIL" : "PASS";
//

            }
        }
        }

            $out = fopen('myScore.json', 'wb');
            fwrite($out, json_encode($field));
            fclose($out);

    }
//    }

}

//        foreach ($field[2] as $rownum=>$value) {
//            foreach $field[2][$rownum]
//            echo $value;




//        if (is_array($value)) {
//            $v = json_encode($value);
//            echo "The value: " . $v[2];
//        }
//    }

//            foreach ($value as $test => $row) {
//                $test = json_decode($row[]);
//
//                $expr2Eval = $row[2];
//                echo "Evaluating " . $row[2] . "\n";
//
//                $result = eval($expr2Eval);
//                echo "Result: " . $result;
//                $row[4] = $result;
//
//                return (strcmp($row[4], $row[3]) !== 0) ? $row[5] = "FAIL" : $row[5] = "PASS";

//                    echo ($res == $row[3]) ? "The test passed.\n" : "The test failed.\n";

//                    foreach ($row as $col) {
//
//                        $expected = $row[3];
//                        echo  "result of evaluation:";
//                        $result = eval($expr2Eval);
//
////
//
//                        if ($row[4] == $row[3]) {
//                            echo "Fail";
//                        } else{
//                            echo "Pass;";
//                        }


//                    }
//            }
//        } else {
//            echo "Field: ", $field, "; Value: ", $value;
//        }
//        $out = fopen('myScore.json', 'w');
//        fwrite($out, json_encode($jsonized));
//        fclose($out);
//    }


//        echo $jsonized["Tests"];
//        foreach ($phases->length as $phase) {
//            echo $phase;
//        }


//            $expr2Eval = $row[2];
//            $expected = $row[3];
//            $actual = $row[4];
//            $passOrFail = $row[5];
//
//            var_dump($expr2Eval);
//
//            $actual = eval($expr2Eval);
//            if ($actual == eval($expected)) {
//                $passOrFail = 'PASS';
//            } else {
//                $passOrFail = 'FAIL';
//            }


//





//    newTest($results, "Create a semaphore.", "\$pheme = new Semaphore('pheme')",  "(isSet(\$pheme)) ? 1 : 0;");
//
//    printf(json_encode($testResults));


//
//    echo "\n Initialized. \n";
//    echo "Destination of A: " . $A->get('currentTarget') . ". \n";
//    echo "Destination of B: " . $B->get('currentTarget') . ". \n";
//    echo "Value of A: " . $A->get('currentValue') . ". \n";
//    echo "Value of B: " . $A->get('currentValue') . ". \n";
//    echo "Why's regA fucked up " . strcmp($A->get('regA'), "theEmptyString") . "\n";
//    echo "What color A? " . strcmp("green", $A->get('color'));
//    echo "A's regA is empty? " . $A->isEmpty('regA') . ". \n";
//    echo "A's regB is empty? " . $A->isEmpty('regB') . ". \n";
//    echo "A's regC is empty? " . $A->isEmpty('regC') . ". \n";
//
//    echo var_dump($A);
////    echo "Initializing the pointers. . . \n";
////    echo $A->initPtr();
////    echo $B->initPtr();
//
//    $A->push("add");
//    $B->push("add");
//    echo "\nLet's lock them in there.\n";
//    echo "Before status (A B): (" . bin($A) . " " .  bin($B) . ")\n";
//    $A->lock();
//    $B->lock();
//    //echo $A->get('all');
//    echo "After status (A B): (" . bin($A) . " " . bin($B) . ")\n";
//    $B->push(5);
//    echo "Current status (A B): (" . bin($A) . " " . bin($B) . ")\n";
//    $B->push(6);
//    $B->push("kick");



    init();




?>