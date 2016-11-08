<?php

/**
 * Class AdminSmsTemplateController
 */
class AdminSmsTemplateController extends AdminControllerCore
{


    /**
     * AdminSmsTemplateController constructor.
     */
    public function __construct()
    {

        $this->bootstrap = true;

        $order_statuses = new OrderStateCore();
        $this->context = Context::getContext();

        $statuses = $order_statuses->getOrderStates($this->context->language->id);


        $fields = array();


        foreach ($statuses as $status) {


            $fields += array(
                'SMS_TEMPLATE_' . $status['id_order_state'] => array(
                    'type' => 'textarea',
                    'title' => $this->l($status['name']),
                    'cols' => 5,
                    'desc' => $this->l('Номер статуса: ' . $status['id_order_state']),
                    'rows' => 1
                ),
            );
        }
        ksort($fields, SORT_REGULAR);  //!!!!


        $this->fields_options = array(
            'general' => array(
                'title' => $this->l('Настройки СМС агрегатора - Доступны переменные: {ID} - номер заказа, {SUM} - Итого, {TRACK} - Трек номер'),
                'fields' => $fields,

                'submit' => array(
                    'title' => 'Сохранить',
                    'class' => 'btn btn-default pull-right',
                    'name' => 'smstemplate'
                ),
            ));

        $this->fields_options['general']['fields']['SMS_LIST_ORDER_STATUSES'] = array(


            'type' => 'text',
            'title' => $this->l('Статусы'),
            'desc' => $this->l('Список статусов через запятую. Пример: 1,3,5,11'),
            'size' => 20
        );

        parent::__construct();

    }


    /**
     *
     */
    public function postProcess()
    {

        if (Tools::isSubmit('smstemplate')) {


            $params = Tools::getAllValues();

            foreach ($params as $key => $value) {
                if (strpos($key, 'SMS_TEMPLATE_') !== false) {
                    Configuration::updateValue($key, $value);
                }
            }
            Configuration::updateValue('SMS_LIST_ORDER_STATUSES', $params['SMS_LIST_ORDER_STATUSES']);

        }

    }


}