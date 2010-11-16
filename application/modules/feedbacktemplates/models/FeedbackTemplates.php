<?php

/**
 * FeedbackTemplates
 *
 * @author Pavel
 * @version
 */



class FeedbackTemplates extends Zend_Db_Table {
    /**
     * The default table name
     */
    protected $_name = 'site_feedback_templates';
    protected $_primary = array('id');
    protected $_sequence = true;

    protected static $_instance = null;

    /**
     * Singleton instance
     *
     * @return FeedbackTemplates
     */
    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public static function deactivateOthers($id) {
        $data = array('active' => '0');
        $where = 'id != ' . $id;
        FeedbackTemplates::getInstance()->update($data, $where);
    }

    public function getActive() {
        $where = $this->getAdapter ()->quoteInto ( "active = ?", 1 );

        return $this->fetchRow ( $where );
    }

    public function getBySystemName($name) {
        $where = $this->getAdapter ()->quoteInto ( "system_name = ?", $name );

        return $this->fetchRow ( $where );
    }
}