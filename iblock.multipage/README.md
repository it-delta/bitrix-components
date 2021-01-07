# iblock-multipage
Многостраничный компонент на инфоблоках

```php
$APPLICATION->IncludeComponent(
	'main:iblock.multypage',
	'',
	[
		'IBLOCK_ID'  => '1',
		'SEF_URL'    => '/sefurl/',
		// Если Y то будет сначала выведен список категорий, если N будет выведен список элементов
		'CATEGORIES' => 'Y',
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
		// Какую первую страницу показать: ELEMENTS показать список всех элементов
		// работает только для включенных категорий 'CATEGORIES' => 'Y'
		'FIRST_PAGE' => 'ELEMENTS',
		// Массив с дополнительными параметрами фильтра
		'FILTER' => [],
		'CACHE_TYPE' => 'A',
		'CACHE_TIME' => 3600
	]
);
```