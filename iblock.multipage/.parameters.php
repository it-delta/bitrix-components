<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

if( !Loader::includeModule("iblock") ) {
    throw new \Exception('Не загружены модули необходимые для работы компонента');
}

// Типы инфоблоков
$arTypesEx = CIBlockParameters::GetIBlockTypes(['-' => ' ']);

// Инфоблоки
$arIBlocks = [];
$dbIblock = CIBlock::GetList(
    ['SORT' => 'ASC'],
    [
        'SITE_ID' => $_REQUEST['site'],
        'TYPE' => $arCurrentValues['IBLOCK_TYPE'] != '-'
                    ? $arCurrentValues['IBLOCK_TYPE']
                    : ''
    ]
);
while ($arRes = $dbIblock->Fetch()) {
    $arIBlocks[$arRes['ID']] = $arRes['NAME'];
}

// Сортировка типы
$arSorts      = ['ASC' => Loc::getMessage('T_IBLOCK_DESC_ASC'), 'DESC' => Loc::getMessage('T_IBLOCK_DESC_DESC')];
$arSortFields = [
    'ID'             => Loc::getMessage('T_IBLOCK_DESC_FID'),
    'NAME'           => Loc::getMessage('T_IBLOCK_DESC_FNAME'),
    'ACTIVE_FROM'    => Loc::getMessage('T_IBLOCK_DESC_FACT'),
    'SORT'           => Loc::getMessage('T_IBLOCK_DESC_FSORT'),
    'TIMESTAMP_X'    => Loc::getMessage('T_IBLOCK_DESC_FTSAMP')
];

// Свойства
$arProperty_LNS = [];
$dbProp = CIBlockProperty::GetList(
    ['sort' => 'asc', 'name' => 'asc'],
    [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => isset($arCurrentValues['IBLOCK_ID'])
                        ? $arCurrentValues['IBLOCK_ID']
                        : $arCurrentValues['ID']
    ]
);

while ($arRes = $dbProp->Fetch()) {
    $arProperty[$arr['CODE']] = '['.$arr['CODE'].'] ' . $arr['NAME'];

    if (in_array($arr['PROPERTY_TYPE'], ['L', 'N', 'S'])) {
        $arProperty_LNS[$arr['CODE']] = '['.$arr['CODE'].'] '.$arr['NAME'];
    }
}

$arComponentParameters = array(
    'PARAMETERS' => array(
        'IBLOCK_TYPE' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('T_IBLOCK_DESC_LIST_TYPE'),
            'TYPE' => 'LIST',
            'VALUES' => $arTypesEx,
            'DEFAULT' => 'news',
            'REFRESH' => 'Y',
        ),
        'IBLOCK_ID' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('T_IBLOCK_DESC_LIST_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks,
            'DEFAULT' => '={$_REQUEST["ID"]}',
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y',
        ),
        'FILTER_NAME' => array(
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('T_IBLOCK_FILTER'),
            'TYPE' => 'STRING',
            "DEFAULT" => "arrFilter1",
            "MULTIPLE" => "N",
            "COLS" => 25
        ),
        'ACTIVE_DATE' => array(
            'PARENT' => 'ADDITIONAL_SETTINGS',
            'NAME' => Loc::getMessage('ITD_ACTIVE_DATE'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
        ),
        'SORT_BY1' => array(
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('T_IBLOCK_DESC_IBORD1'),
            'TYPE' => 'LIST',
            'DEFAULT' => 'ACTIVE_FROM',
            'VALUES' => $arSortFields,
            'ADDITIONAL_VALUES' => 'Y',
        ),
        'SORT_ORDER1' => array(
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('T_IBLOCK_DESC_IBBY1'),
            'TYPE' => 'LIST',
            'DEFAULT' => 'DESC',
            'VALUES' => $arSorts,
            'ADDITIONAL_VALUES' => 'Y',
        ),
        'SORT_BY2' => array(
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('T_IBLOCK_DESC_IBORD2'),
            'TYPE' => 'LIST',
            'DEFAULT' => 'SORT',
            'VALUES' => $arSortFields,
            'ADDITIONAL_VALUES' => 'Y',
        ),
        'SORT_ORDER2' => array(
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('T_IBLOCK_DESC_IBBY2'),
            'TYPE' => 'LIST',
            'DEFAULT' => 'ASC',
            'VALUES' => $arSorts,
            'ADDITIONAL_VALUES' => 'Y',
        ),
        'PAGE_ELEMENT_COUNT' => array(
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('T_IBLOCK_DESC_LIST_CONT'),
            'TYPE' => 'STRING',
            'DEFAULT' => '10',
        ),
        'RAND_ELEMENTS' => array(
            'PARENT' => 'ADDITIONAL_SETTINGS',
            'NAME' => Loc::getMessage('ITD_RAND_ELEMENTS'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
        ),
        // кеширование
        'CACHE_TIME' => array(
        ),
        'ADD_CACHE_STRING' => array(
            'PARENT' => 'CACHE_SETTINGS',
            'NAME' => 'Доп. строка для кеширования',
            'TYPE' => 'STRING',
            "DEFAULT" => "",
            "MULTIPLE" => "N",
        ),
    ),
);
?>
