<?php

namespace IblockMultipageComponent\Controllers;

use IblockMultipageComponent\lib\Sections;
use IblockMultipageComponent\lib\Elements;

use Slim\Exception\HttpNotFoundException;
use Psr\Http\Message\ResponseInterface;

use Bitrix\Iblock\InheritedProperty\SectionValues;
use CDBResult;

class IndexController extends BaseController
{
    protected $pagination;
    protected $args;

    public function index($request, $response, array $args, $component): ResponseInterface
    {
        global $APPLICATION;

        $this->args = $args;
        $this->component = $component;

        \CPageOption::SetOptionString('main', 'nav_page_in_session', 'N');

        $filter_get = [];
        if (isset($this->component->arParams['FILTER_NAME']) && preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $this->component->arParams["FILTER_NAME"])) {
            global ${$this->component->arParams["FILTER_NAME"]};
            $parsedFilter = ${$this->component->arParams["FILTER_NAME"]};
            if (is_array($parsedFilter)) {
                $filter_get = $parsedFilter;
            }
        }

        $pages_count = $this->component->arParams['PAGINATION_COUNT'] ? : 10;
        $nav         = CDBResult::NavStringForCache($pages_count);
        $section_code = $this->args['section'];
        $cache_id    = $APPLICATION->GetCurDir() . $nav.  implode('', $filter_get).$section_code;

        if ($this->component->StartResultCache(false, $cache_id)) {
            // если корневая страница, не добавляем в arResult массив SECTION
            // если не корневая, проверяем на 404 и загружаем
            if (isset($section_code)) {
                $this->component->arResult['SECTION'] = $this->getSection();

                if (empty($this->component->arResult['SECTION'])) {
                    throw new HttpNotFoundException($request);
                }
            }

            $this->component->arResult['ITEMS']      = $this->getElements();
            $this->component->arResult['SECTIONS']   = $this->getSections();
            $this->component->arResult['PAGINATION'] = $this->getPagination();

            //меняем урл
            $sef = rtrim($this->component->arParams['SEF_URL'], '/');
            foreach ($this->component->arResult['ITEMS'] as &$item) {
              $item['DETAIL_PAGE_URL'] = $sef.'/element/'.$item['CODE'];
            }
            foreach ($this->component->arResult['SECTIONS'] as &$item) {
              $item['DETAIL_PAGE_URL'] = $sef.'/'.$item['CODE'];
              $item['SECTION_PAGE_URL'] = $sef.'/'.$item['CODE'].'/detail';
            }

            $this->component->arResult['SECTION_PATH'] = Sections::getPath(
                $this->component->arParams['IBLOCK_ID'],
                $this->component->arResult['SECTION']['IBLOCK_SECTION_ID']
            );

            $this->component->arResult['IPROPERTY_VALUES'] = (new SectionValues(
                $this->component->arResult['SECTION']['IBLOCK_ID'],
                $this->component->arResult['SECTION']['ID']
            ))->getValues();

            $this->component->SetResultCacheKeys(['SECTION', 'IPROPERTY_VALUES', 'SECTION_PATH']);
            $this->component->IncludeComponentTemplate('index');
        }
        $this->setMetaInfo();
        return $response;
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
        $section_code = $this->args['section'];

        return Sections::getSection(
            [
                'IBLOCK_ID' => $this->component->arParams['IBLOCK_ID'],
                'ACTIVE'    => 'Y',
                'CODE'      => $section_code
            ],
            $this->component->arParams['IMG_CACHE']['CATEGORIES']
        );
    }

    /**
     * Получить дочерние категории
     *
     * @return array
     */
    protected function getSections()
    {
        $filter = [
            'IBLOCK_ID'     => $this->component->arParams['IBLOCK_ID'],
            'ACTIVE'        => 'Y',
            'GLOBAL_ACTIVE' => 'Y',
            'CNT_ACTIVE'    => 'Y',
        ];

        $section_code = $this->args['section'];
        if (isset($section_code)) {
          $section = Sections::getSection([
              'IBLOCK_ID' => $this->component->arParams['IBLOCK_ID'],
              'CODE'      => $section_code
          ]);
          $filter['SECTION_ID'] = $section['ID']; // по родительской категории
        } else {
          $filter['SECTION_ID'] = $section['ID']; // только категории из корня
        }

        $sections = Sections::getSections(
            $filter,
            array($this->component->arParams["SECTION_SORT_BY"] => $this->component->arParams["SECTION_SORT_ORDER"]),
            $this->component->arParams['IMG_CACHE']['CATEGORIES']
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
        $filter_get = (isset($this->component->arParams['FILTER_NAME']) && is_array($this->component->arParams['FILTER_NAME'])) ? $this->component->arParams['FILTER_NAME'] : [];

        $filter_get = [];
        if (isset($this->component->arParams['FILTER_NAME']) && preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $this->component->arParams["FILTER_NAME"])) {
            global ${$this->component->arParams["FILTER_NAME"]};
            $parsedFilter = ${$this->component->arParams["FILTER_NAME"]};
            if (is_array($parsedFilter)) {
                $filter_get = $parsedFilter;
            }
        }

llc($filter_get);
        $filter_standart = [
            'IBLOCK_ID'     => $this->component->arParams['IBLOCK_ID'],
            'ACTIVE'        => 'Y',
            'SECTION_GLOBAL_ACTIVE' => 'Y',
            'ACTIVE_DATE'   => $this->component->arParams['ACTIVE_DATE'] == 'Y' ? 'Y' : '',
        ];
        // корневая или нет? для корневой выводим все товары
        $section_code = $this->args['section'];
        if (isset($section_code)) {
            $filter_standart['SECTION_ID'] = $this->component->arResult['SECTION']['ID'];
        }
        $filter = array_merge($filter_standart, $filter_get);

        if ($this->component->arParams['RAND_ELEMENTS'] == 'Y') {
            $order = ['RAND' => 'ASC'];
        } else {
            $order = [
              $this->component->arParams["ELEMENTS_SORT_BY_1"] => $this->component->arParams["ELEMENTS_SORT_ORDER_1"],
              $this->component->arParams["ELEMENTS_SORT_BY_2"] => $this->component->arParams["ELEMENTS_SORT_ORDER_2"]
            ];
        }

        $items = Elements::getElements(
            $filter,
            $order,
            $this->component->arParams['PAGINATION_COUNT'],
            $this->component->arParams['IMG_CACHE']['ELEMENTS'],
            [
                "name" => $this->component->arParams['PAGINATION_TITLE'],
                "template" => $this->component->arParams['PAGINATION_TEMPLATE'],
            ]
        );

        $this->pagination = $items['PAGINATION'];

        return $items['ITEMS'];
    }

}
