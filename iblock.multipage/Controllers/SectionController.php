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
					$imgCache = (isset($this->component->arParams['IMG_CACHE']) && is_array($this->component>arParams['IMG_CACHE'])) ? $this->component->arParams['IMG_CACHE']['ELEMENT'] : false;
					$this->component->arResult = Sections::getSection(
	            [
	                'IBLOCK_ID' => $this->component->arParams['IBLOCK_ID'],
	                'ACTIVE'    => 'Y',
	                'CODE'      => $section_code
	            ],
	            $imgCache
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

}
