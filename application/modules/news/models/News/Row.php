<?php

class News_Row extends Zend_Db_Table_Row {

    //private $_path ="/pics/portfolio/"; // path to images

  
    /**
     * Gets route
     *
     * @return string
     */
    public function getUrl() {
       // return Pages::getInstance()->find($this->page_id)->current()->getPath();
    }

    public function getPath() {
//        $page = Pages::getInstance()->find($this->page_id)->current();
//        if (!is_null($page)) {
//            return $page->getPath();
//        }
//        return '';
    }
    /**
     * Gets date by format
     *
     * @param string $param
     * @return string
     */
    public function date($param = 'all') {
        $format='';
        switch ($param) {
            case 'day': $format ="%d";// Day of the month, numeric (0..31)
                break;
            case 'month': $format ="%b"; //Abbreviated month name (Jan..Dec)
                break;
            default: $format =  $param;
                break;
        }

        $date = News::getInstance()->convertData($this->id, $format);
        return $date;

    }
    
 	

    public function delete() {
        /**
         * удаление элемента из индекса
         */
        Ext_Search_Lucene::deleteItemFromIndex ( $this->id, Ext_Search_Lucene::NEWS );
        return parent::delete ();
    }

    /**
     * Allows post-update logic to be applied to row.
     * Subclasses may override this method.
     *
     * @return void
     */
    protected function _postUpdate() {
        Ext_Search_Lucene::deleteItemFromIndex ( $this->id, Ext_Search_Lucene::NEWS );
        if ($this->pub == 1) {
            $this->addItemToSearchIndex();
        }

    }

    /**
     * Allows post-insert logic to be applied to row.
     * Subclasses may override this method.
     *
     * @return void
     */
    protected function _postInsert() {
        /**
         * индексируются только опубликованные элементы
         */
        if ($this->pub == 1) {
            $this->addItemToSearchIndex();
        }
    }

    /**
     * добавление элемента в индекс
     * @return void
     *
     */
    protected function addItemToSearchIndex() {
        $index = Ext_Search_Lucene::open ( Ext_Search_Lucene::NEWS );
        $doc = new Ext_Search_Lucene_Document ( );
        $doc->setUrl ( SiteDivisionsType::getInstance()->getPagePathBySystemName('newslist').'item/'.$this->id );
        $doc->setTitle ( $this->name );
        $doc->setContent ( strip_tags ( $this->content ) );
        $doc->setId ( $this->id );
        $index->addDocument ( $doc );
    }
    
    protected function  _insert() {
        parent::_insert();
    }
}