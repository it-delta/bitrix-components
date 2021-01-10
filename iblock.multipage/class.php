<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use IblockMultipageComponent\lib\RequestResponseBitrix;

require __DIR__ . '/../vendor/autoload.php';

class IblockMultipageComponent extends CBitrixComponent
{

    public function executeComponent()
    {
        $slim = AppFactory::create();

        $component = $this;

        $routeCollector = $slim->getRouteCollector();
        $routeCollector->setDefaultInvocationStrategy(new RequestResponseBitrix($component));

        $errorMiddleware = $slim->addErrorMiddleware(true, true, true);
        $errorMiddleware->setErrorHandler(
            Slim\Exception\HttpNotFoundException::class,
            function (Request $request, Throwable $exception, bool $displayErrorDetails) use($component) {
                $component->IncludeComponentTemplate('404');
            });

        $sef = rtrim($this->arParams['SEF_URL'], '/');
        $namespace = '\\IblockMultipageComponent\\Controllers\\';

        $slim->any($sef.'/[{section}]', $namespace.'IndexController:index');
        $slim->any($sef.'/{section}/detail', $namespace.'SectionController:index');
        $slim->any($sef.'/element/{element}', $namespace.'ElementController:index');
        // $slim->any($sef.'/{section}/{element}', $namespace.'ElementController:index');

        $slim->run();
    }
}
