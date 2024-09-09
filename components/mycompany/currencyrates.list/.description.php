<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = [
    "NAME" => "Список курсов валют",
    "DESCRIPTION" => "Компонент для вывода списка курсов валют с постраничной навигацией",
    "PATH" => [
        "ID" => "mycompany",
        "CHILD" => [
            "ID" => "currencyrates",
            "NAME" => "Курсы валют"
        ],
    ],
];
?>
