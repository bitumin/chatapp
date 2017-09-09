<?php

namespace ChatApp\Bootstrap;

use LogicException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Throwable;

class Core implements HttpKernelInterface
{
    /** @var RouteCollection */
    protected $routes;

    /**
     * App constructor.
     */
    public function __construct()
    {
        $this->routes = new RouteCollection();
    }

    /**
     * @param Request $request
     * @param int $type
     * @param bool $catch
     * @return Response
     */
    public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
    {
        $context = new RequestContext();
        $context->fromRequest($request);
        $matcher = new UrlMatcher($this->routes, $context);

        try {
            $attributes = $matcher->match($request->getPathInfo());
            $controller = $attributes['controller'];
            unset($attributes['controller']);

            if (is_callable($controller)) {
                $response = call_user_func_array($controller, $attributes);
            } else if (is_string($controller)) {
                list($controllerClass, $method) = explode('@', $controller);
                $response = call_user_func_array(['\\ChatApp\\Controller\\' . $controllerClass, $method], $attributes);
            } else {
                throw new LogicException('Invalid controller provided!');
            }

        } catch (Throwable $e) {
            $response = new Response('Not found!', Response::HTTP_NOT_FOUND);
        }

        return $response;
    }

    /**
     * @param $path
     * @param $controller
     */
    public function map($path, $controller)
    {
        $this->routes->add($path, new Route($path, array('controller' => $controller)));
    }
}
