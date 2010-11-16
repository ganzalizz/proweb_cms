<?php

class Loader {
    public static function load($fullPath) {
        require_once(ROOT_DIR . $fullPath . '.php');
    }

    /**
     * /application/system
     */
    public static function loadSystem($fullPath) {
        require_once(DIR_APPLICATION . 'system' . DS . $fullPath . '.php');
    }

    /**
     * /application/common
     */
    public static function loadCommon($path) {
        require_once(DIR_APPLICATION . 'common' . DS . $path . '.php');
    }


    /**
     * /application/models
     */
    public static function loadPublicModel($model) {
        $filename = DIR_MODELS . $model . '.php' ;
        if( file_exists($filename) )
            require_once($filename);
        elseif( file_exists( $filename = str_replace("_",DS,$filename) ) )
            require_once( $filename ) ;
    }


}