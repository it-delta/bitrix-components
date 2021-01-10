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
            'NAME' => "Тип информационного блока (используется только для проверки)",
            'TYPE' => 'LIST',
            'VALUES' => $arTypesEx,
            'REFRESH' => 'Y',
        ),
        'IBLOCK_ID' => array(
            'PARENT' => 'BASE',
            'NAME' => "Код информационного блока",
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y',
        ),
        'FILTER_NAME' => array(
            'PARENT' => 'DATA_SOURCE',
            'NAME' => "Имя переменной фильтра",
            'TYPE' => 'STRING',
            "DEFAULT" => "arrFilter1",
            "MULTIPLE" => "N",
            "COLS" => 25
        ),
        'ELEMENTS_SORT_BY_1' => array(
            'PARENT' => 'DATA_SOURCE',
            'NAME' => "Сортировка элементов №1",
            'TYPE' => 'LIST',
            'VALUES' => $arSortFields,
        ),
        'ELEMENTS_SORT_ORDER_1' => array(
            'PARENT' => 'DATA_SOURCE',
            'NAME' => "Направление сортировки №1",
            'TYPE' => 'LIST',
            'DEFAULT' => 'ASC',
            'VALUES' => $arSorts,
        ),
        'ELEMENTS_SORT_BY_2' => array(
            'PARENT' => 'DATA_SOURCE',
            'NAME' => "Сортировка элементов №2",
            'TYPE' => 'LIST',
            'VALUES' => $arSortFields,
        ),
        'ELEMENTS_SORT_ORDER_2' => array(
            'PARENT' => 'DATA_SOURCE',
            'NAME' => "Направление сортировки №2",
            'TYPE' => 'LIST',
            'DEFAULT' => 'ASC',
            'VALUES' => $arSorts,
        ),
        'SECTION_SORT_BY' => array(
            'PARENT' => 'DATA_SOURCE',
            'NAME' => "Сортировка секций",
            'TYPE' => 'LIST',
            'VALUES' => $arSortFields,
        ),
        'SECTION_SORT_ORDER' => array(
            'PARENT' => 'DATA_SOURCE',
            'NAME' => "Направление сортировки секций",
            'TYPE' => 'LIST',
            'DEFAULT' => 'ASC',
            'VALUES' => $arSorts,
        ),
        'PAGINATION_COUNT' => array(
            'PARENT' => 'VISUAL',
            'NAME' => "Количество элементов на странице",
            'TYPE' => 'STRING',
            'DEFAULT' => '10',
        ),
        'PAGINATION_TEMPLATE' => array(
            'PARENT' => 'VISUAL',
            'NAME' => "Шаблон пагинации",
            'TYPE' => 'STRING',
            'DEFAULT' => '.default',
        ),
        'PAGINATION_TITLE' => array(
            'PARENT' => 'VISUAL',
            'NAME' => "Заголовок пагинации",
            'TYPE' => 'STRING',
            'DEFAULT' => 'Страница:',
        ),
        'ACTIVE_DATE' => array(
            'PARENT' => 'ADDITIONAL_SETTINGS',
            'NAME' => "Только элементы в диапазоне даты активности",
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
        ),
        'RAND_ELEMENTS' => array(
            'PARENT' => 'ADDITIONAL_SETTINGS',
            'NAME' => Loc::getMessage('ITD_RAND_ELEMENTS'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
        ),
        'ADD_SECTION_IN_BREADCRUMBS' => array(
            'PARENT' => 'ADDITIONAL_SETTINGS',
            'NAME' => "Добавлять секцию в хлебные крошки",
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
        ),
        "CACHE_TIME" => array(
           "DEFAULT" => "3600"
        ),
        'ADD_CACHE_STRING' => array(
            'PARENT' => 'CACHE_SETTINGS',
            'NAME' => 'Доп. строка для кеширования',
            'TYPE' => 'STRING',
            "DEFAULT" => "",
            "MULTIPLE" => "N",
        ),
        'SEF_URL' => array(
            'PARENT' => 'SEF_MODE',
            'NAME' => 'Корень ЧПУ',
            'TYPE' => 'STRING',
            "DEFAULT" => "",
        ),
    ),
);
?>
