<?php
namespace Mycompany\CurrencyRates;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Type\Date;
use Mycompany\CurrencyRates\CurrencyRateTable;

class Agent
{
    /**
     * Агент для обновления курсов валют
     * @return string
     */
    public static function updateRates()
    {
        // Получаем текущую дату
        $date = new Date();
        $api = new CBRApi();

        // Получаем список валют из настроек модуля
        $selectedCurrencies = explode(',', Option::get('my.currencyrates', 'selected_currencies', ''));
        if ($selectedCurrencies == [] || $selectedCurrencies == ['0' => '']) { 
            $bSel = false; 
        } else {
            $bSel = true; 
        };

        // Получаем курсы валют с сайта ЦБ РФ
        $rates = $api->getCursOnDate($date);

        if ($rates) {
            foreach ($rates as $rate) {
                // Обрабатываем только те валюты, которые указаны в настройках
                if ($bSel) {
                    if (!in_array($rate['code'], $selectedCurrencies)) {
                        continue;
                    }
                }

                // Проверяем, есть ли запись за текущую дату и эту валюту
                $existingRate = CurrencyRateTable::getList([
                    'filter' => [
                        'CODE' => $rate['code'],
                        'DATE' => $date
                    ]
                ])->fetch();

                // Если записи нет за эту дату, добавляем новую
                if (!$existingRate) {
                    CurrencyRateTable::add([
                        'CODE' => $rate['code'],
                        'DATE' => $date,
                        'COURSE' => $rate['value']
                    ]);
                }
            }
        }

        // Агент должен возвращать строку с вызовом себя для повторного выполнения
        return "\\Mycompany\\CurrencyRates\\Agent::updateRates();";
    }
}
