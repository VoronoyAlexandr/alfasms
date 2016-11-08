<?php

/**
 * Class AdminSmsSettingsController
 */
class AdminSmsSettingsController extends AdminControllerCore
{

    /**
     * AdminSmsSettingsController constructor.
     */
    public function __construct()
    {

        $this->bootstrap = true;


        $this->fields_options = array(
            'general' => array(
                'title' => $this->l('Настройки СМС агрегатора'),
                'fields' => array(
                    'SMS_ORDER' => array(
                        'title' => $this->l('Отправка СМС'),
                        'desc' => $this->l('Включить смс после оформления заказа'),
                        'cast' => 'boolval',
                        'type' => 'bool'
                    ),

                    'SMS_NEWS_LETTER' => array(
                        'title' => $this->l('Рассылка СМС'),
                        'desc' => $this->l('Включить подписки на новости по смс.'),
                        'cast' => 'boolval',
                        'type' => 'bool'
                    ),
                    'SMS_LOGIN' => array(
                        'type' => 'text',
                        'title' => $this->l('Логин'),
                        'desc' => $this->l('Номер телефона.'),
                        'size' => 20,
                        'required' => true
                    ),
                    'SMS_PASS' => array(
                        'type' => 'text',
                        'title' => $this->l('Пароль'),
                        'desc' => $this->l('Пароль от агрегатора'),
                        'size' => 20,
                        'required' => true
                    ),

                    'SMS_BALANCE' => array(
                        'type' => 'text',
                        'title' => $this->l('Баланс'),
                        'desc' => "Вы сами знаете что делать при достижении 0 грн",
                        'size' => 5,
                        'readonly' => true,
                    ),
                    'SMS_FROM' => array(
                        'type' => 'text',
                        'title' => $this->l('Отправитель'),
                        'desc' => $this->l('Имя отправителя будет указано в смс, обязательно латинскими большими буквами.'),
                        'size' => 20,
                        'required' => true
                    ),

                ),
                'submit' => array(
                    'title' => 'Сохранить',                       // This is the button that saves the whole fieldset.
                    'class' => 'btn btn-default pull-right',
                    'name' => 'smssettings'
                )
            )
        );

        parent::__construct();

    }

  
    public function postProcess()
    {

        if (Tools::isSubmit('smssettings')) {
            $params = Tools::getAllValues();

            Configuration::updateValue('SMS_NEWS_LETTER', $params['SMS_NEWS_LETTER']);
            Configuration::updateValue('SMS_ORDER', $params['SMS_ORDER']);
            Configuration::updateValue('SMS_LOGIN', $params['SMS_LOGIN']);
            Configuration::updateValue('SMS_PASS', $params['SMS_PASS']);
            Configuration::updateValue('SMS_FROM', $params['SMS_FROM']);
          

        }

    }


}