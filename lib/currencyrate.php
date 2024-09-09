<?php
namespace Mycompany\CurrencyRates;

use Bitrix\Main\Entity;
use Bitrix\Main\Type;

class CurrencyRateTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'currency_rates';
    }

    public static function getMap()
    {
        return [
            new Entity\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true
            ]),
            new Entity\StringField('CODE', [
                'required' => true,
                'validation' => function () {
                    return [new Entity\Validator\Length(null, 3)];
                }
            ]),
            new Entity\DatetimeField('DATE', [
                'required' => true
            ]),
            new Entity\FloatField('COURSE', [
                'required' => true
            ]),
        ];
    }
}
?>
