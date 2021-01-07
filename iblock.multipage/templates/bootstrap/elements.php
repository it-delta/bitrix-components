<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>

<? foreach ($arResult['ITEMS'] as $arItem): ?>
<?php $isPic = $arItem['PREVIEW_PICTURE_CACHE']['src'] || $arItem['PREVIEW_PICTURE']['SRC']; ?>
<div class="row">
	<? if ($isPic): ?>
	<div class="col-sm-4">
		<a href="<?= $arItem['DETAIL_PAGE_URL'] ?>" class="">
			<img src="<?= $arItem['PREVIEW_PICTURE_CACHE']['src'] ?: $arItem['PREVIEW_PICTURE']['SRC'] ?>"
				 class="img-responsive">
		</a>
	</div>
	<? endif; ?>

	<div class="<?= $isPic ? 'col-sm-8' : 'col-sm-12' ?>">
		<div class="h3 title" style="margin-top: 0"><?= $arItem['NAME'] ?></div>

		<? if ($arItem['DATE_ACTIVE_FROM']): ?>
		<p class="text-muted">
			<span class="glyphicon glyphicon-time"></span> <?= $arItem['DATE_ACTIVE_FROM'] ?>
		</p>
		<? endif; ?>

		<p><?= strip_tags($arItem['PREVIEW_TEXT']) ?></p>

		<p class="text-muted">
			<a href="<?= $arItem['DETAIL_PAGE_URL'] ?>">Подробнее</a>
		</p>
	</div>
</div>

<hr>
<? endforeach; ?>

<?= $arResult['PAGINATION'] ?>