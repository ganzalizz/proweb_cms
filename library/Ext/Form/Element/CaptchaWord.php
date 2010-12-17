<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Ext_Form_Element_CaptchaWord extends Zend_Form_Element_Captcha
{
    public function init()
    {
        $this->setLabel('Введите символы');
        $this->setOptions(array(
                'captcha'   => 'Image', // Тип CAPTCHA
                'wordLen'   => 6,       // Количество генерируемых символов
                'width'     => 200,     // Ширина изображения
                'timeout'   => 120,     // Время жизни сессии хранящей символы
                'expiration'=> 300,     // Время жизни изображения в файловой системе
                'font'      => Zend_Registry::get('config')->path->rootPublic . 'fonts/arial.ttf', // Путь к шрифту
                'imgDir'    => Zend_Registry::get('config')->path->rootPublic . 'img/captcha/', // Путь к изобр.
                'imgUrl'    => '/img/captcha/', // Адрес папки с изображениями
                'gcFreq'    => 5        // Частота вызова сборщика мусора
        ));
//        $this->setCaptcha('captcha', array(
//                'captcha'   => 'Image', // Тип CAPTCHA
//                'wordLen'   => 6,       // Количество генерируемых символов
//                'width'     => 200,     // Ширина изображения
//                'timeout'   => 120,     // Время жизни сессии хранящей символы
//                'expiration'=> 300,     // Время жизни изображения в файловой системе
//                'font'      => Zend_Registry::get('config')->path->rootPublic . 'fonts/arial.ttf', // Путь к шрифту
//                'imgDir'    => Zend_Registry::get('config')->path->rootPublic . 'img/captcha/', // Путь к изобр.
//                'imgUrl'    => '/img/captcha/', // Адрес папки с изображениями
//                'gcFreq'    => 5        // Частота вызова сборщика мусора
//        ));
    }
}
 
