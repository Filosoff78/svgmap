<?php

namespace PGK\Maps\Events;

class Main
{
    public static function onEpilogHandler()
    {
        /*
         * #Олегпоменяй 51 на свой ид списка
         */
        if($_SERVER['SCRIPT_URL'] === '/bizproc/processes/51/element/0/0/') {
            \Bitrix\Main\Page\Asset::getInstance()->addJs('/local/modules/pgk.maps/lib/events/js/script.js');
        }
    }
}
