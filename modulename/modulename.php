<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class Modulename extends Module
{
    public function __construct()
    {
        $this->name = 'modulename';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Your Name';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = ['min' => '8.0.0', 'max' => '8.9.99'];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Module Display Name');
        $this->description = $this->l('Module description');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }

    public function install()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.bqSQL('modulename_data').'` (
            `id_modulename_data` INT AUTO_INCREMENT,
            `data` VARCHAR(255) NOT NULL,
            PRIMARY KEY (`id_modulename_data`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8mb4';

        return parent::install()
            && $this->registerHook('displayFooter')
            && Db::getInstance()->execute($sql);
    }

    public function uninstall()
    {
        $sql = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.bqSQL('modulename_data').'`';

        return Db::getInstance()->execute($sql) && parent::uninstall();
    }

    public function getContent()
    {
        $output = '';
        if (Tools::isSubmit('submit_modulename')) {
            $value = Tools::getValue('MODULENAME_SETTING');
            Configuration::updateValue('MODULENAME_SETTING', pSQL($value));
            $output .= $this->displayConfirmation($this->l('Settings updated'));
        }

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->table = $this->table;
        $helper->show_toolbar = false;
        $helper->default_form_language = (int) Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG');
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submit_modulename';
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->fields_value['MODULENAME_SETTING'] = Configuration::get('MODULENAME_SETTING');

        $form = [
            'form' => [
                'legend' => ['title' => $this->l('Settings')],
                'input' => [
                    [
                        'type' => 'text',
                        'label' => $this->l('Example setting'),
                        'name' => 'MODULENAME_SETTING',
                        'required' => true,
                    ],
                ],
                'submit' => ['title' => $this->l('Save')],
            ],
        ];

        return $output.$helper->generateForm([$form]);
    }

    public function hookDisplayFooter($params)
    {
        return '<div>'.$this->l('Module footer text').'</div>';
    }
}
