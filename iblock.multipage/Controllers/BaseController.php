<?php

namespace IblockMultipageComponent\Controllers;

class BaseController
{
	/**
	 * @var \CBitrixComponent  
	 */
	protected $bitrix;
	
	/**
	 * @var \Slim\Slim 
	 */
	protected $slim;
	
	/**
	 * @param CBitrixComponent $bitrix
	 * @param Slim\Slim $slim
	 */
	public function __construct($bitrix, $slim)
	{
		$this->bitrix = $bitrix;
		$this->slim = $slim;
	}
	
	public function beforeExecuteAction()
	{		
		return true;
	}
	
	public function afterExecuteAction()
	{
		
	}
	
	public function error404()
	{
		$this->bitrix->AbortResultCache();
		
		global $APPLICATION;		
		$APPLICATION->SetTitle('Страница не найдена');
		$APPLICATION->AddChainItem('Страница не найдена', '');
		$this->slim->response->setStatus(404);
		$this->bitrix->IncludeComponentTemplate('404');
		return true;
	}
	
	protected function getModel($modelName)
	{
		$modelName = ucfirst($modelName);
		$class = "\\IblockMultipageComponent\\Models\\{$modelName}Model";
		return new $class($this->bitrix, $this->slim);
	}
}
