<?php

namespace Mu\Infrastructure\Api\Custom\HttpKernel;

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing\Matcher\UrlMatcher;

class ControllerResolver implements ControllerResolverInterface
{
    private $urlMatcher;
    private $container;

    public function __construct(
        UrlMatcher $urlMatcher,
        ContainerInterface $container
    ) {
        $this->urlMatcher = $urlMatcher;
        $this->container = $container;
    }

    public function getController(Request $request)
    {
        $match = $this->urlMatcher->matchRequest($request);
        if ($this->container->has($match['_controller'])) {
            return $this->container->get($match['_controller']);
        }

        return false;
    }
}
