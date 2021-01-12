# iblock-multipage

Многостраничный компонент для вывода содержимого инфоблока.
Содержит 3 шаблона:
1. index
	- Выводит список секций в массиве SECTIONS
	- Элементы текущей секции в массиве ITEMS
	- Текущую секцию в SECTION
Также другую информацию
2. section
	Детальная страница секции
3. element
	Детальная страница элемента

```php
$APPLICATION->IncludeComponent(
	'it-delta:iblock.multipage',
	'',
	[
		'IBLOCK_ID'  => '1',
		'SEF_URL'    => '/sefurl/',
		// Имя переменной с дополнительными параметрами фильтра
		'FILTER_NAME' => 'arrFilter1',
		// Дополнительная строка для идентификатора кеша
		"ADD_CACHE_STRING" => "",
		// Выводить элементы в случайном порядке
		"RAND_ELEMENTS" => "N",
		// Количество элементов на странице
		'PAGE_ELEMENT_COUNT' => 9,
		// Пагинация
		"PAGINATION_COUNT" => "20",
		"PAGINATION_TEMPLATE" => ".default",
		"PAGINATION_TITLE" => "Страница:",
		// Показывать только активные элементы
		'ACTIVE_DATE' => 'Y',
		// Добавлять секцию в хлебные крошки
		'ADD_SECTION_IN_BREADCRUMBS' => 'Y',
		// Сортировка для элементов и секций
		"ELEMENTS_SORT_BY_1" => "NAME",
		"ELEMENTS_SORT_ORDER_1" => "ASC",
		"ELEMENTS_SORT_BY_2" => "ID",
		"ELEMENTS_SORT_ORDER_2" => "ASC",
		"SECTION_SORT_BY" => "NAME",
		"SECTION_SORT_ORDER" => "DESC",
		// Размер кеша детальной картинки для элемента и секции
		// Необязательный параметр. Если не установить, то ресайз не будет генерироваться
		"IMG_CACHE" => [
			"SECTION" => [
			    "TYPE" => BX_RESIZE_IMAGE_EXACT,// BX_RESIZE_IMAGE_PROPORTIONAL, BX_RESIZE_IMAGE_PROPORTIONAL_ALT
			    "SIZE" => [
				"width" => "200",
				"height" => "200",
		    	    ]
			],
			"ELEMENT" => [
			    "TYPE" => BX_RESIZE_IMAGE_EXACT,// BX_RESIZE_IMAGE_PROPORTIONAL, BX_RESIZE_IMAGE_PROPORTIONAL_ALT
			    "SIZE" => [
    				"width" => "200",
    				"height" => "200",
			    ]
  			],
		],
	]
);
```
