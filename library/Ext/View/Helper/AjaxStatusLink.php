<?php


class Ext_View_Helper_AjaxStatusLink extends Zend_View_Helper_Abstract
{
    public function ajaxStatusLink($options=array())
    {
		
    	if (!isset($options['error_mess'])){
    		$options['error_mess'] = 'error';
    	}
    	
    	
        return $scr = '<script type="text/javascript" >'.
                       '

                        
                        
                        $(document).ready(function(){
                            var target_id = "'.$options['target_id'].'";
                            var loader_img = "'.$options['loader_img'].'";
                            var error_mess = "'.$options['error_mess'].'";

                            $("#"+"'.$options['link_id'].'").click(function()
                            {
                               var link_id = "'.$options['link_id'].'";
                               var target_url = "'.$options['target_url'].'";
                               var url_data = '.$options['url_data'].';
                                                   

                                ajaxStatusLinkSend(link_id, target_url, url_data, target_id, loader_img);
                            });

                                            function ajaxStatusLinkSend(link_id, target_url, url_data, target_id, loader_img)
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
                                                //$("#"+target_id).html("<img src=\'/img/loader.gif\'>");
                                                if(loader_img!=""){
                                                	$("#"+target_id).html("<img src=\'"+loader_img+"\'>");
                                                }
                                                
                                            }

                                            function showResponse(data)
                                            {
                                              if(data!=error_mess){                                            	
                                            	$("#"+target_id).html(data);	                                           
	                                          } else {
    											alert("Ошибка.");					
    										  }  	
                                              
                                            }

                                               });


                        </script>';
    }
}
