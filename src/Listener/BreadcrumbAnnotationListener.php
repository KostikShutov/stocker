<?php

declare(strict_types=1);

namespace App\Listener;

use ReflectionClass;
use ReflectionException;
use App\Annotation\Breadcrumb;
use App\Service\BreadcrumbBuilder;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

final class BreadcrumbAnnotationListener
{
    private Reader $reader;

    private RouteCollection $routeCollection;

    private BreadcrumbBuilder $breadcrumbBuilder;

    /**
     * @var string[]
     */
    private array $routes = [];

    /**
     * @var string[]
     */
    private array $names = [];

    public function __construct(Reader $reader, RouterInterface $router, BreadcrumbBuilder $breadcrumbBuilder)
    {
        $this->reader = $reader;
        $this->routeCollection = $router->getRouteCollection();
        $this->breadcrumbBuilder = $breadcrumbBuilder;
    }

    /**
     * @throws ReflectionException
     */
    public function onKernelController(ControllerEvent $event): void
    {
        if ($event->isMasterRequest() && is_array($controller = $event->getController())) {
            list($controller, $method) = $controller;

            $this->handleMethod(get_class($controller), $method);

            array_unshift($this->routes, null);

            $countRoutes = count($this->routes);
            $countNames = count($this->names);

            if ($countRoutes <= 0 || $countNames <= 0 || $countRoutes !== $countNames) {
                return;
            }

            for ($i = 0; $i < $countRoutes; $i++) {
                $this->breadcrumbBuilder->addToBuild($this->routes[$i], $this->names[$i]);
            }
        }
    }

    /**
     * @throws ReflectionException
     */
    private function handleMethod(string $controller, string $method): void
    {
        $controller = new ReflectionClass($controller);
        $method = $controller->getMethod($method);
        $annotation = $this->reader->getMethodAnnotation($method, Breadcrumb::class);

        if ($annotation instanceof Breadcrumb) {
            $this->handleRoute($annotation->route, $annotation->name);
        }
    }

    /**
     * @throws ReflectionException
     */
    private function handleRoute(string $route, string $name): void
    {
        $this->names[] = $name;

        if (!empty($route)) {
            $this->routes[] = $route;

            $defaults = $this->routeCollection->get($route)->getDefaults();

            list($controller, $method) = explode('::', $defaults['_controller']);

            $this->handleMethod($controller, $method);
        }
    }
}
