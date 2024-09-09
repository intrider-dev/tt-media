<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?php
// Подключаем CSS-файл
$APPLICATION->SetAdditionalCSS($templateFolder . "/style.css");
?>

<table class="currency-rate-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Код валюты</th>
            <th>Дата</th>
            <th>Курс</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($arResult['ITEMS'] as $item): ?>
            <tr>
                <td><?= $item['ID'] ?></td>
                <td><?= $item['CODE'] ?></td>
                <td><?= $item['DATE']->toString() ?></td>
                <td><?= $item['COURSE'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Вывод постраничной навигации через встроенный компонент -->
<?php
if ($arResult['NAV_STRING']) {
    $APPLICATION->IncludeComponent(
        'bitrix:main.pagenavigation',
        '',
        array(
            'NAV_OBJECT' => $arResult['NAV_STRING'],
            'SEF_MODE' => 'N',
        ),
        false
    );
}
?>
