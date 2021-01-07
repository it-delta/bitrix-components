<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

$srv = Bitrix\Main\Application::getInstance()->getContext()->getServer();
require $srv->getDocumentRoot() . '/includes/404.php';
