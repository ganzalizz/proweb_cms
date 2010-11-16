<?php
/**
 * Admin_Catalog_TovarController
 *
 */
class Catalog_Admin_ProductController extends MainAdminController {
    /**
     * Controller name
     *
     * @var string
     */
    private  $_current_module =null;
    /**
     * division id
     *
     * @var int
     */
    private  $_id_division = null;
    /**
     * @var $_division Catalog_Division_Row
     */
    private $_division = null;
    /*
	 * текущаяя страница
    */
    private $_current_page = null;
    private $_onpage = 50;
    private $_offset = null;





    public function init() {
        $this->initView();
        $this->layout = $this->view->layout();
        $this->layout->title = "Каталог";
        $this->layout->action_title = 'Список товаров';
        $this->view->id_division = $this->_id_division =  $this->_getParam('id_division');
        if ($this->_id_division) {
            $this->_division = Catalog_Division::getInstance()->find($this->_id_division)->current();
        }
        $lang = $this->_getParam('lang','ru');
        $this->view->current_page = $this->_current_page = $this->_getParam('page', 1);
        $this->view->onpage = $this->_onpage;
        $this->_offset = ceil(($this->_current_page-1)*$this->_onpage);
        $this->view->currentModule = $this->_current_module =
                SP.
                $this->getRequest()->getModuleName().SP.
                $lang.SP.
                $this->getRequest()->getControllerName()
        ;


    }

    /**
     * список товаров раздела
     */
    public function indexAction() {
        if ($this->_division!=null) {
            $this->layout->action_title = "Список товаров в разделе ". $this->_division->name;
        }

        if ($this->_request->isPost()) {
            $array_Priority = $this->_getParam('priority');
            Catalog_Product::getInstance()->setPriority($array_Priority);

            $action = $this->_getParam('operation');
            $ids = $this->_getParam('products');
            if ($action && $ids) {
                Catalog_Product::getInstance()->processProducts($action, $ids);
            }
        }

        $this->view->items = Catalog_Product::getInstance()->getProductsByDivId($this->_id_division, $this->_onpage, $this->_offset);
        $this->view->total = Catalog_Product::getInstance()->getProductsByDivId($this->_id_division)->count();
        $options = array(
                'disable'=>' Заблокировать',
                'enable'=>' Активировать',
                'delete'=>' Удалить',

        );
        $this->view->options = $options;



    }

    /**
     * изменение активности
     */
    public function activeAction() {
        $id = $this->_getParam('id');
        Catalog_Product::getInstance()->changeActivity($id);
        $this->redirectToIndex();
    }


    public function editAction() {

        $id =  $this->getRequest()->getParam('id');
        
        if ($id) {
            $item = Catalog_Product::getInstance()->find($id)->current();
            $params = Catalog_Product_Options_Enabled::getInstance()->getAllOptionsValues($id);
            $this->view->params = $params;
            if ($this->_division!=null) {
                $this->layout->action_title = "Редактировать товар в разделе \"". $this->_division->name.'"';
            }
        }  else {
            $item = Catalog_Product::getInstance()->createRow();
            if ($this->_division!=null) {
                $this->layout->action_title = "Создать товар в разделе \"". $this->_division->name."\"";
            }            
        }
        $this->view->division = $division = Catalog_Division::getInstance()
                ->find($item->id_division)
                ->current();

        if ($this->_request->isPost()) {
            $ok = 1;
            $data = $this->_getAllParams();
            if ($data['title']!='') {
                $item->setFromArray(array_intersect_key($data,$item->toArray()));
                $id = $item->save();
                

                $img_name = $_FILES['img']['name'];
                $img_source = $_FILES['img']['tmp_name'];
                $delete_img = $this->_getParam('delete_img');
                if ($img_name!='' && $img_source!='' && !$delete_img) {
                    $ext = @end(explode('.', $img_name));
                    $name = date('Ymd_h_i_s');
                    $img_big = DIR_PUBLIC.'pics/catalog/product/'.$name.'_img.'.$ext;
                    if (copy($img_source, $img_big)) {
                        Catalog_Product_Images::getInstance()->deleteMainByProduct($id) ;
                        $img_row = Catalog_Product_Images::getInstance()->fetchNew();
                        $img_row->img = $name.'_img.'.$ext;
                        $img_row->title = '';
                        $img_row->id_product = $id;
                        $img_row->active = 1;
                        $img_row->main = 1;
                        $img_row->save();
                    }

                } else if ($delete_img) {
                    Catalog_Product_Images::getInstance()->deleteMainByProduct($id) ;
                }
                Catalog_Product_Default_Values::getInstance()->updateValues($id, $this->_getParam('option', array()) );

            } else {
                $this->view->message="Ошибка заполните поля помеченные звездочкой";
                $ok = 0;
            }
            Catalog_Division::getInstance()->updateCountProducts($this->_id_division);
            $this->view->ok = $ok;            
        }
        if (!isset($img_row) && $id) {
            $img_row = Catalog_Product_Images::getInstance()->getMainByProduct($id);

        }
        if (isset($img_row) && $img_row!=null) {
            $this->view->img = $img_row->img;
        }
        $fck1 = $this->getFck('intro', '90%', '150','Basic');
        $this->view->fck_intro = $fck1;
        $fck2 = $this->getFck('description', '90%', '300');
        $this->view->fck_content = $fck2;
        $this->view->item = $item;
        $default_options = Catalog_Product_Default::getInstance()->getDefaultToProduct($id);        
        $this->view->def_options = $default_options;


    }


    /**
     * удаление товара
     */
    public function deleteAction() {

        $id = $this->_getParam('id');
        if ($id) {
            $item = Catalog_Product::getInstance()->find($id)->current();
            if ($item!=null) {
            	Catalog_Product_Default_Values::getInstance()->deleteValues($id);
                $item->delete();
            }
        }
        Catalog_Division::getInstance()->updateCountProducts($this->_id_division);
        $this->redirectToIndex();
    }

    /**
     * @todo нуждается в рефакторинге
     *
     */
    public function copyAction() {
        $this->view->lang = $lang = $this->getParam('lang','ru');
        $this->request = $this->getRequest();
        $currentModul = $lang.'/'. $this->request->getControllerName(); //. $this->request->getActionName();
        if($this->_hasParam('tovarid')) {
            $item_id = (int)$this->getRequest()->getParam('tovarid');
            $item = Catalog_Product::getInstance()->find($item_id)->current()->toArray();
            //$teh_fields_values = Catalog_TehnicFildsValues::getInstance()->getAllByProductId($item_id);
            unset($item['id']);
            //$item['created_at'] = new Zend_Db_Expr('CURDATE()');
            $item['name'].='_'.'copy';
            $item['is_new']=0;
            $item['is_popular']=0;
            $new_row = Catalog_Product::getInstance()->createRow()->setFromArray($item);
            $new_row->save();

            /*if (isset($item)){
					$new_tovar_id = Catalog_Product::getInstance()->addProduct($item);
					foreach ($teh_fields_values as $value){
					$insert_value = Array('tehnic_fild_id'=>$value->tehnic_fild_id,
					                     'tovar_id'=>$new_tovar_id,
					                     'value'=>$value->value);
					Catalog_TehnicFildsValues::getInstance()->addItem($insert_value);
				}
			}*/
        }
        $this->_redirect('/admin/'.$currentModul.'/items/divid/'.$item['division_id'].'/id_page/'.$this->_id_page);
    }
    /**
     * редирект на список товаров
     */
    private function redirectToIndex() {
        $this->_redirect($this->_current_module.'/index/id_division/'.$this->_id_division);
    }

}
