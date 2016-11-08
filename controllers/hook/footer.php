<?php

/**
 * Class SmsNewsLetterFooterController
 */
class SmsNewsLetterFooterController
{
    /**
     * SmsNewsLetterFooterController constructor.
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
     * @throws PrestaShopException
     */
    public function postProccess(){
       
       if(Tools::isSubmit('submitSmsNewsletter')){
            
          $phone = Tools::getValue('SmsNewsLetterPhone');

          $sms = new SmsPhone();

          $msg = 'Ваш телефон добавлен в рассылку'; 
           if($sms->isNew($phone)){
              $msg = 'Такой номер уже имеется в базе телефонов';
              $nw_error = 1;
              $this->context->smarty->assign(array(
                  'nw_error' => $nw_error,
                  'color' => 'red',
                  'msg' => $msg
              ));
           }else{
               $sms->phone = $phone;
               $sms->add();

               $this->context->smarty->assign(array(
                   'color' => 'green',
                   'msg' => $msg,
                   'nw_error' => false
               ));
           }
           
         
       }
   }

    /**
     * @param $params
     * @return mixed
     */
    public function run($params)
    {
        $this->context->controller->addCSS($this->_path.'css/smsnewsletter.css', 'all');
        $this->context->controller->addJS($this->_path.'js/smsnewsletteralert.js');
        $this->postProccess();
      
      
        return $this->module->display($this->file, 'smsnewsletter.tpl');

    }
}