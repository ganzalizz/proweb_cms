<?php

/**
 * Orders
 *
 * @author Vitali
 * @version 1.0
 */



class Orders extends Zend_Db_Table {
/**
 *
 * статусы заказа
 */
    const STATUS_NONE		= 0;  	// статус не определен
    const STATUS_ADDED 		= 1;  	// заказ добавлен в базу
    const STATUS_PORCESSED 	= 2;	// обработан менеджером
    const STATUS_COMPLETED 	= 3;	// заказ выполнен
    const STATUS_ARHIVED 	= 4;	// заказ в архиве

    /**
     * названия статусов
     * @var array
     */
    private $_status_titles = array (
    self::STATUS_NONE  		=>'Не определн',
    self::STATUS_ADDED  	=>'<span class="new" >Новый</span>',
    self::STATUS_PORCESSED  =>'Принят',
    self::STATUS_COMPLETED  =>'<span class="complete" >Выполнен</span>',
    self::STATUS_ARHIVED  	=>'А архиве'
    );


    /**
     * The default table name
     */
    protected $_name = 'site_catalog_orders';
    /**
     * The default primary key.
     *
     * @var array
     */
    protected $_primary = array ('id' );

    /**
     * Whether to use Autoincrement primary key.
     *
     * @var boolean
     */
    protected $_sequence = true;


    /**
     * Singleton instance.
     *
     * @var Orders
     */
    protected static $_instance = null;





    /**
     * Singleton instance
     *
     * @return orders
     */
    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self( );
        }

        return self::$_instance;
    }
    /**
     * возвращает массив заголовков для статусов
     * @return array
     */
    public function getStatusTitles() {
        return $this->_status_titles;
    }

    /**
     * валидаторы
     *
     * @return array
     */
    public function getValidators() {
        $messages = Configurator::getConfig('messages')->email->toArray();
        $validators = array(
            'Имя' => array(
            'presence'=>'required',  // обязательный параметр
            Zend_Filter_Input::FIELDS =>'user_name',
            ),
            'Улица' => array(
            'presence'=>'required',  // обязательный параметр
            Zend_Filter_Input::FIELDS =>'user_street',
            ),
            'Дом' => array(
            'presence'=>'required',  // обязательный параметр
            Zend_Filter_Input::FIELDS =>'user_house',
            ),
            'Корпус' => array(
            'allowEmpty' => true,  // необязательный параметр
            Zend_Filter_Input::FIELDS =>'user_house_block',
            ),
            'Квартира' => array(
            'allowEmpty' => true,  // необязательный параметр
            Zend_Filter_Input::FIELDS =>'user_flat',
            ),

            'Телефон'=>array(
            'presence'=>'required',  // обязательный параметр
            Zend_Filter_Input::FIELDS =>'user_phone',
            ),

            'Электронная почта' => array(
            'EmailAddress',
            'allowEmpty' => true,  // необязательный параметр
            Zend_Filter_Input::FIELDS =>'user_email',
            'messages' => array(
            array(
            Zend_Validate_EmailAddress::INVALID_FORMAT => $messages[Zend_Validate_EmailAddress::INVALID_FORMAT],
            Zend_Validate_EmailAddress::INVALID => $messages[Zend_Validate_EmailAddress::INVALID]
            ),
            )
            ),



        );
        return $validators;
    }

    /**
     * добавление заказа в базу
     * @param Zend_DB_Table_row $order
     * @param array $data
     * @return int
     */
    public function AddOrder($order, $data) {
        $order->status = self::STATUS_ADDED;
        $order->added_time = New Zend_Db_Expr("CURRENT_TIMESTAMP");
        $order->edited_time = $order->added_time;
        $order->content = serialize($data);
        return $order->save();
    }

    /**
     * получение всех заказов для админки
     * @param int $count
     * @param int $offset
     * @return Zend_Db_Table_Rowset
     */
    public function getAll($search_params, $count = null, $offset = null) {

        $date_format = "%d.%m.%Y %H:%i:%s";
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array('o'=>$this->_name), array(
            'o.*',            
            'added_time'=> "DATE_FORMAT(added_time, '$date_format') ",
            'processed_time' => "DATE_FORMAT(processed_time, '$date_format')",
            'completed_time'=>"DATE_FORMAT(completed_time, '$date_format')",
            'edited_time'=>"DATE_FORMAT(edited_time, '$date_format')",
            'total_minutes'=>"ROUND((UNIX_TIMESTAMP(completed_time) - UNIX_TIMESTAMP(added_time))/60, 0)"

        ));
        $select->joinLeft(array('m'=>'users'), 'o.id_manager=m.id', 'lastName as manager' );
        $select->joinLeft(array('c'=>'site_users'), 'o.id_user=c.id', array('user_login'=>'login', 'user_first_name'=>'first_name') );
        $select->limit($count, $offset);
        $select->order(array('status ASC', 'id DESC'));

        if (isset($search_params['date_from']) && $date_from = $this->dateToDb($search_params['date_from'])) {
            $select->where("o.added_time >= ?", $date_from);
        }
        if (isset($search_params['date_to']) && $date_to = $this->dateToDb($search_params['date_to'])) {
            $select->where("o.added_time <= ?", $date_to);
        }
        if (isset($search_params['id_manager']) && $search_params['id_manager']) {
            $select->where("o.id_manager = ?", (int)$search_params['id_manager']);
        }

        if (isset($search_params['price_from']) && $search_params['price_from']) {
            $select->where("o.price >= ?", $search_params['price_from']);
        }
        if (isset($search_params['price_to']) && $search_params['price_to']) {
            $select->where("o.price <= ?", $search_params['price_to']);
        }
        if (isset($search_params['user']) && $search_params['user']) {
            $user_text = $search_params['user'];
            $select->where(
                "
				c.login= '$user_text' OR
				c.email= '$user_text' OR
				c.first_name LIKE '$user_text' OR
				c.last_name LIKE '$user_text' OR
				c.id = ?"			
                , $user_text
            );

        }

        //echo $select->__toString();

        return $this->fetchAll($select);
    }

    /**
     * получение заказа для просмотра
     * @param int $id
     * @return Zend_Db_Table_Row
     */
    public function getItemToView($id) {
        $date_format = "%d.%m.%Y %H:%i:%s";
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(
            array('order'=>$this->_name),
            array(
            '*',
            'added_time'=> "DATE_FORMAT(added_time, '$date_format') ",
            'processed_time' => "DATE_FORMAT(processed_time, '$date_format')",
            'completed_time'=>"DATE_FORMAT(completed_time, '$date_format')",
            'edited_time'=>"DATE_FORMAT(edited_time, '$date_format')",
            'total_minutes'=>"ROUND((UNIX_TIMESTAMP(completed_time) - UNIX_TIMESTAMP(added_time))/60, 0)"

            )
        );
        $select->where("order.id = ?", (int)$id);
        $select->joinLeft(array('users'), 'order.id_manager=users.id', array('manager' =>'lastName') );

        //echo $select->__toString(); exit;
        return $this->fetchRow($select);
    }

    /**
     * получение заказа пользователя
     * @param int $id
     * @return Zend_Db_Table_Row
     */
    public function getAllUserOrders($id) {
        $time_format = "%H:%i";
        $datetime_format = "%d.%m.%Y %H:%i:%s";
        $date_format = "%d.%m.%Y";
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(
            array('order'=>$this->_name),
            array(
            '*',
            'added_time'=> "DATE_FORMAT(added_time, '$time_format') ",
            'added_date'=> "DATE_FORMAT(added_time, '$date_format') ",
            'processed_time' => "DATE_FORMAT(processed_time, '$time_format')",
            'processed_date' => "DATE_FORMAT(processed_time, '$date_format')",
            'completed_time'=>"DATE_FORMAT(completed_time, '$datetime_format')",
            'edited_time'=>"DATE_FORMAT(edited_time, '$datetime_format')",
            'total_minutes'=>"ROUND((UNIX_TIMESTAMP(completed_time) - UNIX_TIMESTAMP(added_time))/60, 0)"

            )
        );
        $select->where("order.id_user = ?", (int)$id);
        $select->joinLeft(array('users'), 'order.id_manager=users.id', array('manager' =>'lastName') );
        $select->order('status');

        //echo $select->__toString(); exit;
        return $this->fetchAll($select);
    }

    /**
     * устанавливает следующий статус
     * @param int $id
     * @return int
     */
    public function SetNextStatus($id) {
        $item = $this->find($id)->current();
        switch ($item->status) {
            case self::STATUS_ADDED:
                $item->status = self::STATUS_PORCESSED;
                $item->processed_time =  New Zend_Db_Expr("CURRENT_TIMESTAMP");
                $user = Security::getInstance()->getUser();
                if ($user!=null) {
                    $item->id_manager = $user->id;
                }
                break;

            case self::STATUS_PORCESSED:
                $item->status = self::STATUS_COMPLETED;
                $item->completed_time =  New Zend_Db_Expr("CURRENT_TIMESTAMP");
                break;

            case self::STATUS_COMPLETED:
                $item->status = self::STATUS_ARHIVED;

                break;

        }
        return $item->save();

    }

    private function dateToDb($date) {
        if ($date) {
            preg_match('/([\d]{2})[\.\/-]{1}([\d]{2})[\.\/-]{1}([\d]{4})/i', $date, $matches);
            return $matches[3].'-'.$matches[2].'-'.$matches[1];
        }
        return '';
    }

    /**
     * Добавить комментарий аказук з
     * @param id $order_id
     * @param string $title
     * @param string $comment
     */
    public function addComment($order_id, $title, $comment) {
        $order = $this->find($order_id)->current();
        if (!$order) return null;

        $order->comment_title = $title;
        $order->comment_text = $comment;
        $order->save();
    }
    /**
     * если количество выполненных заказов юзера >= установленного
     * отмечает юзера как постоянного клиента
     * @param int $id_user
     */
    public function setRegularCustomer($id_user){
    	$select = $this->select()
    		->from($this->_name, 'COUNT(*) as total')
    		->where('id_user = ?', $id_user)
    		->where('status >= ?', self::STATUS_COMPLETED);    			
    	$total_orders = $this->getAdapter()->fetchOne($select);
    	$total = (int)Blocks::getInstance()->getContentBySystemName('regular_customer');
    	if ($total_orders>=$total){
    		$user = SiteUsers::getInstance()->find($id_user)->current();
    		if($user!=null){
    			$user->regular_customer = 1;
    			$user->save();
    		}
    	}
    	
    }


}
