<?php

namespace Mu\Infrastructure\Api\Custom\HttpKernel;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\Routing\Matcher\UrlMatcher;

class ArgumentResolver implements ArgumentResolverInterface
{
    private $urlMatcher;

    public function __construct(UrlMatcher $urlMatcher)
    {
        $this->urlMatcher = $urlMatcher;
    }

    public function getArguments(Request $request, $controller): array
    {
        $reflectionClass = new \ReflectionClass($controller);
        $reflectionInvoke = $reflectionClass->getMethod('__invoke');
        return $this->getValues($request, $reflectionInvoke);
    }

    private function getValues(
        Request $request,
        \ReflectionMethod $reflectionMethod
    ): array {
        $arguments = [];
        $params = $this->urlMatcher->matchRequest($request);
        $params['request'] = $request;
        foreach ($reflectionMethod->getParameters() as $reflectionParameter) {
            $arguments[] = $this->getValue($reflectionParameter, $params);
        }

        return$arguments;
    }

    private function getValue(\ReflectionParameter $parameter, $params)
    {
        if (!isset($params[$parameter->getName()])) {
            throw new \RuntimeException(
                sprintf(
                    'Not exists a valid parameter for %s.',
                    $parameter->getName()
                )
            );
        }

        return $params[$parameter->getName()];
    }
}
