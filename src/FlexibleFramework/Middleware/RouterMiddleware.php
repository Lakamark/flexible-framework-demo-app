<?php

namespace FlexibleFramework\Middleware;

use FlexibleFramework\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RouterMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly Router $router,
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $this->router->match($request);
        if (is_null($route)) {
            return $handler->handle($request);
        }

        $params = $route->getParams();
        $request = array_reduce(array_keys($params), function ($request, $key) use ($params) {
            return $request->withAttribute($key, $params[$key]);
        }, $request);
        $request = $request->withAttribute(get_class($route), $route);
        return $handler->handle($request);
    }
}
