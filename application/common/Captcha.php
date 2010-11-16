<?php

class Captcha
{
	/**
	 * Получение арифметического выражения
	 * и запись ррезультата в сессию
	 *
	 * @return string
	 */
	public function getOperation(){
		require_once (DIR_PEAR . 'Text' . DS . 'CAPTCHA' . DS . 'Numeral.php');
		$numcap = new Text_CAPTCHA_Numeral;
		$_SESSION['answer'] = $numcap->getAnswer();		
		return $numcap->getOperation();
	}
	
	/**
	 * Проверка результата
	 *
	 * @param int $answer
	 * @return boolean
	 */
	public function isValidate($answer){
		if (isset($_SESSION['answer']) && $answer == $_SESSION['answer']){
			return true;
		} 
		else{
			return false;
		}
	}
}