<?php
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;
use Mycompany\CurrencyRates\CurrencyRateTable;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

if (!Loader::includeModule('my.currencyrates')) {
    die('Модуль не установлен');
}

$filePathLocal = $_SERVER["DOCUMENT_ROOT"] . "/local/modules/my.currencyrates/admin/currencyrates_edit.php";
$filePathBitrix = $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/my.currencyrates/admin/currencyrates_edit.php";

// Проверка наличия файла в /local и /bitrix, и загрузка соответствующего языкового файла
if (file_exists($filePathLocal)) {
    Loc::loadMessages($filePathLocal);
} elseif (file_exists($filePathBitrix)) {
    Loc::loadMessages($filePathBitrix);
} else {
    echo "Ошибка при загрузке языковых файлов.";
}

$ID = intval($_REQUEST["ID"]); // ID записи
$message = null;
$isUpdated = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && check_bitrix_sessid()) {
    $arFields = [
        'CODE' => $_POST['CODE'],
        'DATE' => new \Bitrix\Main\Type\DateTime($_POST['DATE']),
        'COURSE' => $_POST['COURSE']
    ];

    if ($ID > 0) {
        // Обновление записи
        $result = CurrencyRateTable::update($ID, $arFields);
        if ($result->isSuccess()) {
            $isUpdated = true;
        } else {
            $errors = implode(", ", $result->getErrorMessages()); // Получаем ошибки
            $message = new CAdminMessage(Loc::getMessage("CURRENCY_RATES_EDIT_ERROR") . ": " . $errors);
        }
    } else {
        // Добавление новой записи
        $result = CurrencyRateTable::add($arFields);
        if ($result->isSuccess()) {
            $ID = $result->getId();
            $isUpdated = true;
        } else {
            $errors = implode(", ", $result->getErrorMessages()); // Получаем ошибки
            $message = new CAdminMessage(Loc::getMessage("CURRENCY_RATES_ADD_ERROR") . ": " . $errors);
        }
    }

    // Перенаправление после успешного сохранения
    if ($isUpdated) {
        LocalRedirect("/bitrix/admin/currencyrates_admin.php?lang=" . LANG);
    }
}

// Получение данных для редактирования
if ($ID > 0) {
    $data = CurrencyRateTable::getById($ID)->fetch();
    if (!$data) {
        $ID = 0;
    }
}

$APPLICATION->SetTitle(($ID > 0 ? Loc::getMessage("CURRENCY_RATES_EDIT_TITLE") : Loc::getMessage("CURRENCY_RATES_ADD_TITLE")));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

if ($message) {
    echo $message->Show();
}
?>

<form method="POST">
    <?=bitrix_sessid_post()?>
    <input type="hidden" name="ID" value="<?=$ID?>">

    <table class="adm-detail-content-table edit-table">
        <tbody>
        <tr>
            <td><?=Loc::getMessage("CURRENCY_RATES_CODE")?>:</td>
            <td><input type="text" name="CODE" value="<?=htmlspecialcharsbx($data['CODE'])?>" size="20"></td>
        </tr>
        <tr>
            <td><?=Loc::getMessage("CURRENCY_RATES_DATE")?>:</td>
            <td><input type="text" name="DATE" value="<?=htmlspecialcharsbx($data['DATE'])?>" size="20"></td>
        </tr>
        <tr>
            <td><?=Loc::getMessage("CURRENCY_RATES_COURSE")?>:</td>
            <td><input type="text" name="COURSE" value="<?=htmlspecialcharsbx($data['COURSE'])?>" size="20"></td>
        </tr>
        </tbody>
    </table>

    <div style="margin-top: 20px;"> 
        <!-- Кнопка "Сохранить" с классом для зеленого цвета -->
        <input type="submit" class="adm-btn adm-btn-green" value="<?=($ID > 0 ? Loc::getMessage("CURRENCY_RATES_SAVE") : Loc::getMessage("CURRENCY_RATES_ADD"))?>">
        
        <!-- Кнопка для сброса формы с помощью JavaScript -->
        <input type="button" class="adm-btn" value="<?=Loc::getMessage("CURRENCY_RATES_RESET")?>" onclick="document.querySelector('form').querySelectorAll('input[type=\'text\']').forEach(input => input.value = '');">

        <!-- Кнопка "Назад" для возврата на предыдущую страницу -->
        <input type="button" class="adm-btn" value="<?=Loc::getMessage("CURRENCY_RATES_BACK")?>" onclick="window.location.href='/bitrix/admin/currencyrates_admin.php?lang=<?=LANG?>'">
    </div>
</form>

<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>
