<?php

declare(strict_types=1);

namespace IblockMultipageComponent\lib;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\InvocationStrategyInterface;

/**
 * Default route callback strategy with route parameters as an array of arguments.
 */
class RequestResponseBitrix implements InvocationStrategyInterface
{
    protected $component;

    public function __construct($component)
    {
        $this->component = $component;
    }

    /**
     * Invoke a route callable with request, response, and all route parameters
     * as an array of arguments.
     *
     * @param callable               $callable
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array<mixed>           $routeArguments
     *
     * @return ResponseInterface
     */
    public function __invoke(
        callable $callable,
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $routeArguments
    ): ResponseInterface {
        foreach ($routeArguments as $k => $v) {
            $request = $request->withAttribute($k, $v);
        }

        return $callable($request, $response, $routeArguments, $this->component);
    }
}
