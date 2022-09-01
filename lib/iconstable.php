<?php
namespace PGK\Maps;

use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields\IntegerField,
    Bitrix\Main\ORM\Fields\StringField,
    Bitrix\Main\ORM\Fields\Relations\Reference,
    Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\ORM\Fields\FloatField;

Loc::loadMessages(__FILE__);

class IconsTable extends DataManager
{
    public static function getTableName()
    {
        return 'pgk_map_icons';
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

            (new ReferenceField(
                'MAP_REFS',
                'PGK\Maps\IconsTable',
                Join::on('this.MAP', 'ref.ID')
            )),

            (new StringField('DATA'))
                ->configureRequired()
                ->configureTitle('Дата'),

            (new IntegerField('MAP_TO'))
                ->configureTitle('Ведет на'),
        ];
    }
}
