<?php

class SmsPhone extends ObjectModel
{
    public $id_subscriber;
    public $phone;
    public $date_add;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'smsphone', 'primary' => 'id_subscriber', 'multilang' => false,
        'fields' => array(
            'phone' => array('type' => self::TYPE_STRING, 'validate' => 'isPhoneNumber', 'size' => 32),
            'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDate', 'copy_post' => false),
        ),
    );


    public function isNew($number)
    {
        $sql = 'SELECT `phone`
				FROM ' . _DB_PREFIX_ . 'smsphone
				WHERE `phone` = ' . pSQL($number);

         return  Db::getInstance()->getRow($sql);

    }
    
    public function getNumbers(){

        $numbers = Db::getInstance()->executeS(

        'SELECT `phone`
				FROM ' . _DB_PREFIX_ . 'smsphone');
        
        return $numbers;
    }

}