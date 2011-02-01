<?php

class Rss_RssController extends Zend_Controller_Action {

    public function init() {
        $this->initView();
        $this->layout = $this->view->layout();
        $this->lang = $this->_getParam('lang', 'ru');

        $this->layout->setLayout("front/default");
        $id = $this->_getParam('id');
        $page = Pages::getInstance()->getPage($this->_getParam('id'));
        if (!is_null($page)) {
            if ($page->is_active == '0') {
                $this->_redirect('/404');
            }

            $this->layout->page = $page;
            // $this->layout->lang = $page->version;

            $this->view->addScriptPath(DIR_LAYOUTS);
            $this->view->addHelperPath(Zend_Registry::get('helpersPaths'), 'View_Helper');

            $this->view->options = $options = PagesOptions::getInstance()->getPageOptions($id);
            $this->view->placeholder('title')->set($options->title);
            $this->view->placeholder('keywords')->set($options->keywords);
            $this->view->placeholder('descriptions')->set($options->descriptions);
            $this->view->placeholder('id_page')->set($id);
            $this->view->placeholder('object_id')->set($id);
            $this->view->placeholder('h1')->set($page->title);
            $this->layout->current_type = 'pages';
            $this->view->page = $page;
            $this->layout->id_page = $page->id;
        }
    }

    public function indexAction() {
        
        
        $rssFeeds = Rss::getInstance()->getAllRssFeed();
        
        
        
        
        
        if ($this->_hasParam('item'))
            $this->_forward('portfolioitem');
        $year = $this->_hasParam('year') ? $this->_getParam('year') : 'all';
        $id_page = $this->_getParam('id');

        $page = Pages::getInstance()->getPageByParam('id', $this->_getParam('id'));
        // $this->view->child_pages = $child_pages = Pages::getInstance()->getPagesByParam('parentId', $this->_getParam('id'));
        $this->view->page = $page;
        $this->_setParam('id', $page->id);
        $this->view->year = $year;
        $this->view->years = Portfolio::getInstance()->getYearsForFilter();
        $this->view->portfolio = Portfolio::getInstance()->getActivePortfolio($year);
        $this->view->portfolio_count = Portfolio::getInstance()->getPortfolioCount();
    }
    
    public function doFeedAction($channel_sys_name){
        
        $channel_sys_name = $this->_getParam('feed','all');
        if ($channel_sys_name != 'all'){
        
        $conf_ini = new Ext_Common_Config('rss', 'frontend');
        $limit = $conf_ini->countRecords;
        
        $feed = Rss::getInstance()->getRssFeedByName($channel_sys_name);
        
        $r = new ReflectionClass(ucfirst($channel_sys_name));
        $module = $r->newInstance();
        
        $results = $module->getLimitOrderByDate($feed[0]['fields'],$limit);
        
        $feed_module_conf = new Ext_Common_Config($channel_sys_name);
        
        $feed = new Zend_Feed_Writer_Feed();
        //TODO: Вычитывание имени сайта из главного конфига
        $feed->setTitle($feed_module_conf->module->name.'сайта');
        $feed->setLink($this->getRequest()->getHttpHost().'/'.$channel_sys_name);
        $feed->setFeedLink($this->getRequest()->getHttpHost() . $this->view->url(), 'rss');
        $feed->addAuthor(array(
                               'name'  => 'Paddy',
                               'email' => 'paddy@example.com',
                               'uri'   => $this->getRequest()->getHttpHost(),
                                ));
        $feed->setDateModified(time());
        $feed->addHub('http://pubsubhubbub.appspot.com/');
        
        foreach ($results as $result){
            
           
                $entry = $feed->createEntry();

                $entry->setTitle('All Your Base Are Belong To Us');

                $entry->setLink('http://www.example.com/all-your-base-are-belong-to-us');

                $entry->addAuthor(array(

                                        'name'  => 'Paddy',

                                        'email' => 'paddy@example.com',

                                        'uri'   => $this->getRequest()->getHttpHost(),

                                        ));

                $entry->setDateModified(time());

                $entry->setDateCreated(time());

                $entry->setDescription('Exposing the difficultly of porting games to English.');

                $entry->setContent(

                                    'I am not writing the article. The example is long enough as is ;).'

                                    );

                $feed->addEntry($entry);

             
               

            
        }
        $out = $feed->export('rss');
       
        }
        
        
    } 

    public function portfolioitemAction() {
        $portfolio_url = $this->_getParam('item', '');
        $item = Portfolio::getInstance()->getPortfolioByUrl($portfolio_url);

        if (isset($item) && $item) {
            $this->view->item = $portfolio_row = $item;
            if ($portfolio_row != '') {
                $bread_items[] = array('title' => $portfolio_row->title);
                $this->view->placeholder('title')->set($portfolio_row->seo_title);
                $this->view->placeholder('keywords')->set($portfolio_row->seo_keywords);
                $this->view->placeholder('descriptions')->set($portfolio_row->seo_descriptions);
                $this->view->placeholder('h1')->set($portfolio_row->title);
                $this->view->layout()->bread_items = $bread_items;
            }

            $this->view->prev_item = Portfolio::getInstance()->getPrevPortfolio($item->id);
            $this->view->next_item = Portfolio::getInstance()->getNextPortfolio($item->id);

            $this->render('portfolioitem');
        }
    }

}