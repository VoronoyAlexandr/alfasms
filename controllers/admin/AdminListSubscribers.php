<?php

class AdminListSubscribersController extends ModuleAdminController
{
    public function __construct()
    {

        $this->table = 'smsphone';
        $this->className = 'SmsPhone';
        $this->identifier = 'id_subscriber';
        $this->fields_list = array(
            'id_subscriber' => array('title' => $this->l('ID'), 'width' => 25),
            'phone' => array('title' => $this->l('Телефон'), ),
            'date_add' => array('title' => $this->l('Дата'), 'type' => 'datetime')
        );


        $this->context = Context::getContext();
        $this->context->controller = $this;



        $this->bootstrap = true;


        parent::__construct();

        $this->_select = "a.id_subscriber, a.phone, a.date_add";
        $this->_orderBy = 'a.id_subscriber';

        $this->addRowAction('delete');



        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Удалить выбранные'),
                'confirm' => $this->l('Точно удалить?'),
            )
        );


        $this->meta_title = $this->l('Номера телефонов подписчиков');
        
        $this->toolbar_title[] = $this->meta_title;
    }
    protected function processBulkMyAction()
    {
        Tools::dieObject($this->boxes);
    }
}