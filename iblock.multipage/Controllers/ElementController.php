<?php

namespace IblockMultipageComponent\Controllers;

use Bitrix\Iblock\InheritedProperty\ElementValues;
use Falur\Bitrix\Iblock\Elements;
use Falur\Bitrix\Iblock\Sections;

class ElementController extends BaseController
{
	public function indexAction()
	{
		global $APPLICATION;

        $curdir = $APPLICATION->GetCurDir();

		if ($this->bitrix->StartResultCache(false, $curdir)) {
			$this->bitrix->arResult = $this->getElement();

            if (empty($this->bitrix->arResult)) {
                return $this->error404();
            }	
			
			$this->bitrix->arResult['IPROPERTY_VALUES'] = 
				(new ElementValues(
                    $this->bitrix->arResult['IBLOCK_ID'],
                    $this->bitrix->arResult['ID']
				))->getValues();
			
			$this->bitrix->arResult['SECTION_PATH'] = 
				Sections::getPath(
					$this->bitrix->arParams['IBLOCK_ID'], 
					$this->bitrix->arResult['IBLOCK_SECTION_ID']
				);
			
			$this->bitrix->SetResultCacheKeys([
                'ID', 'NAME', 'DETAIL_PAGE_URL', 'IPROPERTY_VALUES', 'SECTION_PATH'
            ]);
            
			$this->bitrix->IncludeComponentTemplate('element');
		}
		
		$this->setMetaInfo();
	}
	
	/**
	 * Получить информацию по текущему элементу
	 * 
	 * @return array
	 */
	protected function getElement()
	{
		$element_code = $this->slim->router->getCurrentRoute()->getParam('element');
		
		return Elements::getElement(
			[
				'IBLOCK_ID' => $this->bitrix->arParams['IBLOCK_ID'],
				'ACTIVE' => 'Y',
				'CODE' => $element_code
			],
			$this->bitrix->arParams['IMG_CACHE']['ELEMENT']
		);
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
		if (!empty($iprops['ELEMENT_PAGE_TITLE']))
			$APPLICATION->SetTitle($iprops['ELEMENT_PAGE_TITLE']);
		else
			$APPLICATION->SetTitle($this->bitrix->arResult['NAME']);

		if (is_array($iprops['ELEMENT_META_TITLE']))
			$APPLICATION->SetPageProperty('title', implode(' ', $iprops['ELEMENT_META_TITLE']));
		elseif (!empty($iprops['ELEMENT_META_TITLE']))
			$APPLICATION->SetPageProperty('title', $iprops['ELEMENT_META_TITLE']);

		// Установим Keywords 
		if (is_array($iprops['ELEMENT_META_KEYWORDS']))
			$APPLICATION->SetPageProperty('keywords', implode(' ', $iprops['ELEMENT_META_KEYWORDS']));
		elseif (!empty($iprops['ELEMENT_META_KEYWORDS']))
			$APPLICATION->SetPageProperty('keywords', $iprops['ELEMENT_META_KEYWORDS']);

		// Установим Description
		if (is_array($iprops['ELEMENT_META_DESCRIPTION']))
			$APPLICATION->SetPageProperty('description', implode(' ', $iprops['ELEMENT_META_DESCRIPTION']));
		elseif (!empty($iprops['ELEMENT_META_DESCRIPTION']))
			$APPLICATION->SetPageProperty('description', $iprops['ELEMENT_META_DESCRIPTION']);


		// Установим хлебные крошки	
        $add_section = isset($this->bitrix->arParams['ADD_SECTION_IN_BREADCRUMBS']) ? $this->bitrix->arParams['ADD_SECTION_IN_BREADCRUMBS'] : 'Y';

        if ($add_section == 'Y') {
            $section_path = $this->bitrix->arResult['SECTION_PATH'];

            foreach ($section_path as $section) {
                $APPLICATION->AddChainItem(
                    $section['NAME'],
                    $section['SECTION_PAGE_URL']
                );
            }
        }

		// Элемент
		$APPLICATION->AddChainItem(
			$this->bitrix->arResult['NAME'], 
			$this->bitrix->arResult['DETAIL_PAGE_URL']
		);	
	}
}
