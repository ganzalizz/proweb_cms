<?php
class Polls_IndexController extends Zend_Controller_Action
{
	/**
	 *
	 * @var zend_layout
	 */
	private $layout = null;

	/**
	 *
	 * @var string
	 */
	private $lang = null;

	private $_count = null;

	private $_offset = null;
    private $_onpage = null;

    private $_owner_page = null;

	private $_current_page = null;

	public function init(){
            /*$this->initView()
            $view = Zend_Layout::getMvcInstance()->getView();*/

            $this->view->addHelperPath(DIR_HELPER , 'View_Helper') ;
            $this->layout = $this->view->layout();
            $this->layout->setLayout( "front/default" ) ;
            $this->lang = $this->_getParam('lang', 'ru');
            $this->layout->lang = $this->lang;

            if ($this->_hasParam('reindex')){
                    $this->_forward('reindex');
            }
            $id = $this->_getParam('id');
            $this->view->placeholder('id_page')->set($id);
            $this->_owner_page = $page = Pages::getInstance()->find($id)->current();

            if (!is_null($page)){
                if ($page->published==0){
                    $this->_redirect('/404');
                }
                $options  = PagesOptions::getInstance()->getPageOptions($id);
            }
	}

	


    public function indexAction(){echo 123; exit;
        $polls = Polls::getInstance()->getAcivePolls(true, $this->_onpage, $this->_offset);
        $flag = isset($_COOKIE['poll_id']) ? $_COOKIE['poll_id'] : 0;
        echo "flag=".$flag."\ncook=".$polls[0]->id;exit;
        if ($flag!=$polls[0]->id){
            $this->_forward('result/poll_id/'.$polls[0]->id);
        }
        
        Pollsitems::getInstance()->getAciveItems($polls);
        $this->view->categories = $polls;
    }

    public function voteAction(){
        $poll_id = $this->_getParam('poll_id', 0);
        $ansv_id = $this->_getParam('ansv_id', 0);        
        $flag = isset($_COOKIE['poll_id']) ? $_COOKIE['poll_id'] : 0;
        
        if ($flag!=$poll_id){
            $year = date('Y');
            $exp = mktime(0,0,0,date('m'),date('d'),$year+1);
            setcookie('poll_id',$poll_id, $exp);
            $answ_row = Pollsitems::getInstance()->fetchRow("id=".(int)$ansv_id." AND id_parent=".(int)$poll_id);
            if ($answ_row!=null){
                $answ_row->votecount = $answ_row->votecount+1;
                $answ_row->save();
                echo 'ok';               
            }
            else
                echo 'no';                
        }
        exit;
    }

    public function resultAction(){
        $this->layout->setLayout( "polls/pollresults" ) ;
        $poll_id = $this->_getParam('poll_id', 0);
        $this->view->layout()->poll = Polls::getInstance()->getPoll($poll_id);
        $this->view->layout()->answers = Pollsitems::getInstance()->getActiveItemsByParentId($poll_id);
        $this->view->layout()->sum = Pollsitems::getInstance()->getSum($poll_id);
    }

}
