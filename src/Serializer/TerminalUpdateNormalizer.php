<?php

namespace App\Serializer;

use App\Entity\TerminalUpdate\TerminalUpdate;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Vich\UploaderBundle\Storage\StorageInterface;

readonly class TerminalUpdateNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private NormalizerInterface $normalizer,
        private StorageInterface    $storage
    ) {
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        /* @var TerminalUpdate $object */
        $data = $this->normalizer->normalize($object, $format, $context);

        $data['update'] = $this->storage->resolveUri($object, 'updateFile');

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof TerminalUpdate;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            TerminalUpdate::class => true,
        ];
    }
}