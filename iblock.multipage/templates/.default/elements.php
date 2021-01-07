<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>

<? foreach ($arResult['ITEMS'] as $arItem): ?>
<div class="uk-article">
	<div class="uk-article-title uk-h2"><?= $arItem['NAME'] ?></div>

	<p class="uk-article-meta"><?= $arItem['DATE_ACTIVE_FROM'] ?></p>

	<p class="uk-clearfix">
		<? if ($arItem['PREVIEW_PICTURE_CACHE']['src'] || $arItem['PREVIEW_PICTURE']['SRC']): ?>
		<a href="<?= $arItem['DETAIL_PAGE_URL'] ?>">
			<img class="uk-align-medium-left"
				 src="<?= $arItem['PREVIEW_PICTURE_CACHE']['src'] ?: $arItem['PREVIEW_PICTURE']['SRC'] ?>"
				 alt=""
			>
		</a>
		<? endif; ?>
		<?= strip_tags($arItem['PREVIEW_TEXT']) ?>
	</p>

	<a href="<?= $arItem['DETAIL_PAGE_URL'] ?>">Читать далее..</a>
</div>
<? endforeach; ?>

<?= $arResult['PAGINATION'] ?>
