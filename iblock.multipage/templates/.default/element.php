<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>

<div id="<?=$this->GetEditAreaId($arResult['ID']);?>">
	<h1><?= $arResult['NAME'] ?></h1>

	<p><?= $arResult['DATE_ACTIVE_FROM'] ?></p>
	<?
	$this->AddEditAction($arResult['ID'], $arResult['EDIT_LINK'], CIBlock::GetArrayByID($arResult["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arResult['ID'], $arResult['DELETE_LINK'], CIBlock::GetArrayByID($arResult["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => "Удалить?"));
	?>

	<? if ($arResult['DETAIL_PICTURE_CACHE']['src'] || $arResult['DETAIL_PICTURE']['SRC']): ?>
		<img src="<?= $arResult['DETAIL_PICTURE_CACHE']['src'] ?? $arResult['DETAIL_PICTURE']['SRC1'] ?>">
	<? endif; ?>

	<p>
		<?= $arResult['DETAIL_TEXT'] ?>
	</p>
</div>

<pre>
	<?=print_r($arResult);?>
</pre>
