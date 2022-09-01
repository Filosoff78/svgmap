<?php
namespace PGK\Maps;

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields\IntegerField,
    Bitrix\Main\ORM\Fields\StringField,
    Bitrix\Main\ORM\Fields\TextField,
    Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\ORM\Fields\ObjectField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

Loc::loadMessages(__FILE__);

class MapsTable extends DataManager
{
    public static function getTableName()
    {
        return 'pgk_maps';
    }

    public static function getMap()
    {
        return [
            (new IntegerField('ID'))
                ->configurePrimary()
                ->configureAutocomplete()
                ->configureTitle('ID'),

            (new StringField('NAME'))
                ->configureRequired()
                ->configureTitle('Название'),

            (new IntegerField('MAP'))
                ->configureRequired()
                ->configureTitle('Карта'),
        ];
    }
}
