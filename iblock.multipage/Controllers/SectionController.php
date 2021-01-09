<?php

namespace IblockMultipageComponent\Controllers;

use Bitrix\Iblock\InheritedProperty\ElementValues;
use IblockMultipageComponent\lib\Sections;
use IblockMultipageComponent\lib\Elements;
use Slim\Exception\HttpNotFoundException;
use Psr\Http\Message\ResponseInterface;

class SectionController extends BaseController
{
	public function index($request, $response, array $args, $component): ResponseInterface
	{
			global $APPLICATION;

			$this->args = $args;
			$this->component = $component;

      $curdir = $APPLICATION->GetCurDir();

			if ($this->component->StartResultCache(false, $curdir)) {

					$section_code = $this->args['section'];
					$this->component->arResult = Sections::getSection(
	            [
	                'IBLOCK_ID' => $this->component->arParams['IBLOCK_ID'],
	                'ACTIVE'    => 'Y',
	                'CODE'      => $section_code
	            ],
	            $this->component->arParams['IMG_CACHE']['CATEGORIES']
	        );

          if (empty($this->component->arResult)) {
              throw new HttpNotFoundException($request);
          }

					$this->component->arResult['IPROPERTY_VALUES'] =
							(new ElementValues(
			                    $this->component->arResult['IBLOCK_ID'],
			                    $this->component->arResult['ID']
							))->getValues();

					$this->component->arResult['SECTION_PATH'] =
							Sections::getPath(
								$this->component->arParams['IBLOCK_ID'],
								$this->component->arResult['IBLOCK_SECTION_ID']
							);

					$this->component->SetResultCacheKeys([
              'ID', 'NAME', 'DETAIL_PAGE_URL', 'IPROPERTY_VALUES', 'SECTION_PATH'
          ]);

					$this->component->IncludeComponentTemplate('section');
			}

			$this->setMetaInfo();
			return $response;
	}

	/**
	 * Устанавливает всю мета информацию включая хлебные крошки
	 *
	 * @global CMain $APPLICATION
	 */
	// protected function setMetaInfo()
	// {
	// 	global $APPLICATION;
	//
	// 	$iprops = $this->component->arResult['IPROPERTY_VALUES'];
	//
	// 	// Установим TITLE
	// 	if (!empty($iprops['ELEMENT_PAGE_TITLE']))
	// 		$APPLICATION->SetTitle($iprops['ELEMENT_PAGE_TITLE']);
	// 	else
	// 		$APPLICATION->SetTitle($this->component->arResult['NAME']);
	//
	// 	if (is_array($iprops['ELEMENT_META_TITLE']))
	// 		$APPLICATION->SetPageProperty('title', implode(' ', $iprops['ELEMENT_META_TITLE']));
	// 	elseif (!empty($iprops['ELEMENT_META_TITLE']))
	// 		$APPLICATION->SetPageProperty('title', $iprops['ELEMENT_META_TITLE']);
	//
	// 	// Установим Keywords
	// 	if (is_array($iprops['ELEMENT_META_KEYWORDS']))
	// 		$APPLICATION->SetPageProperty('keywords', implode(' ', $iprops['ELEMENT_META_KEYWORDS']));
	// 	elseif (!empty($iprops['ELEMENT_META_KEYWORDS']))
	// 		$APPLICATION->SetPageProperty('keywords', $iprops['ELEMENT_META_KEYWORDS']);
	//
	// 	// Установим Description
	// 	if (is_array($iprops['ELEMENT_META_DESCRIPTION']))
	// 		$APPLICATION->SetPageProperty('description', implode(' ', $iprops['ELEMENT_META_DESCRIPTION']));
	// 	elseif (!empty($iprops['ELEMENT_META_DESCRIPTION']))
	// 		$APPLICATION->SetPageProperty('description', $iprops['ELEMENT_META_DESCRIPTION']);
	//
	//
	// 	// Установим хлебные крошки
  //   $add_section = isset($this->component->arParams['ADD_SECTION_IN_BREADCRUMBS']) ? $this->component->arParams['ADD_SECTION_IN_BREADCRUMBS'] : 'Y';
	//
  //   if ($add_section == 'Y') {
  //       $section_path = $this->component->arResult['SECTION_PATH'];
	//
  //       foreach ($section_path as $section) {
  //           $APPLICATION->AddChainItem(
  //               $section['NAME'],
  //               $section['SECTION_PAGE_URL']
  //           );
  //       }
  //   }
	//
	// 	// Элемент
	// 	$APPLICATION->AddChainItem(
	// 		$this->component->arResult['NAME'],
	// 		$this->component->arResult['DETAIL_PAGE_URL']
	// 	);
	// }
}
