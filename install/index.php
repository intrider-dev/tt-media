<?php
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Application;

class my_currencyrates extends CModule
{
    public function __construct()
    {
        $this->MODULE_ID = 'my.currencyrates';
        $this->MODULE_NAME = 'Модуль курсов валют';
        $this->MODULE_VERSION = '1.0.0';
        $this->MODULE_VERSION_DATE = '2024-09-01';
        $this->MODULE_DESCRIPTION = 'Модуль для работы с курсами валют';
    }

    public function DoInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
        $this->installDB();
        $this->installFiles();
        $this->installAgents();
        // Регистрация агента
        \CAgent::AddAgent(
            "\\Mycompany\\CurrencyRates\\Agent::updateRates();", // Имя функции агента
            "my.currencyrates", // Идентификатор модуля
            "N", // Агент не критичен к количеству вызовов
            86400, // Интервал 24 часа (86400 секунд)
            "", // Дата первой активации агента
            "Y", // Активен ли агент
            "", // Дата первого запуска
            100, // Приоритет агента
            false, // Не запускать при установке
            false // Модуль, который должен быть подключен для агента
        );
}


    public function DoUninstall()
    {
        $this->unInstallAgents();
        $this->unInstallFiles();
        $this->unInstallDB();
        // Удаление агента
        \CAgent::RemoveAgent(
            "\\Mycompany\\CurrencyRates\\Agent::updateRates();", // Имя агента
            "my.currencyrates" // Идентификатор модуля
        );
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    public function installDB()
    {
        $connection = Application::getConnection();
        if (!$connection->isTableExists('currency_rates')) {
            $connection->queryExecute("
                CREATE TABLE currency_rates (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    code VARCHAR(3),
                    date DATE,
                    course FLOAT
                )
            ");
        }
    }

    public function unInstallDB()
    {
        $connection = Application::getConnection();
        $connection->queryExecute("DROP TABLE IF EXISTS currency_rates");
    }

    public function installFiles()
    {
        // Устанавливаем компоненты из local, если они там есть
        $componentsPathLocal = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/my.currencyrates/components';
        $componentsPathBitrix = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/my.currencyrates/components';
    
        if (file_exists($componentsPathLocal)) {
            CopyDirFiles($componentsPathLocal, $_SERVER['DOCUMENT_ROOT'] . '/local/components', true, true);
        } elseif (file_exists($componentsPathBitrix)) {
            CopyDirFiles($componentsPathBitrix, $_SERVER['DOCUMENT_ROOT'] . '/local/components', true, true);
        }
    
        // Устанавливаем административные файлы из local, если они там есть
        $adminPathLocal = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/my.currencyrates/admin';
        $adminPathBitrix = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/my.currencyrates/admin';
    
        if (file_exists($adminPathLocal)) {
            CopyDirFiles($adminPathLocal, $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin', true, true);
        } elseif (file_exists($adminPathBitrix)) {
            CopyDirFiles($adminPathBitrix, $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin', true, true);
        }
    }

    public function unInstallFiles()
    {
        // Проверяем, установлены ли компоненты в local или bitrix
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/components/my.currencyrates/')) {
            DeleteDirFilesEx('/local/components/my.currencyrates/');
        } elseif (file_exists($_SERVER['DOCUMENT_ROOT'] . '/bitrix/components/my.currencyrates/')) {
            DeleteDirFilesEx('/bitrix/components/my.currencyrates/');
        }
    
        // Проверяем и удаляем административные файлы, если они есть в local или bitrix
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/modules/my.currencyrates/admin')) {
            DeleteDirFilesEx('/bitrix/admin/currencyrates_admin.php');
            DeleteDirFilesEx('/bitrix/admin/currencyrates_edit.php');
            DeleteDirFilesEx('/bitrix/admin/currencyrates_settings.php');
        } elseif (file_exists($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/my.currencyrates/admin')) {
            DeleteDirFilesEx('/bitrix/admin/currencyrates_admin.php');
            DeleteDirFilesEx('/bitrix/admin/currencyrates_edit.php');
            DeleteDirFilesEx('/bitrix/admin/currencyrates_settings.php');
        }
    }    
    

    public function installAgents()
    {
        \CAgent::AddAgent(
            "\\My\\CurrencyRates\\Agent::updateRates();",
            $this->MODULE_ID,
            "N",
            86400 // Раз в сутки
        );
    }

    public function unInstallAgents()
    {
        \CAgent::RemoveModuleAgents($this->MODULE_ID);
    }
}
