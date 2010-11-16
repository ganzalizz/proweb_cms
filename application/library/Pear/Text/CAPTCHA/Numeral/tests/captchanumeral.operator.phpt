--TEST--
Text_CAPTCHA_Numeral::getOperation()
--FILE--
<?php
    error_reporting(E_ALL & ~E_STRICT);
    require_once 'Text/CAPTCHA/Numeral.php';

    $num = new Text_CAPTCHA_Numeral;

    $op = $num->getOperation();

    $parts = split(" ", $op);

    $numberOne = $num->getFirstNumber();
    $numberTwo = $num->getSecondNumber();
    
    $textOne = 'First number';
    $textTwo = 'Second number';
    
    //print "$op\n";
    
    if ($parts[1] == '-') {
        //print "Operator: minus, so first numbers has to be higher than second\n";
        //print "Number 1: $numberOne\nNumber 2: $numberTwo\n";
        print "Result: ";
        
        if ($numberOne >= $numberTwo) {
            print "   PASSED\n";
        } else {
            print "   FAILED\n";
        }
    } else {
       print "Result:    PASSED\n";
    } 
?>
--EXPECT--
Result:    PASSED

