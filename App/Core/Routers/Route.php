<?php

namespace App\Core\Routers;

use App\Core\DI\DI;
use App\Core\Request\Request;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;

/**
 * Class Route
 *
 * @method static mixed get(string $name, array $arguments)
 * @method static mixed post(string $name, array $arguments)
 * @method static mixed put(string $name, array $arguments)
 * @method static mixed delete(string $name, array $arguments)
 * @method static mixed patch(string $name, array $arguments)
 *
 * @package Core
 */
class Route implements RouteInterface
{
    /**
     * @var array
     */
    protected static array $methods = ['get', 'post', 'put', 'patch', 'delete'];

    /**
     * @var string
     */
    public static string $controller;

    /**
     * @var  string
     */
    public static string $action;

    /**
     * @var bool
     */
    public static bool $isApiMode = false;

    /**
     * Set headers
     */
    public static function initializeRESTApi() : void
    {
        self::$isApiMode = true;

        header('Content-Type: application/json');
    }

    /**
     * @param $name
     * @param array $arguments
     * @return bool
     * @throws \ReflectionException
     */
    public static function __callStatic($name, array $arguments): bool
    {
        if (empty($_SERVER['REQUEST_METHOD']) || !in_array($name, self::$methods) || $_SERVER['REQUEST_METHOD'] != strtoupper($name)) {
            return false;
        }

        $path = $arguments[0];

        array_shift($arguments);

        return self::init($path, ...$arguments);
    }


    /**
     * @param string $path
     * @param array $arguments
     * @return bool
     * @throws \ReflectionException
     */
    private static function init(string $path, array $arguments = []): bool
    {
        if (self::$isApiMode === false) {
            if (
                Request::isRequestMethod('post') ||
                Request::isRequestMethod('put') ||
                Request::isRequestMethod('patch') ||
                Request::isRequestMethod('delete')
            ) {
                if (empty(Request::getCsrfToken()) || Request::post('csrf_token') !== Request::getCsrfToken()) {
                    http_response_code(403);

                    die('CSRF token mismatch.');
                }
            } else {
                // update CSRF token for the next request
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }
        }

        list($controller, $action) = $arguments;

        self::$controller = $controller;
        self::$action = $action;

        $uri = explode('?', $_SERVER['REQUEST_URI']);
        $route = $uri[0];

        if ($route == $path) {
            $controller = self::$controller;
            $action = self::$action;

            /// Handle Constructor Arguments
            $class = new ReflectionClass(self::$controller);

            $constructorReflection = $class->getConstructor();

            $constructPrams = [];
            if ($constructorReflection) {
                $constructPrams = $constructorReflection->getParameters();
            }
            ///---------------------

            $constructorNewParams = [];
            array_map(function (ReflectionParameter $param) use (&$constructorNewParams) {
                $className = $param->getType()->getName();

                if (class_exists($className)) {
                    $constructorNewParams[] = new $className;
                }
            }, $constructPrams);

            $controller = empty($constructorNewParams) ? new $controller : new $controller(...$constructorNewParams);

            $r = new ReflectionMethod($controller, $action);

            $params = $r->getParameters();

            $reflectionParams = [];
            foreach ($params as $param) {
                $className = $param->getType()->getName();

                if (class_exists($className)) {
                    DI::make($className);
                    $reflectionParams[] = DI::get($className);
                    continue;
                }

                $reflectionParams[] = $className;
            }

            $controller->$action(...$reflectionParams);
        }

        return true;
    }
}