<?php

/**
 * Bitrix lib iblock (webgsite.ru)
 * Библиотека облегчающая работу с инфоблоками битрикса
 *
 * @author    Falur <ienakaev@ya.ru>
 * @link      https://github.com/falur/bitrix.iblock.lib
 * @copyright 2015 - 2016 webgsite.ru
 * @license   GNU General Public License http://www.gnu.org/licenses/gpl-2.0.html
 */

namespace IblockMultipageComponent\lib;

use Bitrix\Main\Loader;
use CIBlockElement;
use CFile;
use CIBlock;

Loader::includeModule('iblock');

class Elements
{
    protected static $arSelect = [
        'ID',
        'IBLOCK_ID',
        'CODE',
        'XML_ID',
        'NAME',
        'ACTIVE',
        'DATE_ACTIVE_FROM',
        'DATE_ACTIVE_TO',
        'SORT',
        'PREVIEW_TEXT',
        'PREVIEW_TEXT_TYPE',
        'DETAIL_TEXT',
        'DETAIL_TEXT_TYPE',
        'DATE_CREATE',
        'CREATED_BY',
        'TIMESTAMP_X',
        'MODIFIED_BY',
        'TAGS',
        'IBLOCK_SECTION_ID',
        'DETAIL_PAGE_URL',
        'DETAIL_PICTURE',
        'PREVIEW_PICTURE',
        'SHOW_COUNTER',
        'PROPERTY_*'
    ];

    /**
     *
     * @param array $arFilter
     * @param array $arSort
     * @param int|boolean|null $pagination
     * @param array|boolean|null $imgCache
     * @param array $pageNavSettings
     * @return array
     */
    public static function getElements(
        array $arFilter = [],
        array $arSort = [],
        $pagination = false,
        $imgCache = false,
        array $pageNavSettings = ['name' => 'Страницы:', 'template' => '.default'])
    {
        $arSelect = self::$arSelect;
        $arResult = ['ITEMS' => [], 'PAGINATION' => ''];

        if ($pagination) {
            $arNavParams = ['nPageSize' => $pagination];
        } else {
            $arNavParams = false;
        }

        $rsElements = CIBlockElement::GetList($arSort, $arFilter, false, $arNavParams, $arSelect);

        while ($ob = $rsElements->GetNextElement()) {
            $arItem               = $ob->GetFields();
            $arItem['PROPERTIES'] = $ob->GetProperties();
            self::setImages($arItem, $imgCache);
            self::setButtons($arItem);

            $arResult['ITEMS'][] = $arItem;
        }

        if ($pagination) {
            $arResult['PAGINATION'] =
                $rsElements->GetPageNavStringEx(
                    $navComponentObject,
                    $pageNavSettings['name'],
                    $pageNavSettings['template']
                );
        }

        return $arResult;
    }

    /**
     *
     * @param array $arFilter
     * @param array|boolean|null $imgCache
     * @return type
     */
    public static function getElement(array $arFilter = [], $imgCache = false)
    {
        $arSelect = self::$arSelect;
        $arResult = [];
        $arItem   = null;

        $rsElements = CIBlockElement::GetList($arSort, $arFilter, false, false, $arSelect);

        while ($ob = $rsElements->GetNextElement()) {
            $arItem               = $ob->GetFields();
            $arItem['PROPERTIES'] = $ob->GetProperties();

            self::setImages($arItem, $imgCache);
            self::setButtons($arItem);
        }

        $arResult = $arItem;

        return $arResult;
    }

    /**
     *
     * @param array $arItem
     * @param array|boolean|null $imgCache
     */
    public static function setImages(array &$arItem, $imgCache)
    {
        $arItem['PREVIEW_PICTURE'] =
            0 < $arItem['PREVIEW_PICTURE']
            ? CFile::GetFileArray($arItem['PREVIEW_PICTURE'])
            : null;

        $arItem['DETAIL_PICTURE'] =
            0 < $arItem['DETAIL_PICTURE']
            ? CFile::GetFileArray($arItem['DETAIL_PICTURE'])
            : null;

        if ($imgCache) {
            if (!isset($imgCache['SIZE'])) {
              llc($imgCache);
              throw new \Bitrix\Main\ArgumentNullException('IMG_CACHE[\'SIZE\']');
            }
            // $img_cache_size = isset($imgCache['SIZE']) ? $imgCache['SIZE'] : $imgCache;
            $img_cache_size = $imgCache['SIZE'];
            $img_cache_type = isset($imgCache['TYPE']) ? $imgCache['TYPE'] : BX_RESIZE_IMAGE_EXACT;

            // $arItem['PREVIEW_PICTURE_CACHE'] =
            //     is_array($imgCache) && $arItem['PREVIEW_PICTURE']
            //     ?  CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], $img_cache_size, $img_cache_type)
            //     : null;

            $arItem['DETAIL_PICTURE_CACHE'] =
                is_array($imgCache) && $arItem['DETAIL_PICTURE']
                ?  CFile::ResizeImageGet($arItem['DETAIL_PICTURE'], $img_cache_size, $img_cache_type)
                : null;
        }
    }

    /**
     *
     * @param array $arItem
     */
    public static function setButtons(array &$arItem)
    {
        $arButtons = CIBlock::GetPanelButtons(
                        $arItem['IBLOCK_ID'], $arItem['ID'], 0,
                        [
                            'SECTION_BUTTONS' => false,
                            'SESSID' => false
                        ]
                    );

        $arItem['EDIT_LINK']   = $arButtons['edit']['edit_element']['ACTION_URL'];
        $arItem['DELETE_LINK'] = $arButtons['edit']['delete_element']['ACTION_URL'];
    }
}
