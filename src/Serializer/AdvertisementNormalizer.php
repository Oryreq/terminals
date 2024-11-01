<?php

namespace App\Serializer;

use App\Entity\Advertisement\Advertisement;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Vich\UploaderBundle\Storage\StorageInterface;

readonly class AdvertisementNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private NormalizerInterface $normalizer,
        private StorageInterface    $storage
    ) {
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        /* @var Advertisement $object */
        $data = $this->normalizer->normalize($object, $format, $context);

        $data['advertisement'] = $this->storage->resolveUri($object, 'advertisementFile');

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof Advertisement;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Advertisement::class => true,
        ];
    }
}