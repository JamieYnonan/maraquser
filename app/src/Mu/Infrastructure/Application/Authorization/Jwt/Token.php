<?php

namespace Mu\Infrastructure\Application\Authorization\Jwt;

/**
 * Interface Token
 * @package Mu\Infrastructure\Application\Authorization\Jwt
 */
interface Token
{
    /**
     * @param Payload $payload
     * @return string
     */
    public function encode(Payload $payload): string;

    /**
     * @param string $jwt
     * @return Payload
     */
    public function decode(string $jwt): Payload;
}
