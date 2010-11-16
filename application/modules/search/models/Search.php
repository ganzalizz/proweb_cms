<?php

class Search {

    public static function optimizeindexes() {
        $index = Ext_Search_Lucene::open(Ext_Search_Lucene::NEWS);
        $index->optimize();
        $index = Ext_Search_Lucene::open(Ext_Search_Lucene::PAGES);
        $index->optimize();
        $index = Ext_Search_Lucene::open(Ext_Search_Lucene::CATALOG_DIVISIONS);
        $index->optimize();
        $index = Ext_Search_Lucene::open(Ext_Search_Lucene::CATALOG_PRODUCTS);
        $index->optimize();
    }

    public static function reindex() {
        $numDocs = News::getInstance()->reindex();
        $numDocs += Pages::getInstance()->reindex();
        $numDocs += Catalog_Division::getInstance()->reindex();
        $numDocs += Catalog_Product::getInstance()->reindex();
        echo $numDocs;
    }
}