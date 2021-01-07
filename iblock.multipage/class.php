<?php

use Symfony\Component\ClassLoader\Psr4ClassLoader;

class IblockMultipageComponent extends CBitrixComponent
{
    public function registerAutoload()
    {
        $loader = new Psr4ClassLoader();

        $loader->addPrefix('IblockMultipageComponent', __DIR__);

        $loader->register();
    }

    public function executeAction($bitrix, $slim, $controller, $action)
    {
        $controller = "IblockMultipageComponent\\Controllers\\{$controller}Controller";
        $action     = "{$action}Action";

        $c = new $controller($bitrix, $slim);

        if ($c->beforeExecuteAction()) {
            $c->$action();
        }

        $c->afterExecuteAction();
    }

    public function getRoutes()
    {
        if (!empty($this->arParams['ROUTES'])) {
            return $this->arParams['ROUTES'];
        }

        if (isset($this->arParams['CATEGORIES_TO_ELEMENT']) &&
            $this->arParams['CATEGORIES_TO_ELEMENT'] == 'Y'
        ) {
            return [
                 [
                    'METHOD' => 'GET, POST',
                    'URL' => '/',
                    'NAME' => 'categories',
                    'CONTROLLER' => 'Categories',
                    'ACTION' => 'index'
                ],
                [
                    'METHOD' => 'GET, POST',
                    'URL' => '/:element',
                    'NAME' => 'element',
                    'CONTROLLER' => 'Element',
                    'ACTION' => 'index'
                ],
            ];
        }

        if (isset($this->arParams['CATEGORIES']) && $this->arParams['CATEGORIES'] == 'Y') {
            if (isset($this->arParams['FIRST_PAGE']) && $this->arParams['FIRST_PAGE'] == 'ELEMENTS') {
                return [
                    [
                        'METHOD' => 'GET, POST',
                        'URL' => '/(index.php)',
                        'NAME' => 'elements',
                        'CONTROLLER' => 'Elements',
                        'ACTION' => 'index'
                    ],
                    [
                        'METHOD' => 'GET, POST',
                        'URL' => '/:category',
                        'NAME' => 'category',
                        'CONTROLLER' => 'Category',
                        'ACTION' => 'index'
                    ],
                    [
                        'METHOD' => 'GET, POST',
                        'URL' => '/:category/:element',
                        'NAME' => 'element',
                        'CONTROLLER' => 'Element',
                        'ACTION' => 'index'
                    ],
                ];
            }

            return [
                [
                    'METHOD' => 'GET, POST',
                    'URL' => '/',
                    'NAME' => 'categories',
                    'CONTROLLER' => 'Categories',
                    'ACTION' => 'index'
                ],
                [
                    'METHOD' => 'GET, POST',
                    'URL' => '/:category',
                    'NAME' => 'category',
                    'CONTROLLER' => 'Category',
                    'ACTION' => 'index'
                ],
                [
                    'METHOD' => 'GET, POST',
                    'URL' => '/:category/:element',
                    'NAME' => 'element',
                    'CONTROLLER' => 'Element',
                    'ACTION' => 'index'
                ],
            ];
        }

        return [
            [
                'METHOD' => 'GET, POST',
                'URL' => '/',
                'NAME' => 'elements',
                'CONTROLLER' => 'Elements',
                'ACTION' => 'index'
            ],
            [
                'METHOD' => 'GET, POST',
                'URL' => '/:element',
                'NAME' => 'element',
                'CONTROLLER' => 'Element',
                'ACTION' => 'index'
            ],
        ];
    }

    public function executeComponent()
    {
        $this->registerAutoload();

        $slim   = new \Slim\Slim();
        $routes = $this->getRoutes();
        $bitrix = $this;
        $sef    = rtrim($this->arParams['SEF_URL'], '/');

        foreach ($routes as $route) {
            if (count(explode('.', $_SERVER['SERVER_NAME'])) > 2) {
                $url = $sef.$route['URL'];
            } else {
                $url = $route['URL'] != '/' ? $sef.$route['URL'] : $route['URL'];
            }

            $url .= '(/(index.php))';

            if ($route['METHOD'] == 'GET, POST') {
                $slim->map($url,
                    function() use($bitrix, $slim, $route) {

                    $bitrix->executeAction(
                        $bitrix,
                        $slim,
                        $route['CONTROLLER'],
                        $route['ACTION']
                    );
                })->via('GET', 'POST')->name($route['NAME']);
            } else {
                $method = strtolower($route['METHOD']);
                $slim->$method($url,
                    function() use($bitrix, $slim, $route) {

                    $bitrix->executeAction(
                        $bitrix,
                        $slim,
                        $route['CONTROLLER'],
                        $route['ACTION']
                    );
                })->name($route['NAME']);
            }
        }

        $slim->notFound(function() use($bitrix) {
            $bitrix->IncludeComponentTemplate('404');
        });

        $slim->run();
    }
}