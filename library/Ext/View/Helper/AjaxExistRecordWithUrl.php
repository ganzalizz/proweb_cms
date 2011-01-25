<?php


class Ext_View_Helper_AjaxExistRecordWithUrl extends Zend_View_Helper_Abstract
{
    public function ajaxExistRecordWithUrl($options=array())
    {
        // пока не используется, да и не готов((
        return $scr = '
            <script type="text/javascript" charset="utf-8">
                $(document).ready(function(){
                    
                    target_url = "'.$options['target_url'].'";
                    item_id = '.$options['item_id'].';
                    id_url = "'.$options['id_url'].'";
                    id_title = "'.$options['id_title'].'";
                    img_loader = "'.$options['img_loader'].'";
                    img_ok = "'.$options['img_ok'].'";
                    img_error = "'.$options['img_error'].'";

                    memory_value = "";
                    id_url = "#"+id_url;
                    id_title = "#"+id_title;
                    id_process = "url_process";

                    $(id_url).prev("p.form_name").children("span:first").after(" <span id="+id_process+"></span>");

                    id_process = "#"+id_process;

                    $(id_url).blur(function() {
                        processUrlAjax();
                    });

                    $(id_title).blur(function() {
                        processUrlAjax();
                    });

                    function processUrlAjax()
                    {
                        urlvalue = $(id_url).val();
                        if (urlvalue != "" && urlvalue != memory_value) {
                            $.ajax({
                                url: target_url,
                                cache: false,
                                data: "urlvalue="+urlvalue+"&itemid="+item_id,
                                processData: true,
                                type: "GET",
                                beforeSend: showLoading,
                                success: showResponse
                            });
                        }
                        memory_value = urlvalue;
                    }

                    function showLoading()
                    {
                        $(id_process).html("<img src=\'"+img_loader+"\'>");
                    }

                    function showResponse(data)
                    {
                        if(data!="error") {
                            $(id_process).html("<img src=\'"+img_ok+"\'>")
                        } else {
                            $(id_process).html("<img src=\'"+img_error+"\'>")
                        }
                    }
                });
            </script>';
    }
}
