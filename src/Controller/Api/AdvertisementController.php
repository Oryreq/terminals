<?php

namespace App\Controller\Api;

use App\Repository\Advertisement\AdvertisementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Contracts\Service\Attribute\Required;

#[AsController]

class AdvertisementController extends AbstractController
{
    #[Required]
    public AdvertisementRepository $advertisementRepository;


    public function __invoke(): JsonResponse
    {
        $advertisements = new ArrayCollection($this->advertisementRepository->findAll());

        if ($advertisements->isEmpty()) {
            throw $this->createNotFoundException('Advertisements not found');
        }

        $properties = new ArrayCollection();
        for ($i = 0; $i < $advertisements->count(); $i++) {
            $propertiesInAdvertisement = $advertisements->get($i)->getProperties();
            for ($j = 0; $j < $propertiesInAdvertisement->count(); $j++) {
                $properties->add($propertiesInAdvertisement->get($j));
            }
        }

        $properties = $properties->toArray();

        usort($properties, function($item1, $item2) {
            return $item1->getDisplayOrder() <=> $item2->getDisplayOrder();
        });

        return $this->json($properties, 200, [], ['groups' => 'advertisement:item']);
    }
}