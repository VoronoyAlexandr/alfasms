<?php

require(_PS_MODULE_DIR_ . "smsnewsletter/smsclient.class.php");
require_once(dirname(__FILE__) . '/classes/SmsPhone.php');

/**
 * Class SmsNewsLetter
 */
class SmsNewsLetter extends Module
{
    /**
     * @var SMSClient
     */
    public $sms;

    /**
     * SmsNewsLetter constructor.
     */
    public function __construct()
    {
        $this->name = 'smsnewsletter';
        $this->tab = 'other';
        $this->version = '0.1';
        $this->author = 'Вороной Александр';
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('СМС рассылка');
        $this->description = $this->l('СМС рассылка подписчикам магазина.');

        $this->sms = new SMSClient(Configuration::get('SMS_LOGIN'), Configuration::get('SMS_PASS'));
        Configuration::updateValue('SMS_BALANCE', round($this->sms->getBalance(), 2));


    }

    /**
     * @param $sql_file
     * @return bool
     */
    public function loadSQLFile($sql_file)
    {
        // Get install SQL file content
        $sql_content = file_get_contents($sql_file);

        // Replace prefix and store SQL command in array
        $sql_content = str_replace('PREFIX_', _DB_PREFIX_, $sql_content);
        $sql_requests = preg_split("/;\s*[\r\n]+/", $sql_content);

        // Execute each SQL statement
        $result = true;
        foreach ($sql_requests as $request)
            if (!empty($request))
                $result &= Db::getInstance()->execute(trim($request));

        // Return result
        return $result;
    }

    /**
     * @return bool
     */
    public function install()
    {


        $sql_file = dirname(__FILE__) . '/install/install.sql';
        if (!$this->loadSQLFile($sql_file))
            return false;

        $parent_tab = new Tab();
        foreach (Language::getLanguages(true) as $lang)
            $parent_tab->name[$lang['id_lang']] = 'СМС модули';
        $parent_tab->class_name = 'SmsTab';
        $parent_tab->id_parent = 0;
        $parent_tab->module = $this->name;
        $parent_tab->add();
        if (!parent::install()
            || !$this->registerHook('DisplayBackOfficeHeader')
            || !$this->registerHook('footer')
            || !$this->registerHook('ActionOrderStatusPostUpdate')
            || !$this->installModuleTab('AdminSmsNewsLetter', array((int)(Configuration::get('PS_LANG_DEFAULT')) => 'СМС рассылка'), $parent_tab->id)
            || !$this->installModuleTab('AdminListSubscribers', array((int)(Configuration::get('PS_LANG_DEFAULT')) => 'СМС подписчики'), $parent_tab->id)
            || !$this->installModuleTab('AdminSmsSettings', array((int)(Configuration::get('PS_LANG_DEFAULT')) => 'СМС настройки'), $parent_tab->id)
            || !$this->installModuleTab('AdminSmsTemplate', array((int)(Configuration::get('PS_LANG_DEFAULT')) => 'СМС шаблоны'), $parent_tab->id)
        )
            return false;
        return true;
    }

    /**
     * @return bool
     */
    public function uninstall()
    {


        $sql_file = dirname(__FILE__) . '/install/uninstall.sql';
        if (!$this->loadSQLFile($sql_file))
            return false;

        if (!parent::uninstall()
            || !$this->registerHook('DisplayBackOfficeHeader')
            || !$this->registerHook('footer')
            || !$this->registerHook('ActionOrderStatusPostUpdate')
            || !$this->uninstallModuleTab('AdminSmsTemplate')
            || !$this->uninstallModuleTab('AdminSmsNewsLetter')
            || !$this->uninstallModuleTab('AdminSmsSettings')
            || !$this->uninstallModuleTab('AdminListSubscribers')
            || !$this->uninstallModuleTab('SmsTab')
            || !Configuration::deleteByName('SMS_ORDER')
            || !Configuration::deleteByName('SMS_REMINDER')
            || !Configuration::deleteByName('SMS_NEWS_LETTER')
            || !Configuration::deleteByName('SMS_LOGIN')
            || !Configuration::deleteByName('SMS_PASS')
            || !Configuration::deleteByName('SMS_FROM')
            || !Configuration::deleteByName('SMS_BALANCE')
            || !Configuration::deleteByName('SMS_TEMPLATE')

        )
            return false;
        return true;
    }

    /**
     * @param $tabClass
     * @param $tabName
     * @param $idTabParent
     * @return mixed
     */
    private function installModuleTab($tabClass, $tabName, $idTabParent)
    {

        $idTab = $idTabParent;
        $pass = true;
        @copy(_PS_MODULE_DIR_ . $this->name . '/logo.gif', _PS_IMG_DIR_ . 't/' . $tabClass . '.gif');
        $tab = new Tab();
        $tab->name = $tabName;
        $tab->class_name = $tabClass;
        $tab->module = $this->name;
        $tab->id_parent = $idTab;
        $pass = $tab->save();
        return ($pass);
    }

    /**
     * @param $tabClass
     * @return bool
     */
    private function uninstallModuleTab($tabClass)
    {
        $pass = true;
        @unlink(_PS_IMG_DIR_ . 't/' . $tabClass . '.gif');
        $idTab = Tab::getIdFromClassName($tabClass);
        if ($idTab != 0) {
            $tab = new Tab($idTab);
            $pass = $tab->delete();
        }
        return ($pass);
    }

    /**
     * @param $hook_name
     * @return mixed
     */
    public function getHookController($hook_name)
    {

        require_once(dirname(__FILE__) . '/controllers/hook/' . $hook_name . '.php');

        $controller_name = $this->name . $hook_name . 'Controller';

        $controller = new $controller_name($this, __FILE__, $this->_path);

        return $controller;
    }

    /**
     * @param $params
     * @return mixed
     */
    public function hookFooter($params)
    {
        $controller = $this->getHookController('footer');
        return $controller->run($params);
    }

    /**
     * @param $params
     */
    public function hookActionOrderStatusPostUpdate($params)
    {
        if (ConfigurationCore::get('SMS_ORDER')) {
            $controller = $this->getHookController('ActionOrderStatusPostUpdate');
            return $controller->run($params);
        }
        return;
    }


    /**
     *
     */
    public function hookDisplayBackOfficeHeader()
    {
        $this->context->controller->addCss($this->_path . 'css/tab.css');
    }
}

