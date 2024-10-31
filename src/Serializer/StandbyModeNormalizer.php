<?php

namespace App\Serializer;

use App\Entity\StandbyMode\StandbyMode;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Vich\UploaderBundle\Storage\StorageInterface;

readonly class StandbyModeNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private NormalizerInterface $normalizer,
        private StorageInterface    $storage
    ) {
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        /* @var StandbyMode $object */
        $data = $this->normalizer->normalize($object, $format, $context);

        $data['mode'] = $this->storage->resolveUri($object, 'modeFile');

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof StandbyMode;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            StandbyMode::class => true,
        ];
    }
}