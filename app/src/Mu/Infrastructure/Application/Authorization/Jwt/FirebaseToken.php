<?php

namespace Mu\Infrastructure\Application\Authorization\Jwt;

use Firebase\JWT\JWT;
use Symfony\Component\Serializer\Serializer;

final class FirebaseToken implements Token
{
    private $algorithm;
    private $key;
    private $serializer;

    public function __construct(
        string $algorithm,
        string $pathKey,
        Serializer $serializer
    ) {
        $this->algorithm = $algorithm;
        $this->key = file_get_contents($pathKey);
        $this->serializer = $serializer;
    }

    public function encode(Payload $payload): string
    {
        return JWT::encode(
            $this->serializer->normalize(
                $payload,
                null,
                ['groups' => ['jwt']]
            ),
            $this->key,
            $this->algorithm
        );
    }

    public function decode(string $jwt): Payload
    {
        $payload = JWT::decode($jwt, $this->key, [$this->algorithm]);
        return $this->serializer->denormalize($payload, Payload::class);
    }
}
