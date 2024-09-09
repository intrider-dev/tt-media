<?php
namespace Mycompany\CurrencyRates;

use Bitrix\Main\Type\Date;
use Bitrix\Main\Diag\Debug;
use SoapClient;
use SimpleXMLElement;
use Exception;

class CBRApi
{
    private $wsdlUrl = "http://www.cbr.ru/DailyInfoWebServ/DailyInfo.asmx?WSDL";

    /**
     * Получает курсы валют на указанную дату
     * @param Date $date
     * @return array|false
     */
    public function getCursOnDate(Date $date)
    {
        $soapClient = new SoapClient($this->wsdlUrl);
        $params = [
            'On_date' => $date->format('Y-m-d')
        ];

        try {
            // Выполняем запрос к методу GetCursOnDate
            $response = $soapClient->GetCursOnDate($params);

            // Преобразуем XML-ответ в объект SimpleXMLElement
            $result = $response->GetCursOnDateResult->any;
            $xml = simplexml_load_string($result);

            // Извлекаем данные о валюте
            if (isset($xml->ValuteData->ValuteCursOnDate)) {
                $valutes = $xml->ValuteData->ValuteCursOnDate;
            } else {
                $valutes = [];
            }

            return $this->parseRates($valutes);
        } catch (Exception $e) {
            // Логируем ошибку
            Debug::writeToFile($e->getMessage(), "", "currency_rates.log");
            return false;
        }
    }

    /**
     * Парсит XML с курсами валют
     * @param SimpleXMLElement $xml
     * @return array
     */
    private function parseRates($xml)
    {
        $rates = [];
        foreach ($xml as $rate) {
            $rates[] = [
                'code' => (string)$rate->VchCode,
                'name' => (string)$rate->Vname,
                'nominal' => (int)$rate->Vnom,
                'value' => (float)$rate->Vcurs
            ];
        }
        return $rates;
    }

    /**
     * Получает список валют
     * @return array|false
     */
    public function getEnumValutes()
    {
		$date = new Date();
        $soapClient = new SoapClient($this->wsdlUrl);
        $params = [
            'On_date' => $date->format('Y-m-d')
        ];

        try {
            // Выполняем запрос к методу GetCursOnDate
            $response = $soapClient->GetCursOnDate($params);

            // Преобразуем XML-ответ в объект SimpleXMLElement
            $result = $response->GetCursOnDateResult->any;
            $xml = simplexml_load_string($result);

            // Извлекаем данные о валюте
            if (isset($xml->ValuteData->ValuteCursOnDate)) {
                $valutes = $xml->ValuteData->ValuteCursOnDate;
            } else {
                $valutes = [];
            }

            return $this->parseEnumValutes($valutes);
        } catch (Exception $e) {
            // Логируем ошибку
            Debug::writeToFile($e->getMessage(), "", "currency_rates.log");
            return false;
        }
    }

    /**
     * Парсит XML со списком валют
     * @param SimpleXMLElement $xml
     * @return array
     */
    private function parseEnumValutes($xml)
    {
        $currencies = [];
        foreach ($xml as $valute) {
            $currencies[] = [
                'VchCode' => (string)$valute->VchCode,
                'Vname' => (string)$valute->Vname
            ];
        }
        return $currencies;
    }
}
?>
