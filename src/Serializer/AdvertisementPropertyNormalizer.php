<?php

namespace App\Serializer;

use App\Entity\Advertisement\Advertisement;
use App\Entity\Advertisement\Form\AdvertisementProperty;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Vich\UploaderBundle\Storage\StorageInterface;

readonly class AdvertisementPropertyNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private NormalizerInterface $normalizer,
        private StorageInterface    $storage
    ) {
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        /* @var AdvertisementProperty $object */
        $data = $this->normalizer->normalize($object, $format, $context);

        $advertisement = $object->getAdvertisement();
        $data['advertisementImage'] = $this->storage->resolveUri($advertisement, 'advertisementFile');

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof AdvertisementProperty;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            AdvertisementProperty::class => true,
        ];
    }

}