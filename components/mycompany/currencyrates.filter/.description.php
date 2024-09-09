<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = [
    "NAME" => "Фильтр курсов валют",
    "DESCRIPTION" => "Компонент для фильтрации курсов валют",
    "PATH" => [
        "ID" => "mycompany",
        "CHILD" => [
            "ID" => "currencyrates",
            "NAME" => "Курсы валют"
        ],
    ],
];
?>
