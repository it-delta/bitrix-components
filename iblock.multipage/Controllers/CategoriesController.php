<?php

namespace IblockMultipageComponent\Controllers;

use Falur\Bitrix\Iblock\Sections;

class CategoriesController extends BaseController
{
    /**
     *
     * @global CMain $APPLICATION
     */
	public function indexAction()
	{
		global $APPLICATION;
		
		if ($this->bitrix->StartResultCache(false, $APPLICATION->GetCurDir())) {
			$this->bitrix->arResult = Sections::getSections(
				[
					'IBLOCK_ID'     => $this->bitrix->arParams['IBLOCK_ID'],
					'ACTIVE'        => 'Y',
					'GLOBAL_ACTIVE' => 'Y',
					'CNT_ACTIVE'    => 'Y',
					'DEPTH_LEVEL'   => 1
				],
				$this->bitrix->arParams['SORT']['CATEGORIES'],
				$this->bitrix->arParams['IMG_CACHE']['CATEGORIES']
			);

			$this->bitrix->IncludeComponentTemplate('categories');
		}
	}
}
