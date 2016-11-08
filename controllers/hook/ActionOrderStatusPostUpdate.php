<?php

/**
 * Class SmsNewsLetterActionOrderStatusPostUpdateController
 */
class SmsNewsLetterActionOrderStatusPostUpdateController
{
    /**
     * SmsNewsLetterActionOrderStatusPostUpdateController constructor.
     * @param $module
     * @param $file
     * @param $path
     */
    public function __construct($module, $file, $path)
    {
        $this->file = $file;
        $this->module = $module;
        $this->context = Context::getContext();
        $this->_path = $path;

    }

    /**
     * @param $params
     * @param $id_order_state
     */
    public function sendMessage($params, $id_order_state)
    {

        $id_order = $params['id_order'];
        $order = new OrderCore($id_order);
        $order_fields = $order->getFields();
        $total = $order_fields['total_paid'];



        $id_address = $order_fields["id_address_delivery"];
        $address = new AddressCore($id_address);
        $address_fields = $address->getFields();

        $phone = $address_fields['phone_mobile'];
        $track = $order->getWsShippingNumber();

        $search = array('{ID}', '{SUM}', '{TRACK}');
        $replace = array($id_order, $total, $track);
        
        
        
        
        $message = str_replace($search, $replace, Configuration::get('SMS_TEMPLATE_'.$id_order_state));
       
        $client = new SMSClient(Configuration::get('SMS_LOGIN'), Configuration::get('SMS_PASS'));
        $client->sendSMS(Configuration::get('SMS_FROM'), $phone, $message);

        return;
    }

    /**
     * @param $params
     */
    public function run($params)
    {

        $id_order_state = $params['newOrderStatus']->id;

        $list_order_statuses = explode(',', Configuration::get('SMS_LIST_ORDER_STATUSES'));


        if(in_array($id_order_state, $list_order_statuses)) {


            $this->sendMessage($params, $id_order_state);
        }

      
    }
}