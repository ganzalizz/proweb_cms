<?php
class Mail
{
	/**
	 * Отправка сообщения с переданными параметрами
	 * на указанный email
	 *
	 * @param string $email
	 * @param string $msg	 
	 * @param string $subject
	 * @param string $recipient
	 */
	
	
	
	public static function send($email, $msg, $subject=null, $recipient = ''){
		
		$config_options = Configurator::getConfig('mail');
		if ($email!='' && $msg!=''){
			$mail = new Zend_Mail('UTF-8');
			if ($config_options->contentType=='html'){
				$mail->setBodyHtml($msg, 'UTF-8');	
			} else {
				$mail->setBodyText($msg, 'UTF-8');
			}
			$mail->setFrom($config_options->fromEmail,$config_options->fromName);
			$subject = $subject!=null ? $subject : $config_options->subject;
			$mail->setSubject($subject);
			$mail->addTo($email, $recipient);
			$mail->send();
		}
		
				
		//if (isset($_FILES['file']) && $_FILES['file']['tmp_name']!=''){
			
			//$file_content = file_get_contents($_FILES['file']['tmp_name']);			
			//$at = $mail->addAttachment(new Zend_Mime_Part($file_content));
			//echo $_FILES['file']['name'];
			//$mail->createAttachment($file_content, $_FILES['file']['type'],  Zend_Mime::DISPOSITION_INLINE, Zend_Mime::ENCODING_8BIT, $_FILES['file']['name']);
			 /** 
			  * Создаём вложение, читаем файл 
			  */  
			// $file = new Zend_Mime_Part($file_content);  
			 /** 
			  * Указываем тип содержимого файла 
			  */  
		//	 $file->type = 'application/octet-stream';  
		//	 $file->disposition = Zend_Mime::DISPOSITION_INLINE;  
			 /** 
			  * Каким способом закодировать файл в письме 
			  */  
		//	 $file->encoding = Zend_Mime::ENCODING_BASE64;  
			 /** 
			  * Название файла в письме 
			  */  
		//	 $file->filename = $_FILES['file']['name'];  
			 /** 
			  * Идентификатор содержимого. 
			  * По нему можно обращаться к файлу в теле письма 
			  */  
		//	 $file->id = md5(time());  
			 /** 
			  * Описание вложеного файла 
			  */  
		//	 $file->description = 'Вложение';  
			 /** 
			  * Добавляем вложение в письмо 
			  */  
		//	 $mail->addAttachment($file);  
			
		//}
		
	}
	
}