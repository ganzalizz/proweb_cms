<?php


class Ext_View_Helper_AjaxDeleteLink extends Zend_View_Helper_Abstract
{
    public function ajaxDeleteLink($options=array())
    {
		
    	if (!isset($options['error_mess'])){
    		$options['error_mess'] = 'error';
    	}
    	
    	
        return $scr = '<script type="text/javascript" >'.
                       '

                        
                        
                        $(document).ready(function(){
                            var target_id = "'.$options['target_id'].'";
                            var delete_id = "'.$options['delete_id'].'";
                            var loader_img = "'.$options['loader_img'].'";
                            var error_mess = "'.$options['error_mess'].'";

                            $("#"+"'.$options['link_id'].'").click(function()
                            {
                               var link_id = "'.$options['link_id'].'";
                               var target_url = "'.$options['target_url'].'";
                               var url_data = '.$options['url_data'].';
                               var sure = confirm("Вы уверены?");	                       
								if(sure){
                                	ajaxDeleteLinkSend(link_id, target_url, url_data, target_id, delete_id, loader_img);
                                }
                            });

                                            function ajaxDeleteLinkSend(link_id, target_url, url_data, target_id, delete_id, loader_img)
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
                                                	$("#"+target_id).prepend("<img src=\'"+loader_img+"\'>");
                                                }
                                                
                                            }

                                            function showResponse(data)
                                            {
                                              if(data!=error_mess){                                            	
                                            	$("#"+delete_id).remove();	                                           
	                                          } else {
    											alert("Ошибка.");					
    										  }  	
                                              
                                            }

                                               });


                        </script>';
    }
}
