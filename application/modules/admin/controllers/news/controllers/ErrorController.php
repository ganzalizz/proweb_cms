<?php
/** Zend_Controller_Action */
require_once 'Zend/Controller/Action.php';

class ErrorController extends Zend_Controller_Action
{
    public function errorAction()
    {

    	$errors = $this->_getParam('error_handler');
    	    	require_once("Zend/Debug.php");
    	
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // ошибка 404 - не найден контроллер или действие
               

                $content =<<<EOH
<h1>Ошибка!</h1>
<p>Запрошенная вами страница не найдена.</p>
EOH;
                $this->getResponse()->clearBody();
               
                echo $this->getResponse()->setRawHeader('HTTP/1.1 404 Not Found')->appendBody($content);
//                 $this->getResponse()->setHeader('Content-Type', 'text/html')->appendBody($content);
//Zend_Debug::dump($content, 'content:');
                break;
            default:
                // ошибка приложения
                $content =<<<EOH
<h1>Ошибка!</h1>
<p>При обработке вашего запроса произошла непредвиденная ошибка. Пожалуйста, попробуйте позднее.</p>
EOH;
                break;
        }

        // Удаление добавленного ранее содержимого
        $this->getResponse()->clearBody();

        $this->view->content = $content;
    }
}