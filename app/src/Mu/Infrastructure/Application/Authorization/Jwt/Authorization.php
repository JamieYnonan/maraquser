<?php
namespace Mu\Infrastructure\Application\Authorization\Jwt;

use Mu\Infrastructure\Application\Authorization\AuthorizationException;

final class Authorization
{
    const TYPE = 'Bearer';

    private $credential;
    private $type;

    public static function byAuthorization(string $authorization): self
    {
        list($type, $credential) = explode(' ', $authorization);
        return new self($credential, $type);
    }

    private function __construct(string $type, string $credential)
    {
        $this->setType($type);
        $this->setCredential($credential);
    }

    private function setType(string $type): void
    {
        if ($type !== self::TYPE) {
            throw AuthorizationException::invalidType($type);
        }

        $this->type = $type;
    }

    private function setCredential(string $credential): void
    {
        $this->credential = $credential;
    }

    public static function byCredential(string $credential): self
    {
        return new static(static::TYPE, $credential);
    }

    public function getCredential(): string
    {
        return $this->credential;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
