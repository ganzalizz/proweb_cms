<?php

/**
 * Printed
 *
 * @author Vitali
 * @version
 */

class Subscribe extends Zend_Db_Table {
    /**
     * The default table name
     */
    protected $_name = 'site_subscribe';
    protected $_primary = array('id');
    protected $_sequence = true;

    protected static $_instance = null;

    /**
     * Singleton instance
     *
     * @return Printed
     */
    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function deleteSubscription($email) {
        $where = array(
                $this->getAdapter ()->quoteInto ( "email = ?", $email ),
        );
        $this->delete($where);
    }
}