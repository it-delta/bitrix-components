<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>

<h1><?= $arResult['NAME'] ?></h1>

<p class="uk-article-meta"><?= $arResult['DATE_ACTIVE_FROM'] ?></p>

<div class="uk-clearfix">
	<? if ($arResult['DETAIL_PICTURE_CACHE']['src'] || $arResult['DETAIL_PICTURE']['SRC']): ?>
	<img class="uk-align-medium-left"
		 src="<?= $arResult['DETAIL_PICTURE_CACHE']['src'] ?: $arResult['DETAIL_PICTURE']['SRC'] ?>"
		 alt=""
	>
	<? endif; ?>
	<?= $arResult['DETAIL_TEXT'] ?>
</div>
