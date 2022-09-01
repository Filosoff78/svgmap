<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Новая страница");

//$APPLICATION->IncludeComponent(
//    "pgk:map",
//    "",
//    ['ID' => 1]
//);

$APPLICATION->IncludeComponent(
    'bitrix:ui.sidepanel.wrapper',
    '',
    [
        'POPUP_COMPONENT_USE_BITRIX24_THEME' => 'Y',
        'DEFAULT_THEME_ID' => 'light:mail',
        'POPUP_COMPONENT_NAME' => 'pgk:map',
        'POPUP_COMPONENT_TEMPLATE_NAME' => '',
        'POPUP_COMPONENT_PARAMS' => [
            'ID' => 1,
        ],
        'USE_UI_TOOLBAR' => 'Y',
        'USE_PADDING' => false,
        'PLAIN_VIEW' => false,
        'PAGE_MODE' => false,
        'PAGE_MODE_OFF_BACK_URL' => "/stream/",
    ]);

?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
