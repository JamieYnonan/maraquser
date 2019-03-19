<?php

namespace Mu\Infrastructure\Api\Custom;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;

final class Application implements HttpKernelInterface
{
    private $controllerResolver;
    private $argumentResolver;

    public function __construct(
        ControllerResolverInterface $controllerResolver,
        ArgumentResolverInterface $argumentResolver
    ) {
        $this->controllerResolver = $controllerResolver;
        $this->argumentResolver = $argumentResolver;
    }

    public function handle(
        Request $request,
        $type = self::MASTER_REQUEST,
        $catch = true
    ): Response {
        $request->headers->set('X-Php-Ob-Level', ob_get_level());

        $controller = $this->controllerResolver->getController($request);

        if ($controller === false) {
            throw new NotFoundHttpException(
                sprintf(
                    'Unable to find the controller for path "%s". '.
                    'The route is wrongly configured.',
                    $request->getPathInfo()
                )
            );
        }

        $response = call_user_func_array(
            $controller,
            $this->argumentResolver->getArguments(
                $request,
                $controller
            )
        );

        if (!$response instanceof Response) {
            $msg = 'The controller must return a response.';

            if (null === $response) {
                $msg .= ' Did you forget to add a return statement somewhere '.
                    'in your controller?';
            }

            throw new \LogicException($msg);
        }

        return $response;
    }
}
