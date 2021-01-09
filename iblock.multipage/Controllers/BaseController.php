<?php

namespace IblockMultipageComponent\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class BaseController
{

		protected $component;

  	public function __construct()
	 	{

		}

		/**
     * Устанавливает всю мета информацию включая хлебные крошки
     *
     * @global CMain $APPLICATION
     */
    protected function setMetaInfo()
    {
        global $APPLICATION;

        $iprops = $this->component->arResult['IPROPERTY_VALUES'];
// llc($this->component->arResult);
        // Установим TITLE
				if (isset($this->component->arResult['SECTION'])) {
					// секция
					$title = $this->component->arResult['SECTION']['NAME'];
				} else {
					// карточка товара, секции
					$title = $this->component->arResult['NAME'];
				}
        if (!empty($iprops['SECTION_PAGE_TITLE'])) {
            $APPLICATION->SetTitle($iprops['SECTION_PAGE_TITLE']);
        } else {
            $APPLICATION->SetTitle($title);
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
				$add_section = isset($this->component->arParams['ADD_SECTION_IN_BREADCRUMBS']) ? $this->component->arParams['ADD_SECTION_IN_BREADCRUMBS'] : 'Y';

		    if ($add_section == 'Y') {
		        $section_path = $this->component->arResult['SECTION_PATH'];

		        foreach ($section_path as $section) {
		            $APPLICATION->AddChainItem(
		                $section['NAME'],
		                $section['SECTION_PAGE_URL']
		            );
		        }
		    }

        $APPLICATION->AddChainItem(
            $this->component->arResult['SECTION']['NAME'],
            $this->component->arResult['SECTION']['SECTION_PAGE_URL']
        );
    }


		// public function error404()
		// {
		// 	$this->bitrix->AbortResultCache();
		//
		// 	global $APPLICATION;
		// 	$APPLICATION->SetTitle('Страница не найдена');
		// 	$APPLICATION->AddChainItem('Страница не найдена', '');
		// 	$this->slim->response->setStatus(404);
		// 	$this->bitrix->IncludeComponentTemplate('404');
		// 	return true;
		// }
		//
		// protected function getModel($modelName)
		// {
		// 	$modelName = ucfirst($modelName);
		// 	$class = "\\IblockMultipageComponent\\Models\\{$modelName}Model";
		// 	return new $class($this->bitrix, $this->slim);
		// }
}
