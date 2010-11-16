<?php
require_once 'Text/CAPTCHA/Numeral/interfaces/NumeralInterface.php';
// {{{ Class Text_CAPTCHA_Numeral
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1998-2007 David Coallier                               |
// | All rights reserved.                                                 |
// +----------------------------------------------------------------------+
// |                                                                      |
// | Redistribution and use in source and binary forms, with or without   |
// | modification, are permitted provided that the following conditions   |
// | are met:                                                             |
// |                                                                      |
// | Redistributions of source code must retain the above copyright       |
// | notice, this list of conditions and the following disclaimer.        |
// |                                                                      |
// | Redistributions in binary form must reproduce the above copyright    |
// | notice, this list of conditions and the following disclaimer in the  |
// | documentation and/or other materials provided with the distribution. |
// |                                                                      |
// | Neither the name of David Coallier nor the names of his contributors |
// | may be used to endorse                                               |
// | or promote products derived from this software without specific prior|
// | written permission.                                                  |
// |                                                                      |
// | THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS  |
// | "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT    |
// | LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS    |
// | FOR A PARTICULAR PURPOSE ARE DISCLAIMED.  IN NO EVENT SHALL THE      |
// | REGENTS OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,          |
// | INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, |
// | BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS|
// |  OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED  |
// | AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT          |
// | LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY|
// | WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE          |
// | POSSIBILITY OF SUCH DAMAGE.                                          |
// +----------------------------------------------------------------------+
// | Author: David Coallier <davidc@agoraproduction.com>                  |
// +----------------------------------------------------------------------+
//
/**
 * Class used for numeral captchas
 *
 * This class is intended to be used to generate
 * numeral captchas as such as:
 * Example:
 *  Give me the answer to "54 + 2" to prove that you are human.
 *
 * @author   David Coallier <davidc@agoraproduction.com>
 * @package  Text_CAPTCHA_Numeral
 * @category CAPTCHA
 */
class Text_CAPTCHA_Numeral implements Text_CAPTCHA_Numeral_Interface
{
    // {{{ Variables
    /**
     * Minimum range value
     *
     * This variable holds the minimum range value
     * default set to "1"
     *
     * @access private
     * @var    integer $minValue The minimum range value
     */
    private $minValue = '1';
   
    /**
     * Maximum range value
     *
     * This variable holds the maximum range value
     * default set to "50"
     *
     * @access private
     * @var    integer $maxValue The maximum value of the number range
     */
    private $maxValue = '50';
   
    /**
     * Operators
     *
     * The valid operators to use
     * in the numeral captcha. We could
     * use / and * but not yet.
     *
     * @access private
     * @var    array $operators The operations for the captcha
     */
    private $operators = array();
   
    /**
     * Operator to use
     *
     * This variable is basically the operation
     * that we're going to be using in the
     * numeral captcha we are about to generate.
     *
     * @access private
     * @var    string $operator The operation's operator
     */
    private  $operator = '';
   
    /**
     * Mathematical Operation
     *
     * This is the mathematical operation
     * that we are displaying to the user.
     *
     * @access private
     * @var    string $operation The math operation
     */
    private $operation = '';
   
    /**
     * First number of the operation
     *
     * This variable holds the first number
     * of the numeral operation we are about
     * to generate.
     *
     * @access private
     * @var    integer $firstNumber The first number of the operation
     */
    private $firstNumber = '';
   
    /**
     * Second Number of the operation
     *
     * This variable holds the value of the
     * second variable of the operation we are
     * about to generate for the captcha.
     *
     * @access private
     * @var    integer $secondNumber The second number of the operation     
     */
    private $secondNumber = '';
   
    /**
     * The operation answer
     *
     * The answer to the numeral operation
     * we are about to do.
     *
     * @access private
     * @var    integer $answer The mathematical operation answer value.
     */
    private $answer;
 
    /**
     * A constant that indicates the complexity of mathematical operations
     *
     * @access public
     *
     */
    const TEXT_CAPTCHA_NUMERAL_COMPLEXITY_ELEMENTARY = 1;
    
     
    /**
     * A constant that indicates the complexity of mathematical operations
     *
     * @access public
     *
     */
    const TEXT_CAPTCHA_NUMERAL_COMPLEXITY_HIGH_SCHOOL = 2;
    
     
    /**
     * A constant that indicates the complexity of mathematical operations
     *
     * @access public
     *
     */
    const TEXT_CAPTCHA_NUMERAL_COMPLEXITY_UNIVERSITY = 4;
 
 
 
    // }}}
    // {{{ Constructor
    /**
     * Constructor with different levels of mathematical operations sets
     *
     * @param constant $complexityType
     */
    public function __construct($complexityType = self::TEXT_CAPTCHA_NUMERAL_COMPLEXITY_ELEMENTARY)
    {
        
        switch ($complexityType) {
                case 2:
                     $this->operators = array('+', '-', '*');
                     break;
                case 4:
                     $this->operators = array('+', '-', '*', '%', '/');
                     break;
                case 1:
                default:
                     $this->operators = array('-', '+');
                     break;
        }
        
        $this->generateFirstNumber();
        $this->generateSecondNumber();
        $this->generateOperator();
        $this->generateOperation();
    }
    // }}}
    // {{{ private function setRangeMinimum
    /**
     * Set Range Minimum value
     *
     * This function give the developer the ability
     * to set the range minimum value so the operations
     * can be bigger, smaller, etc.
     *
     * @access private
     * @param  integer $minValue The minimum value
     */
    private function setRangeMinimum($minValue = '1')
    {
        $this->minValue = (int)$minValue;
    }
    // }}}
    // {{{ private function generateFirstNumber
    /**
     * Sets the first number
     *
     * This function sets the first number
     * of the operation by calling the generateNumber
     * function that generates a random number.
     *
     * @access private
     * @see    $this->firstNumber, $this->generateNumber
     */
    private function generateFirstNumber()
    {
        $this->setFirstNumber($this->generateNumber());
    }
    // }}}
    // {{{ private function generateSecondNumber
    /**
     * Sets second number
     *
     * This function sets the second number of the
     * operation by calling generateNumber()
     *
     * @access private
     * @see    $this->secondNumber, $this->generateNumber()
     */
    private function generateSecondNumber()
    {
        $this->setSecondNumber($this->generateNumber());
    }
    // }}}
    // {{{ private function generateOperator
    /**
     * Sets the operation operator
     *
     * This function sets the operation operator by
     * getting the array value of an array_rand() of
     * the $this->operators() array.
     *
     * @access private
     * @see    $this->operators, $this->operator
     */
    private function generateOperator()
    {
        $this->operator = $this->operators[array_rand($this->operators)];
    }
    // }}}
    // {{{ private function setAnswer
    /**
     * Sets the answer value
     *
     * This function will accept the parameters which is
     * basically the result of the function we have done
     * and it will set $this->answer with it.
     *
     * @access private
     * @param  integer $answerValue The answer value
     * @see    $this->answer
     */
    private function setAnswer($answerValue)
    {  
        $this->answer = $answerValue;
        return $this;
    }
    // }}}
    // {{{ private function setFirstNumber
    /**
     * Set First number
     *
     * This function sets the first number
     * to the value passed to the function
     *
     * @access private
     * @param  integer $value The first number value.
     * @return object $this  The self object
     */
    private function setFirstNumber($value)
    {
        $this->firstNumber = (int)$value;
        return $this;
    }
    // }}}
    // {{{ private function setSecondNumber
    /**
     * Sets the second number
     *
     * This function sets the second number
     * with the value passed to it.
     *
     * @access private
     * @param  integer $value The second number new value.
     * @return object  $this  The self object
     */
    private function setSecondNumber($value)
    {
        $this->secondNumber = (int)$value;
        return $this;
    }
    // }}}
    // {{{ private function setOperation
    /**
     * Set operation
     *
     * This variable sets the operation variable
     * by taking the firstNumber, secondNumber and operator
     *
     * @access private
     * @see    $this->operation
     */
    private function setOperation()
    {
        $this->operation = $this->getFirstNumber() . ' ' .
                           $this->operator . ' ' .
                           $this->getSecondNumber();
        return $this;
    }
    // }}}
    // {{{ private function generateNumber
    /**
     * Generate a number
     *
     * This function takes the parameters that are in
     * the $this->maxValue and $this->minValue and get
     * the random number from them using mt_rand()
     *
     * @access private
     * @return integer Random value between minValue and maxValue
     */
    private function generateNumber()
    {
        return mt_rand($this->minValue, $this->maxValue);
    }
    // }}}
    // {{{ private function doAdd
    /**
     * Adds values
     *
     * This function will add the firstNumber and the
     * secondNumber value and then call setAnswer to
     * set the answer value.
     *
     * @access private
     * @see    $this->firstNumber, $this->secondNumber, $this->setAnswer()
     */
    private function doAdd()
    {
        $answer = $this->getFirstNumber() + $this->getSecondNumber();
        $this->setAnswer($answer);
    }
    // }}} 
    // {{{ private function doMultiplication
    /**
     * Do Multiplication
     * 
     * This method will multiply two numbers
     * 
     * @access private
     * @see $this->firstNumber, $this->secondNumber, $this->setAnswer
     * 
     */
    private function doMultiplication()
    {
        $this->setAnswer($this->getFirstNumber() * $this->getSecondNumber());
    }
    // }}}
    // {{{ private function doDivision
    /**
     * Do Division
     *
     * This function executes a division based on the two
     * numbers.
     *
     * @param integer $firstNumber The first number of the operation.
     *                             This is by default set to null.
     *
     * @param integer $secondNumber The second number of the operation
     *                              This is by default set to null.
     *
     */
    private function doDivision($firstNumber = null, $secondNumber = null)
    {
        if (is_null($firstNumber)) {
            $firstNumber = $this->getFirstNumber();
        }

        if (is_null($secondNumber)) {
            $secondNumber = $this->getSecondNumber();
        }

        if ($secondNumber == 0) {
            ++$secondNumber;
            $this->doDivision($firstNumber, $secondNumber);
            return;
        }
        
        if ($firstNumber % $secondNumber != 0) {
            --$firstNumber;
            --$secondNumber;
            
            $this->doDivision($firstNumber, $secondNumber);
            return;
        }

        $this->setFirstNumber($firstNumber)
             ->setSecondNumber($secondNumber)
             ->setOperation()
             ->setAnswer($this->getFirstNumber() / $this->getSecondNumber());
    }
    // }}}
    // {{{ private function doModulus
    /**
     * Do modulus
     * 
     * This method will do a modulus operation between two numbers
     *
     * 
     * @access private
     * @see $this->firstNumber, $this->secondNumber, $this->setAnswer()
     * 
     */
    private function doModulus()
    {
       $this->setAnswer($this->getFirstNumber() % $this->getSecondNumber());
    }
    // }}}
    // {{{ private function doSubstract
    /**
     * Does a substract on the values
     *
     * This function executes a substraction on the firstNumber
     * and the secondNumber to then call $this->setAnswer to set
     * the answer value.
     *
     * If the firstnumber value is smaller than the secondnumber value
     * then we regenerate the first number and regenerate the operation.
     *
     * @access private
     * @see    $this->firstNumber, $this->secondNumber, $this->setAnswer()
     */
    private function doSubstract()
    {
         $first  = $this->getFirstNumber();
         $second = $this->getSecondNumber();
 
        /**
         * Check if firstNumber is smaller than secondNumber
         */
        if ($first < $second) {
            $this->setFirstNumber($second)
                 ->setSecondNumber($first)
                 ->setOperation();
        }

        $answer = $this->getFirstNumber() - $this->getSecondNumber();
        $this->setAnswer($answer);
    }
    // }}}
    // {{{ private function generateOperation
    /**
     * Generate the operation
     *
     * This function will call the setOperation() function
     * to set the operation string that will be called
     * to display the operation, and call the function necessary
     * depending on which operation is set by this->operator.
     *
     * @access private
     * @see    $this->setOperation(), $this->operator
     */
    private function generateOperation()
    {
        $this->setOperation();
                          
        switch ($this->operator) {
        case '+':
            $this->doAdd();
            break;
        case '-':
            $this->doSubstract();
            break;
        case '*':
            $this->doMultiplication();
            break;
        case '%':
            $this->doModulus();
            break;
        case '/':
            $this->doDivision();
            break;
        default:
            $this->doAdd();
            break;
        }
    }
    // }}}
    // {{{ public function getOperation
    /**
     * Get operation
     *
     * This function will get the operation
     * string from $this->operation
     *
     * @access public
     * @return string The operation String
     */
    public function getOperation()
    {
        return $this->operation;
    }
    // }}}
    // {{{ public function getAnswer
    /**
     * Get the answer value
     *
     * This function will retrieve the answer
     * value from this->answer and return it so
     * we can then display it to the user.
     *
     * @access public
     * @return string The operation answer value.
     */
    public function getAnswer()
    {
        return $this->answer;
    }
    // }}}
    // {{{ public function getFirstNumber
    /**
     * Get the first number
     *
     * This function will get the first number
     * value from $this->firstNumber
     *
     * @access public
     * @return integer $this->firstNumber The firstNumber
     */
    public function getFirstNumber()
    {
        return $this->firstNumber;
    }
    // }}}
    // {{{ public function getSecondNumber
    /**
     * Get the second number value
     *
     * This function will return the second number value
     *
     * @access public
     * @return integer $this->secondNumber The second number
     */
    public function getSecondNumber()
    {
        return $this->secondNumber;
    }
    // }}}
}
// }}}
?>
