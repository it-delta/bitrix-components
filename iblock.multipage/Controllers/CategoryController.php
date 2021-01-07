<?php

namespace IblockMultipageComponent\Controllers;

use Bitrix\Iblock\InheritedProperty\SectionValues;
use Falur\Bitrix\Iblock\Sections;
use Falur\Bitrix\Iblock\Elements;
use CDBResult;

class CategoryController extends BaseController
{
    protected $pagination;

    public function indexAction()
    {
        global $APPLICATION;

        \CPageOption::SetOptionString('main', 'nav_page_in_session', 'N');

        $filter_get = isset($this->bitrix->arParams['FILTER'])
                      ? $this->bitrix->arParams['FILTER']
                      : [];

        $pages_count = $this->bitrix->arParams['PAGINATION']['COUNT'] ? : 10;
        $nav         = CDBResult::NavStringForCache($pages_count);
        $cache_id    = $APPLICATION->GetCurDir() . $nav.  implode('', $filter_get);

        if ($this->bitrix->StartResultCache(false, $cache_id)) {
            $this->bitrix->arResult['SECTION'] = $this->getSection();

            if (empty($this->bitrix->arResult['SECTION'])) {
                return $this->error404();
            }

            $this->bitrix->arResult['ITEMS']      = $this->getElements();
            $this->bitrix->arResult['SECTIONS']   = $this->getSections();
            $this->bitrix->arResult['PAGINATION'] = $this->getPagination();

            $this->bitrix->arResult['SECTION_PATH'] = Sections::getPath(
                $this->bitrix->arParams['IBLOCK_ID'],
                $this->bitrix->arResult['SECTION']['IBLOCK_SECTION_ID']
            );

            $this->bitrix->arResult['IPROPERTY_VALUES'] = (new SectionValues(
                $this->bitrix->arResult['SECTION']['IBLOCK_ID'],
                $this->bitrix->arResult['SECTION']['ID']
            ))->getValues();

            $this->bitrix->SetResultCacheKeys(['SECTION', 'IPROPERTY_VALUES', 'SECTION_PATH']);
            $this->bitrix->IncludeComponentTemplate('category');
        }

        $this->setMetaInfo();
    }

    public function getPagination()
    {
        return $this->pagination;
    }

    /**
     * Получить информацию по текущей категории
     *
     * @return array
     */
    protected function getSection()
    {
        $section_code = $this->slim->router->getCurrentRoute()->getParam('category');

        return Sections::getSection(
            [
                'IBLOCK_ID' => $this->bitrix->arParams['IBLOCK_ID'],
                'ACTIVE'    => 'Y',
                'CODE'      => $section_code
            ],
            $this->bitrix->arParams['IMG_CACHE']['CATEGORIES']
        );
    }

    /**
     * Получить дочерние категории
     * 
     * @return array
     */
    protected function getSections()
    {
        $sections = Sections::getSections(
            [
                'IBLOCK_ID'     => $this->bitrix->arParams['IBLOCK_ID'],
                'ACTIVE'        => 'Y',
                'GLOBAL_ACTIVE' => 'Y',
                'CNT_ACTIVE'    => 'Y',
                'SECTION_ID'    => $this->bitrix->arResult['SECTION']['ID']
            ],
            $this->bitrix->arParams['SORT']['CATEGORIES'],
            $this->bitrix->arParams['IMG_CACHE']['CATEGORIES']
        );

        return $sections['SECTIONS'];
    }

    /**
     * Получить элементы категории
     * 
     * @return array
     */
    protected function getElements()
    {
        $filter_get = isset($this->bitrix->arParams['FILTER'])
                      ? $this->bitrix->arParams['FILTER']
                      : [];

        $filter_standart = [
            'IBLOCK_ID'     => $this->bitrix->arParams['IBLOCK_ID'],
            'ACTIVE'        => 'Y',
            'ACTIVE_DATE'   => $this->bitrix->arParams['ACTIVE_DATE'] ? : '',
            'SECTION_ID'    => $this->bitrix->arResult['SECTION']['ID']
        ];

        $filter = array_merge($filter_standart, $filter_get);

        $items = Elements::getElements(
            $filter, $this->bitrix->arParams['SORT']['ELEMENTS'],
            $this->bitrix->arParams['PAGINATION'],
            $this->bitrix->arParams['IMG_CACHE']['ELEMENTS']
        );

        $this->pagination = $items['PAGINATION'];

        return $items['ITEMS'];
    }

    /**
     * Устанавливает всю мета информацию включая хлебные крошки
     * 
     * @global CMain $APPLICATION
     */
    protected function setMetaInfo()
    {
        global $APPLICATION;

        $iprops = $this->bitrix->arResult['IPROPERTY_VALUES'];

        // Установим TITLE
        if (!empty($iprops['SECTION_PAGE_TITLE'])) {
            $APPLICATION->SetTitle($iprops['SECTION_PAGE_TITLE']);
        } else {
            $APPLICATION->SetTitle($this->bitrix->arResult['SECTION']['NAME']);
        }

        if (is_array($iprops['SECTION_META_TITLE'])) {
            $APPLICATION->SetPageProperty('title', implode(' ', $iprops['SECTION_META_TITLE']));
        } elseif (!empty($iprops['SECTION_META_TITLE'])) {
            $APPLICATION->SetPageProperty('title', $iprops['SECTION_META_TITLE']);
        }

        // Установим Keywords
        if (is_array($iprops['SECTION_META_KEYWORDS'])) {
            $APPLICATION->SetPageProperty('keywords', implode(' ', $iprops['SECTION_META_KEYWORDS']));
        } elseif (!empty($iprops['SECTION_META_KEYWORDS'])) {
            $APPLICATION->SetPageProperty('keywords', $iprops['SECTION_META_KEYWORDS']);
        }
                
        // Установим Description
        if (is_array($iprops['SECTION_META_DESCRIPTION'])) {
            $APPLICATION->SetPageProperty('description', implode(' ', $iprops['SECTION_META_DESCRIPTION']));
        } elseif (!empty($iprops['SECTION_META_DESCRIPTION'])) {
            $APPLICATION->SetPageProperty('description', $iprops['SECTION_META_DESCRIPTION']);
        }
        
        // Установим хлебные крошки
        $section_path = $this->bitrix->arResult['SECTION_PATH'];

        foreach ($section_path as $section) {
            $APPLICATION->AddChainItem(
                $section['NAME'], $section['SECTION_PAGE_URL']
            );
        }

        $APPLICATION->AddChainItem(
            $this->bitrix->arResult['SECTION']['NAME'],
            $this->bitrix->arResult['SECTION']['SECTION_PAGE_URL']
        );
    }
}