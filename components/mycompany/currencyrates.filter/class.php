<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class CurrencyRatesFilter extends CBitrixComponent
{
    public function executeComponent()
    {
        $this->arResult = [
            'FILTER_FIELDS' => [
                'CURRENCY_CODE' => htmlspecialcharsbx($_REQUEST['CURRENCY_CODE']),
                'DATE_FROM' => htmlspecialcharsbx($_REQUEST['DATE_FROM']),
                'DATE_TO' => htmlspecialcharsbx($_REQUEST['DATE_TO']),
            ]
        ];

        $this->includeComponentTemplate();
    }
}
