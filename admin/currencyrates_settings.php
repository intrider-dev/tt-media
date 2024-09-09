<?php
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;
use Mycompany\CurrencyRates\CBRApi;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

if (!Loader::includeModule('my.currencyrates')) {
    die('Модуль не установлен');
}

$filePathLocal = $_SERVER["DOCUMENT_ROOT"] . "/local/modules/my.currencyrates/admin/currencyrates_settings.php";
$filePathBitrix = $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/my.currencyrates/admin/currencyrates_settings.php";

// Проверка наличия файла в /local и /bitrix, и загрузка соответствующего языкового файла
if (file_exists($filePathLocal)) {
    Loc::loadMessages($filePathLocal);
} elseif (file_exists($filePathBitrix)) {
    Loc::loadMessages($filePathBitrix);
} else {
    echo "Ошибка при загрузке языковых файлов.";
}

$APPLICATION->SetTitle(Loc::getMessage("CURRENCY_RATES_SETTINGS_TITLE"));

$cbrApi = new CBRApi();
$allCurrencies = $cbrApi->getEnumValutes(); // Метод получения справочника валют

$selectedCurrencies = Option::get('my.currencyrates', 'selected_currencies', '');

if ($_SERVER["REQUEST_METHOD"] == "POST" && check_bitrix_sessid()) {
    $selectedCurrencies = isset($_POST['CURRENCIES']) ? implode(',', $_POST['CURRENCIES']) : '';
    Option::set('my.currencyrates', 'selected_currencies', $selectedCurrencies);
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>

<form method="POST">
    <?=bitrix_sessid_post()?>
    <table class="adm-detail-content-table edit-table">
        <tr>
            <td><?=Loc::getMessage("CURRENCY_RATES_SELECT_CURRENCIES")?></td>
            <td>
                <select name="CURRENCIES[]" multiple>
                    <?php foreach ($allCurrencies as $currency): ?>
                        <option value="<?=htmlspecialcharsbx($currency['VchCode'])?>" <?=in_array($currency['VchCode'], explode(',', $selectedCurrencies)) ? 'selected' : ''?>>
                            <?=htmlspecialcharsbx($currency['Vname'])?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
    </table>
    <!-- Кнопка "Сохранить" с классом для зеленого цвета -->
    <input type="submit" class="adm-btn adm-btn-green" value="<?=Loc::getMessage("CURRENCY_RATES_SAVE_BTN")?>">
        
    <!-- Кнопка для сброса формы с помощью JavaScript -->
    <input type="button" class="adm-btn" value="<?=Loc::getMessage("CURRENCY_RATES_RESET")?>" onclick="document.querySelector('form').querySelectorAll('select').forEach(select => select.selectedIndex = -1);">

    <!-- Кнопка "Назад" для возврата на предыдущую страницу -->
    <input type="button" class="adm-btn" value="<?=Loc::getMessage("CURRENCY_RATES_BACK")?>" onclick="window.location.href='/bitrix/admin/currencyrates_admin.php?lang=<?=LANG?>'">
</form>

<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
