<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
$isPic = $arResult['PREVIEW_PICTURE_CACHE']['src'] || $arResult['PREVIEW_PICTURE']['SRC'];
?>
<div class="row">
	<? if ($isPic): ?>
	<div class="col-sm-4">
		<img src="<?= $arResult['DETAIL_PICTURE_CACHE']['src'] ?: $arResult['DETAIL_PICTURE']['SRC'] ?>"
			 class="img-responsive">
	</div>
	<? endif; ?>

	<div class="<?= $isPic ? 'col-sm-8' : 'col-sm-12' ?>">
		<div class="h3 title" style="margin-top: 0"><?= $arResult['NAME'] ?></div>

		<? if ($arResult['DATE_ACTIVE_FROM']): ?>
		<p class="text-muted">
			<span class="glyphicon glyphicon-time"></span> <?= $arResult['DATE_ACTIVE_FROM'] ?>
		</p>
		<? endif; ?>

		<?= $arResult['DETAIL_TEXT'] ?>
	</div>
</div>