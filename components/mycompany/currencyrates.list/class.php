<?php
use Bitrix\Main\Loader;
use Bitrix\Main\UI\PageNavigation;
use Mycompany\CurrencyRates\CurrencyRateTable;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class CurrencyRatesList extends CBitrixComponent
{
    public function executeComponent()
    {
        Loader::includeModule('my.currencyrates');

        // Подготовка фильтра
        $filter = [];
        if (!empty($_REQUEST['CURRENCY_CODE'])) {
            $filter['CODE'] = $_REQUEST['CURRENCY_CODE'];
        }
        if (!empty($_REQUEST['DATE_FROM']) && !empty($_REQUEST['DATE_TO'])) {
            $filter['>=DATE'] = $_REQUEST['DATE_FROM'];
            $filter['<=DATE'] = $_REQUEST['DATE_TO'];
        }

        // Инициализация объекта навигации
        $nav = new PageNavigation("nav");
        $nav->allowAllRecords(true)
            ->setPageSize($this->arParams['PAGE_SIZE'] ?? 10)
            ->initFromUri();

        // Запрос списка курсов с постраничной навигацией
        $dbResult = CurrencyRateTable::getList([
            'filter' => $filter,
            'select' => ['ID', 'CODE', 'DATE', 'COURSE'],
            'order' => ['DATE' => 'DESC'],
            'count_total' => true,
            'offset' => $nav->getOffset(),
            'limit' => $nav->getLimit(),
        ]);

        // Устанавливаем общее количество записей для навигации
        $nav->setRecordCount($dbResult->getCount());

        // Подготовка данных для шаблона
        $this->arResult['ITEMS'] = $dbResult->fetchAll();
        $this->arResult['NAV_STRING'] = $nav;

        // Выводим шаблон компонента
        $this->includeComponentTemplate();
    }
}
?>
