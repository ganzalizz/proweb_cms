<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Ext_View_Helper_PoupUpWindow extends Zend_View_Helper_Abstract
{
    public function poupupwindow($options = array())
    {
          $options['target'] = isset($options['target']) ? $options['target'] : 'window';
          $options['position'] = isset($options['position']) ? $options['position'] : 'center';
          $options['buttons'] = isset($options['buttons']) ? $options['buttons'] : '"Закрыть": function() { $(this).dialog("destroy"); $(this).remove(); }';
          $options['autoOpen'] = isset($options['autoOpen']) ? $options['autoOpen'] : 'false';
          $options['closeOnEscape'] = isset($options['closeOnEscape']) ? $options['closeOnEscape'] : true;
          $options['draggable'] = isset($options['draggable']) ? $options['draggable'] : true;
          $options['height'] = isset($options['height']) ? $options['height'] : 'auto';
          $options['width'] = isset($options['width']) ? $options['width'] : '300px';
          $options['hide'] = isset($options['hide']) ? $options['hide'] : 'slide';
          $options['modal'] = isset($options['modal']) ? $options['modal'] : true;
          $options['resizable'] = isset($options['resizable']) ? $options['resizable'] : true;
          $options['show'] = isset($options['show']) ? $options['show'] : 'slide';

           return $window = '<script type="text/javascript">'."
                             $(function(){
                             $(\"#".$options['target']."\").dialog({
                             position: \"".$options['position']."\",
                             buttons:{".$options['buttons']."},
                             autoOpen: \"".$options['autoOpen']."\",
                             closeOnEscape: \"".$options['closeOnEscape']."\",
                             draggable: \"".$options['draggable']."\",
                             height: \"".$options['height']."\",
                             width: \"".$options['width']."\",
                             hide: \"".$options['hide']."\",
                             modal: \"".$options['modal']."\",
                             resizable: \"".$options['resizable']."\",
                             show: \"".$options['show']."\"
                             });
                             });
                             $(\"#".$options['target']."\").dialog(\"open\");
                             </script>";

    }
}
