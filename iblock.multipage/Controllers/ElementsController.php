<?php

namespace IblockMultipageComponent\Controllers;

use Falur\Bitrix\Iblock\Elements;
use CDBResult;

class ElementsController extends BaseController
{
	public function indexAction()
	{
		global $APPLICATION;

        \CPageOption::SetOptionString('main', 'nav_page_in_session', 'N');

        $filter_get = isset($this->bitrix->arParams['FILTER']) ? $this->bitrix->arParams['FILTER'] : [];
		
		$pages_count = $this->bitrix->arParams['PAGINATION']['COUNT'] ?: 10;
		$nav = CDBResult::NavStringForCache($pages_count);
		$cache_id = $APPLICATION->GetCurDir() . $nav . implode('', $filter_get);
		
		if ( $this->bitrix->StartResultCache(false, $cache_id) )
		{
			$filter_standart = [
				'IBLOCK_ID' => $this->bitrix->arParams['IBLOCK_ID'],
				'ACTIVE' => 'Y',
				'ACTIVE_DATE' => $this->bitrix->arParams['ACTIVE_DATE'] ?: ''
			];
			
			$filter = array_merge($filter_standart, $filter_get);
			
			$this->bitrix->arResult = Elements::getElements(
				$filter, 
				$this->bitrix->arParams['SORT']['ELEMENTS'], 
				$this->bitrix->arParams['PAGINATION'],
				$this->bitrix->arParams['IMG_CACHE']['ELEMENTS']
			);

			$this->bitrix->IncludeComponentTemplate('elements');
		}
	}
}
