<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arComponentDescription = array(
   "NAME" => "it-delta:multipage",
   "DESCRIPTION" => "Комплексный компонент инфоблоков",
   "ICON" => "/images/icon.gif",
   "PATH" => array(
      "ID" => "content",
      "CHILD" => array(
         "ID" => "it-delta",
      )
   ),
   // "AREA_BUTTONS" => array(
   //    array(
   //       'URL' => "javascript:alert('Это кнопка!!!');",
   //       'SRC' => '',
   //       'TITLE' => "Это кнопка!"
   //    ),
   // ),
   "CACHE_PATH" => "N",
   "COMPLEX" => "Y"
);
?>
