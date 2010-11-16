<?php

/**
 * Layout Helper
 *
 */
class View_Helper_DateFromDb extends Zend_View_Helper_Abstract
{
   
	
	/**
	 * convert datefrom db
	 * $date дата из базы
	 * $time если 1 то выводить время
	 */
	public function DateFromDb($date, $time=null){
            
	   if ($date!='' && !preg_match('/0000/', $date)){	
	   		$str_time = '';
	   	    if ($time==1){
	   	    	$str_time = substr($date, 10);	   	    	
	   	    }
	       if(preg_match('/([\d]{4})-([\d]{2})-([\d]{2})/', $date, $matches)){			
		   	$res = $matches[3].'.'.$matches[2].'.'.$matches[1];
                        

		   	if ($time && $str_time){
		   		$res.=$str_time;
		   	}
		   	return $res;
                        
	       }
	   }	
	   return '';
	}	
}