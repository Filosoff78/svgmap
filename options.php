<?php
defined('B_PROLOG_INCLUDED') || die;

use \Bitrix\Main\Loader,
    \Bitrix\Main\Config\Option,
    PGK\Maps\RenderOptions;

global $APPLICATION, $USER;

if (!$USER->IsAdmin()) {
    return;
}

$request = \Bitrix\Main\HttpApplication::getInstance()->getContext()->getRequest();

$myModuleID = $request['mid'];
Loader::includeModule($myModuleID);

//region options

$tabs[] = array(
    'DIV' => 'general',
    'TAB' => 'ПГК: Карты',
    'TITLE' => 'Основные настройки'
);


$options['general'] = [
    [
        'NAME_MAP',
        'Идентификатор:',
        null,
        array("text", null)
    ],
    [
        'MAPS',
        'Карта:',
        null,
        array("filedownload", null)
    ],
    [
        'TABLES',
        'Карты:',
        null,
        array("tables", null)
    ],
];

//endregion options

if (check_bitrix_sessid() && strlen($_POST['save']) > 0) {
    $nameMap = $request->getPost("NAME_MAP");
    $idMap = $request->getPost("MAPS");

    if (!$nameMap || !$idMap) {
        LocalRedirect($APPLICATION->GetCurPageParam().'&status=false&error=Вы не заполнили все поля');
    }
    else {
        CAdminMessage::ShowNote('Карта успешно добавлена.');
        \PGK\Maps\MapsTable::add([
            'NAME' => $nameMap,
            'MAP' => $idMap,
        ]);
    }
    LocalRedirect($APPLICATION->GetCurPageParam().'&status=true');
}
if ($request->get("status") === 'false') {
    CAdminMessage::ShowMessage($request->get("error"));
}
if ($request->get("status") === 'true') {
    CAdminMessage::ShowNote('Карта успешно добавлена.');
}
/*
 * отрисовка формы
 */
$tabControl = new CAdminTabControl('tabControl', $tabs);
$tabControl->Begin();
?>

<form method="POST"
      action="<? echo $APPLICATION->GetCurPage() ?>?mid=<?= htmlspecialcharsbx($mid) ?>&lang=<?= LANGUAGE_ID ?>" id="baseexchange_form">
    <?php
    foreach($options as $option){
        $tabControl->BeginNextTab();
        (new RenderOptions())->__AdmSettingsDrawList($myModuleID, $option);
    }
    $tabControl->Buttons(array('btnApply' => false, 'btnCancel' => false, 'btnSaveAndAdd' => false));
    echo bitrix_sessid_post();
    $tabControl->End();
    ?>
</form>

<style>
    .webform-field-upload {
        line-height: 20px !important;
    }
    .webform-button-replace {
        padding-left: 155px !important;
    }
    .webform-button-upload {
        display: none !important;
    }
</style>
