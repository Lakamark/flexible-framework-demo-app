<?php

namespace Tests\FlexibleFramework\Middleware;

use FlexibleFramework\Middleware\MethodMiddleware;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;

class MethodMiddlewareTest extends TestCase
{
    private MethodMiddleware $middleware;

    public function setUp(): void
    {
        $this->middleware = new MethodMiddleware();
    }

    public function testAddMethod()
    {
        $delegate = $this->getMockBuilder(RequestHandlerInterface::class)
            ->onlyMethods(['handle'])
            ->getMock();

        $delegate->expects($this->once())
            ->method('handle')
            ->with($this->callback(function ($request) {
                return $request->getMethod() === 'DELETE';
            }));

        $request = (new ServerRequest('POST', '/demo'))
            ->withParsedBody(['_method' => 'DELETE']);
        $this->middleware->process($request, $delegate);
    }
}
