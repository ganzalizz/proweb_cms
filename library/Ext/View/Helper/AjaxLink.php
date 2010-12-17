<?php


class Ext_View_Helper_AjaxLink extends Zend_View_Helper_Abstract
{
    public function ajaxlink($options=array())
    {
        return $scr = '<script type="text/javascript" >'.
                       '

                        
                        
                        $(document).ready(function(){
                            var target_id = "'.$options['target_id'].'";
                            var loader_id = "'.$options['loader_id'].'";

                            $("#"+"'.$options['link_id'].'").click(function()
                            {
                               var link_id = "'.$options['link_id'].'";
                               var target_url = "'.$options['target_url'].'";
                               var url_data = '.$options['url_data'].';
                                                   

                                ajaxLinkSend(link_id, target_url, url_data, target_id, loader_id);
                            });

                                            function ajaxLinkSend(link_id, target_url, url_data, target_id, loader_id)
                                            {
                                              $.ajax({
                                                    url: target_url,
                                                    cache: false,
                                                    data: url_data,
                                                    processData: true,
                                                    type: "GET",
                                                    beforeSend: showLoading,
                                                    success: showResponse

                                                    });
                                            }

                                            function showLoading()
                                            {
                                                $("#"+target_id).hide();
                                                //$("#"+loader_id).show();
                                            }

                                            function showResponse(data)
                                            {
                                              $("#"+target_id).html(data);
                                              //$("#"+loader_id).hide();
                                              $("#"+target_id).show();
                                            }

                                               });


                        </script>';
    }
}
