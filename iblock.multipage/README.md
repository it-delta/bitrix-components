# iblock-multipage
Многостраничный компонент на инфоблоках

```php
$APPLICATION->IncludeComponent(
	'main:iblock.multypage',
	'',
	[
		'IBLOCK_ID'  => '1',
		'SEF_URL'    => '/sefurl/',
		// Ключ - имя сортируемого поля, значение - как сортировать (возр, убыв)
		'SORT' => [
			'ELEMENTS' => [
				'DATE_ACTIVE_FROM' => 'DESC',
				'DATE_ACTIVE_TO' => 'DESC'
			],
			'CATEGORIES' => [
				'NAME' => 'ASC',
				'SORT' => 'ASC'
			]
		],
		// Количество элементов на строанице
		'PAGINATION' => 9,
		// Размер кеша картиноу
		'IMG_CACHE' => [
			'CATEGORIES' => ['width' => 200, 'height' => 200],
			'ELEMENTS'   => ['width' => 800, 'height' => 400],
			'ELEMENT'    => ['width' => 800, 'height' => 400],
		],
		// Показывать только активные элементы
		//'ACTIVE_DATE' => 'Y',
		// Добавлять секцию в хлебные крошки
		'ADD_SECTION_IN_BREADCRUMBS' => 'Y',
		// Массив с дополнительными параметрами фильтра
		'FILTER' => [],
		'CACHE_TYPE' => 'A',
		'CACHE_TIME' => 3600
	]
);
```
