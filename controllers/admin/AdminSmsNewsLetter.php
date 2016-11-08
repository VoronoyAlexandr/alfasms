<?php

class AdminSmsNewsLetterController extends AdminControllerCore
{


    public function __construct()
    {

        $this->bootstrap = true;


        $this->fields_options = array(
            'general' => array(

                'title' => $this->l('Форма отправления рассылки СМС'),
                'fields' => array(


                    'SMS_NEWS_LETTER_TEXT' => array(
                        'title' => $this->l('Текст СМС'),
                        'desc' => $this->l('Количество символов: '),
                        'type' => 'textarea',
                        'rows' => 5,
                        'cols' => 10,
                    ),

                ),
                'submit' => array(
                    'title' => 'Отправить',                       
                    'class' => 'btn btn-default pull-right',
                    'name' => 'smsnewslettertext'
                )
            )
        );


        parent::__construct();


    }

    public function setMedia()
    {
        parent::setMedia();
        $this->path = __PS_BASE_URI__ . 'modules/smsnewsletter/';
        $this->context->controller->addJS($this->path . 'js/smsnewsletter.js');
    }

    public function postProcess()
    {

        if (Tools::isSubmit('smsnewslettertext')) {


            if(ConfigurationCore::get('SMS_NEWS_LETTER')){


                $api = ModuleCore::getInstanceByName('smsnewsletter');

                $subscribers = new SmsPhone();
                $i = 0;

                $numbers = $subscribers->getNumbers();


                foreach ($numbers as $number) {

                    $api->sms->sendSMS(Configuration::get('SMS_FROM'), $number['phone'], Tools::getValue('SMS_NEWS_LETTER_TEXT'));

                    $i++;
                }

                $this->_conf = array(
                    1 => 'Отослано: ' . $i . ' смсок');
                $this->confirmations[] = $this->_conf[1];
            }else{
                $this->errors[] = Tools::displayError('Модуль не включен');
            }

        }

    }


}