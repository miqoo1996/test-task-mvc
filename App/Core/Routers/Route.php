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
    protected static array $methods = [
        'get',
        'post',
        'put',
        'patch',
        'delete'
    ];

    /**
     * @var string
     */
    protected static string $controller;

    /**
     * @var  string
     */
    protected static string $action;

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
     * @throws \Exception
     */
    private static function init(string $path, array $arguments = []): bool
    {
        $uri = explode('?', $_SERVER['REQUEST_URI']);

        $route = $uri[0] ?? null;

        if ($route === $path) {
            list($controller, $action) = $arguments;

            self::$controller = $controller;
            self::$action = $action;

            if (self::$isApiMode === false) {
                self::checkCsrfToken();
            }

            /// Handle Constructor Arguments
            $constructorNewParams = [];
            array_map(function (ReflectionParameter $param) use (&$constructorNewParams) {
                $className = $param->getType()->getName();

                if (class_exists($className)) {
                    $constructorNewParams[] = new $className;
                }
            }, self::getConstructorParams());

            $controller = empty($constructorNewParams) ? new $controller : new $controller(...$constructorNewParams);

            $controller->$action(...self::getActionParams());
        }

        return true;
    }
    /**
     * @return void
     * @throws \Exception
     */
    private static function checkCsrfToken() : void
    {
        if (empty(Request::getCsrfToken())) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        if (
            Request::isRequestMethod('post') ||
            Request::isRequestMethod('put') ||
            Request::isRequestMethod('patch') ||
            Request::isRequestMethod('delete')
        ) {
            if (empty(Request::getCsrfToken()) || Request::post('csrf_token') !== Request::getCsrfToken()) {
                http_response_code(403);

                die('CSRF token mismatch.');
            } else {
                // update CSRF token for the next request
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }
        }
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    private static function getConstructorParams() : array
    {
        $class = new ReflectionClass(self::$controller);

        $constructorReflection = $class->getConstructor();

        $constructPrams = [];
        if ($constructorReflection) {
            $constructPrams = $constructorReflection->getParameters();
        }

        return $constructPrams;
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    private static function getActionParams(): array
    {
        $r = new ReflectionMethod(self::$controller, self::$action);

        $reflectionParams = [];
        foreach ($r->getParameters() as $param) {
            $className = $param->getType()->getName();

            if (class_exists($className)) {
                DI::make($className);

                $reflectionParams[] = DI::get($className);

                continue;
            }

            $reflectionParams[] = $className;
        }

        return $reflectionParams;
    }
}