<?php
use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses('my.currencyrates', [
    'Mycompany\CurrencyRates\CBRApi' => 'lib/cbr_api.php',
    'Mycompany\CurrencyRates\Agent' => 'lib/agent.php',
    'Mycompany\CurrencyRates\CurrencyRateTable' => 'lib/currencyrate.php',
]);
?>
