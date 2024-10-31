<?php

namespace App\Serializer;

use App\Entity\Floor\Floor;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Vich\UploaderBundle\Storage\StorageInterface;

readonly class FloorNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private NormalizerInterface $normalizer,
        private StorageInterface    $storage
    ) {
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        /* @var Floor $object */
        $data = $this->normalizer->normalize($object, $format, $context);

        $data['image'] = $this->storage->resolveUri($object, 'imageFile');

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof Floor;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Floor::class => true,
        ];
    }
}