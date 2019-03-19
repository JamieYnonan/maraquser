<?php

namespace Mu\Infrastructure\Serializer;

use Bvon\BasicValueObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\YamlFileLoader;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\JsonSerializableNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;

class SerializerFactory
{
    public static function build(): Serializer
    {
        return new Serializer(
            [
                new BasicValueObjectNormalizer(),
                new DateTimeNormalizer(),
                new PropertyNormalizer(
                    new ClassMetadataFactory(
                        new YamlFileLoader(
                            __DIR__ . '/serializer.yaml'
                        )
                    )
                ),
                new JsonSerializableNormalizer()
            ],
            [new JsonEncoder()]
        );
    }
}
