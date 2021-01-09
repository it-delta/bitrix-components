<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>

<h1>Категория</h1>

<? foreach ($arResult['SECTIONS'] as $arItem): ?>
<div class="uk-article">
  <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>"><?= $arItem['NAME'] ?></a>
  <a href="<?= $arItem['SECTION_PAGE_URL'] ?>"> -> Детальная</a><br>
  <?= strip_tags($arItem['PREVIEW_TEXT']) ?>
</div>
<? endforeach; ?>
<br><br>

<? foreach ($arResult['ITEMS'] as $arItem): ?>
    <div <div id="<?=$this->GetEditAreaId($arItem['ID']);?>">>
        <?
          $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
          $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => "Удалить?"));
        ?>
        <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>"><?= $arItem['NAME'] ?></a><br>
        <?= strip_tags($arItem['PREVIEW_TEXT']) ?>
    </div>
<? endforeach; ?>

<?= $arResult['PAGINATION'] ?>
<br>
<pre>
<?
print_r($arResult);
?>
</pre>
