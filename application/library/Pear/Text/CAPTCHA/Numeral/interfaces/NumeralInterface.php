<?php
// {{{ Disclaimer
// +----------------------------------------------------------------------+
// | PHP version 5                                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 1998-2006 David Coallier                               | 
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
// }}}
// {{{ Text_CAPTCHA_Numeral_Interface
/**
 * Text CAPTCHA Numeral Interface
 *
 * This is the textcaptchanumeral interface
 * that is called everytime a new instance of 
 * Text_CAPTCHA_Numeral is called.
 *
 * @author   David Coallier <davidc@agoraproduction.com>
 * @package  Text_CAPTCHA_Numeral
 * @category CAPTCHA
 */
interface Text_CAPTCHA_Numeral_Interface
{
    // {{{ public function getOperation
    /**
     * Get operation
     * 
     * This function will get the operation
     * string from $this->operation
     *
     * @access public
     */
    public function getOperation();
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
     */
    public function getAnswer();
    // }}}
    // {{{ public function getFirstNumber
    /**
     * Get the first number
     * 
     * This function will get the first number
     * value from $this->firstNumber
     * 
     * @access public
     */
    public function getFirstNumber();
    // }}}
    // {{{ public function getSecondNumber
    /**
     * Get the second number value
     * 
     * This function will return the second number value
     * 
     * @access public
     */
    public function getSecondNumber();
    // }}}
}
// }}}
?>
