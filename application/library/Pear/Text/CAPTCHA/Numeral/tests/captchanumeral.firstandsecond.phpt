--TEST--
Text_CAPTCHA_Numeral::getOperation(),
Text_CAPTCHA_Numeral::getFirstNumber(),
Text_CAPTCHA_Numeral::getSecondNumber()
--FILE--
<?php
    error_reporting(E_ALL & ~E_STRICT);
    require_once 'Text/CAPTCHA/Numeral.php';

    $num = new Text_CAPTCHA_Numeral;

    $op = $num->getOperation();

    $parts = split(" ", $op);

    $numberOne = $num->getFirstNumber();
    $numberTwo = $num->getSecondNumber();
    

    if ( ($numberOne == $parts[0]) && ($numberTwo == $parts[2])) {
        print "Result:    PASSED\n";
    } else {
        print "Result:    FAILED\n";
    }
?>
--EXPECT--
Result:    PASSED
