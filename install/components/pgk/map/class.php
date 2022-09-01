<?php

use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\UI\Toolbar\Facade\Toolbar;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class MapComponent extends \CBitrixComponent implements Controllerable
{
    public function onPrepareComponentParams($params)
    {
        if (!$params['ID']) parent::__showError('У вас не указан ИД карты.');
        return $params;
    }

    public function executeComponent()
    {
        \Bitrix\Main\Loader::includeModule('pgk.maps');
        \Bitrix\Main\Loader::includeModule('ui');

        $this->arResult['MAP_DATA'] = [
            'NAME' => $this->getMapAction()['NAME'],
            'ID' => $this->arParams['ID'],
        ];

        $id = $this->makeToolbar();

        $toolbar = Bitrix\UI\Toolbar\Manager::getInstance()->getToolbarById($id)->renderRightButtons();

        $this->includeComponentTemplate();
    }

    public function makeToolbar()
    {
        $addButton = new \Bitrix\UI\Buttons\Button([
            "color" => \Bitrix\UI\Buttons\Color::PRIMARY,
            "icon" => \Bitrix\UI\Buttons\Icon::DONE,
            "click" => new \Bitrix\UI\Buttons\JsCode(
                "BX.Vue.Map.Complete()"
            ),
            "text" => "Готово"
        ]);
        Toolbar::addButton($addButton);

        $addButton = new \Bitrix\UI\Buttons\Button([
            "text" => "Добавить",
            "color" => \Bitrix\UI\Buttons\Color::PRIMARY,
            "icon" => \Bitrix\UI\Buttons\Icon::ADD,
            "menu" => [
                "items" => [
                    [
                        "text" => "Иконка",
                        "onclick" => new \Bitrix\UI\Buttons\JsCode(
                            "BX.Vue.Map.showIconAdd('icon')"
                        )
                    ],
                    ["delimiter" => true],
                    [
                        "text" => "Произвольная площадь",
                        "onclick" => new \Bitrix\UI\Buttons\JsCode(
                            "BX.Vue.Map.showIconAdd('square')"
                        )
                    ],
                    [
                        "text" => "Окружность",
                        "onclick" => new \Bitrix\UI\Buttons\JsCode(
                            "BX.Vue.Map.showIconAdd('circle')"
                        )
                    ],
                ],
            ],
        ]);
        Toolbar::addButton($addButton);

        $addButton = new \Bitrix\UI\Buttons\Button([
            "color" => \Bitrix\UI\Buttons\Color::PRIMARY,
            "icon" => \Bitrix\UI\Buttons\Icon::REMOVE,
            "click" => new \Bitrix\UI\Buttons\JsCode(
                "BX.Vue.Map.deleteIconMod()"
            ),
            "text" => "Удалить"
        ]);
        $addButton->addClass('button__delete');
        Toolbar::addButton($addButton);

        return Toolbar::getId();
    }

    public function configureActions()
    {
        return [];
    }

    public function getMapAction($id = null)
    {
        $id = $id ?? $this->arParams['ID'];
        \Bitrix\Main\Loader::includeModule('pgk.maps');

        $arMap = \PGK\Maps\MapsTable::query()
            ->setSelect(['MAP', 'NAME'])
            ->where('ID', $id)
            ->fetch();

        if (!$arMap) parent::__showError('Карта с таким названиям не найдена.');

        $map = CFile::GetFileArray($arMap['MAP']);
        $map['ID_BD'] = $id;
        $map['ICONS'] = $this->getIcons($id);
        $map['NAME'] = $arMap['NAME'];

        return $map;
    }

    public function getIcons($mapID)
    {
        return \PGK\Maps\IconsTable::query()
            ->setSelect(['*'])
            ->where('MAP', $mapID)
            ->fetchAll();
    }

    public function addIconAction($icon)
    {
        \Bitrix\Main\Loader::includeModule('pgk.maps');

        $result = \PGK\Maps\IconsTable::add([
            'NAME' => $icon['NAME'],
            'MAP' => $icon['MAP'],
            'DATA' => $icon['DATA'],
            'MAP_TO' => $icon['ID_TO'] ?? 0,
        ]);

        return $result->getId();
    }

    public function deleteIconAction($id)
    {
        \Bitrix\Main\Loader::includeModule('pgk.maps');

        return \PGK\Maps\IconsTable::delete($id);
    }

    public function viewInSliderAction(){
        return new \Bitrix\Main\Engine\Response\Component('bitrix:ui.sidepanel.wrapper', '', [
            'POPUP_COMPONENT_USE_BITRIX24_THEME' => 'Y',
            'POPUP_COMPONENT_NAME' => 'pgk:map',
            'POPUP_COMPONENT_TEMPLATE_NAME' => '',
            'POPUP_COMPONENT_PARAMS' => [
                'ID' => 1,
            ],
            'USE_UI_TOOLBAR' => 'Y',
            'USE_PADDING' => false,
            'PLAIN_VIEW' => false,
            'PAGE_MODE' => false,
            'PAGE_MODE_OFF_BACK_URL' => "/bizproc/processes/51/element/0/0/?list_section_id=",
        ]);
    }
}
