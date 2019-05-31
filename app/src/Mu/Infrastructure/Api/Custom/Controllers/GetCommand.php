<?php

namespace Mu\Infrastructure\Api\Custom\Controllers;

use Symfony\Component\HttpFoundation\Request;

trait GetCommand
{
    protected $baseNameSpace;

    protected function getCommand(Request $request, array $extraData = [])
    {
        $dataRequest = json_decode($request->getContent(), true);
        $dataRequest = array_merge($dataRequest, $extraData);

        $action = $request->headers->get('Action');
        $commandName = $this->baseNameSpace.'\\'.$action.'Command';

        return $this->getInstanceCommandByReflection(
            $commandName,
            $dataRequest
        );
    }

    protected function getInstanceCommandByReflection(
        string $commandName,
        array $dataRequest
    ) {
        $arguments = [];
        try {
            $reflectionCommand = new \ReflectionClass($commandName);
        } catch (\ReflectionException $e) {
            throw new \InvalidArgumentException(
                sprintf('Invalid Action %s', $commandName)
            );
        }

        $parameters = $reflectionCommand->getConstructor()->getParameters();
        foreach ($parameters as $parameter) {
            $arguments[] = $dataRequest[$parameter->getName()];
        }

        return $reflectionCommand->newInstanceArgs($arguments);
    }
}
