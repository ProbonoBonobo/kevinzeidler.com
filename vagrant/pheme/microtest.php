<?php
/**
 * Created by PhpStorm.
 * User: kevinzeidler
 * Date: 5/5/16
 * Time: 11:13 PM
 */



$expr1eval = "return (true) ? 1 : 0;";
$expr2eval = "break; echo true;";
$expectedResult = "1";

// argument to strcmp($1, $2) must be a string. the expressions above return ints, so perhaps try
// concatenating the return value to an empty string
$result1 = "";
$result2 = "";

$result1 += eval($expr1eval);
$result2 += eval("return true && " . $expr2eval);

echo "Comparing " . $result1 . " and " . $expectedResult . ": ";


if (strcmp($expectedResult, $result1) == 0) {
    echo "They're the same.\n";
} else {
    echo "They're different.\n";
}

echo "Comparing " . $result2 . " and " . $expectedResult . ": ";

if (strcmp($expectedResult, $result2) == 0) {
    echo "They're the same.";
} else {
    echo "They're different.";
}

echo "\n\nTest of bitwise comparison statements\n\n";
$exp1 = "'something' && 'foo'";
$exp2 = $exp1 . "==true";
$exp3 = "return (" . $exp2 . ") ? true : false";
$exp4 = "(" . $exp1 . ")==true" ;
$exp5 = "return " . $exp1;
$exp6 = "return ((\$x = 6)==6) ? true : false";
echo "Expr1: " . $exp1 . "\n";
echo "Result: " . eval($exp1 . ";") . "\n";
echo "Expr2: " . $exp2. "\n";
echo "Result: " . eval($exp2 . ";") . "\n";
echo "Expr3: " . $exp3 . "\n";
echo "Result: " . eval($exp3 . ";") . "\n";
echo "Expr4: " . $exp4 . "\n";
echo "Result: " . eval($exp4 . ";") . "\n";
echo "Expr5: " . $exp5 . "\n";
echo "Result: " . eval($exp5 . ";") . "\n";
echo "Expr6: " . $exp6 . "\n";
echo "Result: " . eval($exp6 . ";") . "\n";
///usr/local/Cellar/php56/5.6.21/bin/php /Users/kevinzeidler/rhizome/mohole/public/vagrant/pheme/microtest.php
//Comparing 1 and 1: They're different.
//Comparing 0 and 1: They're the same.
//Process finished with exit code 0
