<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>

<h1>Категории</h1>

<ul class="list-group">
<? foreach ($arResult['SECTIONS'] as $aritem): ?>
	<li class="list-group-item">
		<a href="<?= $aritem['SECTION_PAGE_URL'] ?>">
			<?= $aritem['NAME'] ?>
		</a>
	</li>
<? endforeach ?>
</ul>
