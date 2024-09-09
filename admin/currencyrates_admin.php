<?php
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Mycompany\CurrencyRates\CurrencyRateTable;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

// Проверка прав доступа
$POST_RIGHT = $APPLICATION->GetGroupRight("my.currencyrates");
if ($POST_RIGHT == "D") {
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
}

// Подключаем модуль
if (!Loader::includeModule('my.currencyrates')) {
    die(Loc::getMessage('MODULE_NOT_INSTALLED'));
}

$filePathLocal = $_SERVER["DOCUMENT_ROOT"] . "/local/modules/my.currencyrates/admin/currencyrates_admin.php";
$filePathBitrix = $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/my.currencyrates/admin/currencyrates_admin.php";

// Проверка наличия файла в /local и /bitrix, и загрузка соответствующего языкового файла
if (file_exists($filePathLocal)) {
    Loc::loadMessages($filePathLocal);
} elseif (file_exists($filePathBitrix)) {
    Loc::loadMessages($filePathBitrix);
} else {
    echo "Ошибка при загрузке языковых файлов.";
}

$APPLICATION->SetTitle(Loc::getMessage("CURRENCY_RATES_TITLE"));

$sTableID = "tbl_currency_rates";
$oSort = new CAdminSorting($sTableID, "ID", "desc");
$lAdmin = new CAdminList($sTableID, $oSort);

// Обработка действий (удаление, редактирование)
if ($lAdmin->EditAction() && $POST_RIGHT == "W") {
    foreach ($FIELDS as $ID => $arFields) {
        if (!$lAdmin->IsUpdated($ID)) continue;

        $ID = intval($ID);
        $result = CurrencyRateTable::update($ID, $arFields);
        if (!$result->isSuccess()) {
            $lAdmin->AddUpdateError("Ошибка при обновлении записи с ID ".$ID, $ID);
        }
    }
}

// Групповые действия (удаление)
if (($arID = $lAdmin->GroupAction()) && $POST_RIGHT == "W") {
    if ($_REQUEST['action_target'] == 'selected') {
        $rsData = CurrencyRateTable::getList();
        while ($arRes = $rsData->fetch()) {
            $arID[] = $arRes['ID'];
        }
    }

    foreach ($arID as $ID) {
        if (strlen($ID) <= 0) continue;

        $ID = intval($ID);

        switch ($_REQUEST['action']) {
            case "delete":
                if (!check_bitrix_sessid()) break;
                
                $result = CurrencyRateTable::delete($ID);
                if (!$result->isSuccess()) {
                    $lAdmin->AddGroupError(Loc::getMessage("CURRENCY_RATES_DELETE_ERROR"), $ID);
                }
                break;
        }
    }
}

// Получение данных для отображения
$rsData = CurrencyRateTable::getList([
    'select' => ['ID', 'CODE', 'DATE', 'COURSE'],
    'order' => [$by => $order]
]);

$rsData = new CAdminResult($rsData, $sTableID);
$lAdmin->NavText($rsData->GetNavPrint(Loc::getMessage("CURRENCY_RATES_NAV")));

// Добавление заголовков таблицы
$lAdmin->AddHeaders([
    ['id' => 'ID', 'content' => 'ID', 'sort' => 'ID', 'default' => true],
    ['id' => 'CODE', 'content' => Loc::getMessage("CURRENCY_RATES_CODE"), 'sort' => 'CODE', 'default' => true],
    ['id' => 'DATE', 'content' => Loc::getMessage("CURRENCY_RATES_DATE"), 'sort' => 'DATE', 'default' => true],
    ['id' => 'COURSE', 'content' => Loc::getMessage("CURRENCY_RATES_COURSE"), 'sort' => 'COURSE', 'default' => true],
]);

// Добавление строк данных
while ($arRes = $rsData->NavNext(true, "f_")) {
    $row =& $lAdmin->AddRow($f_ID, $arRes);
    $row->AddInputField("CODE", ["size" => 20]);
    $row->AddInputField("COURSE", ["size" => 10]);
    $row->AddCalendarField("DATE");

    $arActions = [];
    $arActions[] = [
        "ICON" => "edit",
        "TEXT" => Loc::getMessage("CURRENCY_RATES_EDIT"),
        "ACTION" => $lAdmin->ActionRedirect("currencyrates_edit.php?ID=" . $f_ID)
    ];
    $arActions[] = [
        "ICON" => "delete",
        "TEXT" => Loc::getMessage("CURRENCY_RATES_DELETE"),
        "ACTION" => "if(confirm('".Loc::getMessage("CURRENCY_RATES_CONFIRM_DELETE")."')) ".$lAdmin->ActionDoGroup($f_ID, "delete")
    ];

    $row->AddActions($arActions);
}

// Добавление групповых действий
$lAdmin->AddGroupActionTable([
    "delete" => Loc::getMessage("CURRENCY_RATES_DELETE"),
]);

// Добавление контекстного меню
$aContext = [
    [
        "TEXT" => Loc::getMessage("CURRENCY_RATES_ADD"),
        "LINK" => "currencyrates_edit.php?lang=".LANG,
        "TITLE" => Loc::getMessage("CURRENCY_RATES_ADD_TITLE"),
        "ICON" => "btn_new"
    ],
    [
        "TEXT" => Loc::getMessage("CURRENCY_RATES_SETTINGS"), // Название кнопки
        "LINK" => "currencyrates_settings.php?lang=".LANG,    // Ссылка на страницу настроек
        "TITLE" => Loc::getMessage("CURRENCY_RATES_SETTINGS_TITLE"), // Подсказка для кнопки
        "ICON" => "btn_settings" // Иконка
    ]
];

$lAdmin->AddAdminContextMenu($aContext);
$lAdmin->CheckListMode();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
$lAdmin->DisplayList();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
