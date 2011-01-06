<?php


class Ext_View_Helper_JqueryTranslit extends Zend_View_Helper_Abstract
{
    public function JqueryTranslit($options=array())
    {
        $scr = '<script type="text/javascript" src="/js/jquery/jquery.synctranslit.min.js"></script>';
        $scr.= '<script type="text/javascript" charset="utf-8">
            $(document).ready(function(){
                $("#'.$options['id_source'].'").syncTranslit({
                    destination: "'.$options['id_receiver'].'",
                    type: "url",
                    caseStyle: "lower",
                    urlSeparator: "-"
                });
                $("#'.$options['id_receiver'].'").syncTranslit({
                    destination: "'.$options['id_receiver'].'",
                    type: "url",
                    caseStyle: "lower",
                    urlSeparator: "-"
                });
            });
        </script>';

        return $scr;
    }
}
