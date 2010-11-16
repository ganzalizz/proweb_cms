<?php
class Users_UserareaController extends Zend_Controller_Action
{
    private $layout = null;
    private $lang = null;
    private $_count = null;
    private $_offset = null;
    private $_onpage = 10;
    private $_owner_page = null;
    private $_current_page = null;

    public function init(){
        !SiteAuth::getInstance()->checkUser()?$this->_redirect('/avtorizaciya'):'';

        $this->view->addHelperPath(Zend_Registry::get('helpersPaths') , 'View_Helper') ;
        $this->layout = $this->view->layout();
        $this->layout->setLayout( "front/default" );
        $this->layout->lang = $this->_getParam('lang', 'ru');
        
        $id = $this->_getParam('id');
        $this->view->placeholder('id_page')->set($id);
        $this->_owner_page = $page = Pages::getInstance()->find($id)->current();

        if (!is_null($page)){
            if ($page->published==0){
                    $this->_redirect('/404');
            }

            $options  = PagesOptions::getInstance()->getPageOptions($id);
            $this->view->placeholder('title')->set($options->title);
            $this->view->placeholder('keywords')->set($options->keywords);
            $this->view->placeholder('descriptions')->set($options->descriptions);
            $this->view->placeholder('h1')->set($options->h1);
            $this->layout->page = $page;            

            $this->_page = $this->_getParam('page', 1);
            $this->_offset =($this->_page-1)*$this->_onpage;
            $this->view->current_page = $this->_page;
            $this->view->onpage = $this->_onpage;
        }
    }

    public function myresumesAction() {
        $this->view->h1 = 'Мои резюме';
        $order = $this->_getParam('sort', 'created_at');
        $this->view->resumes = Resume::getInstance()->getUserResumes($order);
        $this->view->resumeCount = count($this->view->resumes);

        if (!$this->view->resumeCount) {
            $this->view->message = 'У вас не резюме';
            $this->render();
        }

        $this->view->order = $order;
        $this->view->onpage = $this->_onpage;
    }

    public function myvacanciesAction() {
        $this->view->h1 = 'Мои вакансии';
        $order = $this->_getParam('sort', 'created_at');        
        $this->view->vacancies = Vacancy::getInstance()->getUserVacancies($order);
        $this->view->vacancyCount = count($this->view->vacancies);
        
        if (!$this->view->vacancyCount) {
            $this->view->message = 'У вас не вакансий';
            $this->render();
        }
        $this->view->order = $order;
        $this->view->onpage = $this->_onpage;
    }

    public function editresumeAction() {
        $resumeId = $this->_getParam('resumeid', null);        
        if (!$resumeId) $this->_redirect('/myresumes');

        $matched = Resume::getInstance()->matchUser($resumeId);
        if ($matched==0) $this->_redirect('/myresumes');  

        if ($this->_request->isPost()){
            $validators = Resume::getInstance()->getValidators();
            $data = $this->_getAllParams();
            $resumedata = $data['resume'];
            $resumedata['notify'] = isset($resumedata['notify'])?1:0;
            $resumedata['active'] = isset($resumedata['active'])?1:0;
            $resumedata['created_at'] = new Zend_Db_Expr('NOW()');
            $resumedata['zarplata_ot'] = trim($resumedata['zarplata_ot'])==''?0:(int)$resumedata['zarplata_ot'];
            $resumedata['zarplata_do'] = trim($resumedata['zarplata_do'])==''?999999999:(int)$resumedata['zarplata_do'];
            $resumedata['user'] = SiteAuth::getInstance()->getCurrentUserId();

            $filter = FormFilter::getInstance()->getFilterInput($validators, $resumedata);
            if ($filter->isValid()){
                
                $resumedata = $filter->getEscaped();
                
                
                $resume = Resume::getInstance()->find($resumeId)->current();
                $resume->setFromArray(array_intersect_key($resumedata, $resume->toArray()));
                
                $resume->save();
                $count = Resume::getInstance()->getCount('activity_item='.(int)$resume['activity_item'].' AND active = 1');
                Activities_Items::getInstance()->updateResumeCount($count, (int)$resume['activity_item']);
                $count = Activities_Items::getInstance()->getCount('activity_id='.(int)$resume['activity'].' AND resume_count>0');
                Activities::getInstance()->updateResumeCount($count, (int)$resume['activity']);
                $ok= 1;
            } else {
                $errors =$filter->getMessages();
                $resumedata = $this->_getParam('resume');
                $ul_errors = array();
                foreach ((array)$errors as $key=>$err){
                        $ul_errors[$key] = implode('<br>', $err);
                }               
                $this->view->errors = $ul_errors;
                $this->view->id_root = @$resumedata['activity'];
            }
        }

        $this->view->h1 = 'Редактировать резюме';
        $this->view->resume = Resume::getInstance()->find($resumeId)->current();
        $this->view->fields = $this->getFields();
        
    }

    public function editvacancyAction() {
        $vacancyId = $this->_getParam('vacancyid', null);
        if (!$vacancyId) $this->_redirect('/myvacancies');

        $matched = Vacancy::getInstance()->matchUser($vacancyId);
        if ($matched==0) $this->_redirect('/myvacancies');

        if ($this->_request->isPost()){
            $validators = Vacancy::getInstance()->getValidators();
            $data = $this->_getAllParams();
            $vacancydata = $data['vacancy'];
            $vacancydata['notify'] = isset($vacancydata['notify'])?1:0;
            $vacancydata['active'] = isset($vacancydata['active'])?1:0;
            $vacancydata['created_at'] = new Zend_Db_Expr('NOW()');
            $vacancydata['zarplata_ot'] = trim($vacancydata['zarplata_ot'])==''?0:(int)$vacancydata['zarplata_ot'];
            $vacancydata['zarplata_do'] = trim($vacancydata['zarplata_do'])==''?999999999:(int)$vacancydata['zarplata_do'];
            $vacancydata['user'] = SiteAuth::getInstance()->getCurrentUserId();
            
            $filter = FormFilter::getInstance()->getFilterInput($validators, $vacancydata);

            if ($filter->isValid()){
                $vacancydata = $filter->getEscaped();
                $vacancy = Vacancy::getInstance()->find($vacancyId)->current();
                $vacancy->setFromArray(array_intersect_key($vacancydata, $vacancy->toArray()));
                $vacancy->save();
                $count = Vacancy::getInstance()->getCount('activity_item='.(int)$vacancy['activity_item'].' AND active = 1');
                Activities_Items::getInstance()->updateVacancyCount($count, (int)$vacancy['activity_item']);
                $count = Activities_Items::getInstance()->getCount('activity_id='.(int)$vacancy['activity'].' AND vacancy_count>0');
                Activities::getInstance()->updateVacancyCount($count, (int)$vacancy['activity']);
                $ok= 1;
            } else {
                $errors =$filter->getMessages();
                $vacancydata = $this->_getParam('vacancy');
                $ul_errors = array();
                foreach ((array)$errors as $key=>$err){
                        $ul_errors[$key] = implode('<br>', $err);
                }
                $this->view->errors = $ul_errors;
                $this->view->id_root = @$vacancydata['activity'];
            }
        }

        $this->view->h1 = 'Редактировать вакансию';
        $this->view->vacancy = Vacancy::getInstance()->find($vacancyId)->current();
        $this->view->fields = $this->getFields();
    }

    public function getFields($first='Выбрать') {
        $cities =  array(
            'id' => '0',
            'type' => 'region',
            'title' => 'Регион',
            'priority' => '130',
            'active' => '1',
            'form_type' => 'select',
            'required' => 0,
            'childs'=>Cities::getInstance()->getCitiesArray(),
        );
        $activities =  array(
            'id' => '0',
            'type' => 'activity',
            'title' => 'Сфера деятельности',
            'priority' => '120',
            'active' => '1',
            'form_type' => 'select',
            'required' => 1,
            'childs'=>Activities::getInstance()->getActivitiesArray($first),
        );
        $activitiesitems =  array(
            'id' => '0',
            'type' => 'activity_item',
            'title' => 'Специальность',
            'priority' => '110',
            'active' => '1',
            'form_type' => 'select',
            'required' => 1,
            'childs'=>Activities_Items::getInstance()->getActivitiesItemsArray($first),
        );

        $fields['region'] = $cities;
        $fields['activity'] = $activities;
        $fields['activity_item'] = $activitiesitems;
        $types = WorkSettings::getInstance()->getAciveSettings(true);
        Settings_Items::getInstance()->getValuesOfType($fields,$types);

        return $fields;
    }

    public function subcategoryAction(){
        $id_root = $this->_getParam('id_root', 0);
        $subcategories = Activities_Items::getInstance()->fetchAll("active='1' AND activity_id='$id_root'", "priority DESC");
        
        if ($subcategories->count()){
                print_r(json_encode($subcategories->toArray()));
                exit;
        }
        echo json_encode('err');
        exit;
    }

     public function deleteresumeAction(){
            $resume_id = $this->_getParam('resumeid', null);
            if ($resume_id ){
                $item = Resume::getInstance()->find($resume_id)->current();

                $speciality_id = Resume::getInstance()->getSpecialityIdByResumeId($resume_id);
                $activity_id = Activities_Items::getInstance()->getActivityIdBySpecialityId($speciality_id);

                Resume::getInstance()->delete("id = " . $resume_id);

                $count = Resume::getInstance()->getCount('activity_item='.$speciality_id);
                Activities_Items::getInstance()->updateResumeCount($count, $speciality_id);

                $count = Activities_Items::getInstance()->getCount('activity_id='.(int)$activity_id.' AND resume_count>0');
                Activities::getInstance()->updateResumeCount($count, (int)$activity_id);


            }
            $this->_redirect("/myresumes");
	}

        public function deletevacancyAction(){
            $vacancyId = $this->_getParam('vacancyid', null);
            if ($vacancyId ){
                $item = Vacancy::getInstance()->find($vacancyId)->current();

                $speciality_id = Vacancy::getInstance()->getSpecialityIdByVacancyId($vacancyId);
                $activity_id = Activities_Items::getInstance()->getActivityIdBySpecialityId($speciality_id);

                Vacancy::getInstance()->delete("id = " . $vacancyId);

                $count = Vacancy::getInstance()->getCount('activity_item='.$speciality_id);
                Activities_Items::getInstance()->updateVacancyCount($count, $speciality_id);

                $count = Activities_Items::getInstance()->getCount('activity_id='.(int)$activity_id.' AND vacancy_count>0');
                Activities::getInstance()->updateVacancyCount($count, (int)$activity_id);


            }
            $this->_redirect("/myvacancies");
	}
}