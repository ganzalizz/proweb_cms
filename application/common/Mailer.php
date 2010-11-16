<?php   
  
/**  
 * @see Zend_Mail  
 */   
require_once 'Zend/Mail.php';   
  
/**  
 * Class Mailer have fixes problems the with headers in mails
 *   
 * @name Mailer
 * @uses Zend_Mail  
 * @copyright Copyrigth (c) 2008-2009 web-blog.org.ua  
 * @author Andrew Vasiliev  
 * @version 0.0.0.1  
 *  
 */   
class Mailer extends Zend_Mail
{   
    /**  
     * Encode a given string with the QUOTED_PRINTABLE mechanism  
     *  
     * @param string $str  
     * @param int $lineLength Defaults to {@link LINELENGTH}  
     * @param int $lineEnd Defaults to {@link LINEEND}  
     * @return string  
     */   
    protected function encodeQuotedPrintable($str,   
    $lineLength = Zend_Mime::LINELENGTH)   
    {   
        $out = '';   
        $str = str_replace('=', '=3D', $str);   
        $str = str_replace(Zend_Mime::$qpKeys, Zend_Mime::$qpReplaceValues, $str);   
        $str = rtrim($str);   
        // Split encoded text into separate lines   
        while ($str) {   
            $ptr = strlen($str);   
            if ($ptr > $lineLength) {   
                $ptr = $lineLength;   
            }   
            // Ensure we are not splitting across an encoded character   
            $pos = strrpos(substr($str, 0, $ptr), '=');   
            if ($pos !== false && $pos >= $ptr - 2) {   
                $ptr = $pos;   
            }   
            // Check if there is a space at the end of the line and rewind   
            if ($ptr > 0 && $str[$ptr - 1] == ' ') {   
                --$ptr;   
            }   
            // Add string and continue   
            $out .= substr($str, 0, $ptr);   
            $str = substr($str, $ptr);   
        }   
        return $out;   
    }   
  
    /**  
     * Encode header fields  
     *  
     * Encodes header content according to RFC1522 if it contains non-printable  
     * characters.  
     *  
     * @param  string $value  
     * @return string  
     */   
    protected function _encodeHeader($value)   
    {   
        if (Zend_Mime::isPrintable($value)) {   
            return $value;   
        } else {   
            /**  
             * Next strings fixes the problems  
             * According to RFC 1522 (http://www.faqs.org/rfcs/rfc1522.html)  
             */   
            $quotedValue = '';   
            $count = 1;   
            for ($i=0; strlen($value)>$i;$i++) {   
                if ($value[$i] == '?' or $value[$i] == '_' or $value[$i] == ' ') {   
                    $quotedValue .= str_replace(array('?', ' ', '_'), array('=3F', '=20', '=5F'), $value[$i]);   
                } else {   
                    $quotedValue .= $this->encodeQuotedPrintable($value[$i]);   
                }   
                if (strlen($quotedValue)>$count*Zend_Mime::LINELENGTH) {   
                    $count++;   
                    $quotedValue .= "?=\n =?". $this->_charset . '?Q?';   
                }   
            }   
            return '=?' . $this->_charset . '?Q?' . $quotedValue . '?=';   
        }   
    }   
}