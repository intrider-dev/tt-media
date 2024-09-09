<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?php
// Подключаем CSS-файл
$APPLICATION->SetAdditionalCSS($templateFolder . "/style.css");
?>

<form class="currency-filter-form" method="get">
    <div>
        <label for="CURRENCY_CODE">Код валюты:</label>
        <input type="text" name="CURRENCY_CODE" value="<?= $arResult['FILTER_FIELDS']['CURRENCY_CODE'] ?>" />
    </div>
    <div>
        <label for="DATE_FROM">Дата от:</label>
        <input type="date" name="DATE_FROM" value="<?= $arResult['FILTER_FIELDS']['DATE_FROM'] ?>" />
    </div>
    <div>
        <label for="DATE_TO">Дата до:</label>
        <input type="date" name="DATE_TO" value="<?= $arResult['FILTER_FIELDS']['DATE_TO'] ?>" />
    </div>
    <div>
        <button type="submit">Фильтровать</button>
    </div>
</form>
