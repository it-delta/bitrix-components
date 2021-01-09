<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Slim\Factory\AppFactory;

use IblockMultipageComponent\lib\RequestResponseBitrix;

require __DIR__ . '/../vendor/autoload.php';

class IblockMultipageComponent extends CBitrixComponent
{

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
                        'URL' => '/',
                        'NAME' => 'elements',
                        'CONTROLLER' => 'Elements',
                        'ACTION' => 'index'
                    ],
                    [
                        'METHOD' => 'GET, POST',
                        'URL' => '/{category}',
                        'NAME' => 'category',
                        'CONTROLLER' => 'Category',
                        'ACTION' => 'index'
                    ],
                    [
                        'METHOD' => 'GET, POST',
                        'URL' => '/{category}/{element}',
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
        $namespace = '\\IblockMultipageComponent\\Controllers\\';
        $slim = AppFactory::create();

        $routes = $this->getRoutes();
        $bitrix = $this;

        $routeCollector = $slim->getRouteCollector();
        $routeCollector->setDefaultInvocationStrategy(new RequestResponseBitrix($bitrix));

        $errorMiddleware = $slim->addErrorMiddleware(true, true, true);

        $errorMiddleware->setErrorHandler(
            Slim\Exception\HttpNotFoundException::class,
            function (Request $request, Throwable $exception, bool $displayErrorDetails) use($bitrix) {
                $bitrix->IncludeComponentTemplate('404');
            });

        $sef    = rtrim($this->arParams['SEF_URL'], '/');

        $slim->any($sef.'/elements[/{category}]', $namespace.'ElementsController:index');

        $slim->any($sef.'/[{section}]', $namespace.'IndexController:index');
        $slim->any($sef.'/{section}/detail', $namespace.'SectionController:index');
        $slim->any($sef.'/element/{element}', $namespace.'ElementController:index');

        // $slim->any($sef.'/', $namespace.'ElementsController:index');

        $slim->run();
    }
}
