<?php
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$aMenu = [
    [
        "parent_menu" => "global_menu_services", // Раздел "Сервисы"
        "section" => "my_currencyrates",
        "sort" => 100, // Порядок сортировки
        "text" => Loc::getMessage("MY_CURRENCYRATES_MENU_TITLE"), // Название пункта меню
        "title" => Loc::getMessage("MY_CURRENCYRATES_MENU_TITLE"), // Всплывающая подсказка
        "url" => "currencyrates_admin.php?lang=" . LANGUAGE_ID, // URL на страницу модуля
        "icon" => "currencyrates_menu_icon", // Иконка для меню
        "page_icon" => "currencyrates_page_icon", // Иконка для страницы
        "items_id" => "menu_currencyrates", // Идентификатор
        "items" => [], // Подпункты меню, если нужны
    ]
];

return $aMenu;
?>
