<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>

<h1>Категории</h1>

<ul class="uk-list uk-list-striped uk-list-space">
<? foreach ($arResult['SECTIONS'] as $aritem): ?>
	<li>
		<a href="<?= $aritem['SECTION_PAGE_URL'] ?>">
			<?= $aritem['NAME'] ?>
		</a>
	</li>
<? endforeach ?>
</ul>
