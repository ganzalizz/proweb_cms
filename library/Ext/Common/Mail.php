<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Ext_Common_Mail extends Zend_Mail
{
    /**
     *
     * @var Zend_Mail_Transport 
     */
    protected $_transport = null;
    /**
     *
     * @var string 
     */
    protected $_mailEncoding = 'UTF-8';
    /**
     *
     * @var string 
     */
    protected $_mailBodyType = 'html';
    /**
     *
     * @var string
     */
    protected $_subject = null;
    /**
     *
     * @param string $charset 
     */
    public function  __construct($charset = null) {
        parent::__construct($this->_mailEncoding);
        $this->getMailTransport();
    }

    /**
     * 
     */
    protected function getMailTransport()
    {
      $this->_transport = new Zend_Mail_Transport_Smtp('localhost');   
    }
    /**
     *
     * @param string $mailEncoding - UTF-8 etc. 
     */
    public function setEncoding($mailEncoding)
    {
        $this->_mailEncoding = $mailEncoding;
    }
    /**
     *
     * @return string 
     */
    public function getMailEncoding()
    {
        return $this->_mailEncoding;
    }
    /**
     *
     * @param string $mailBodyType - html, text
     */
    public function setMailBodyType($mailBodyType)
    {
        $this->_mailBodyType = $mailBodyType;
    }
    /**
     *
     * @return string
     */
    public function getMailBodyType()
    {
        return $this->_mailBodyType;
    }
    
    
    
    /**
     *
     * @param string $email_to
     * @param string $msg
     * @param string $subject
     * @param string $recipient 
     */
    public function SendMail($email_to, $msg, $subject=null, $recipient = '')
    {
        
        $this->setFrom('avenger999@gmail.com', 'Admin');
        $this->addTo($email_to, $recipient);
        if ($this->_mailBodyType == 'html') $this->setBodyHtml ($msg, $this->_mailEncoding);
                else $this->setBodyText ($msg, $this->_mailEncoding);
        if (!is_null($subject)) $this->setSubject($subject);
        $this->send($this->_transport);
    }
    
    public function SendMailWFaile()
    {
        //
    }
    
    
}
