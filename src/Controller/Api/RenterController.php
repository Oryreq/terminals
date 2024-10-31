<?php

namespace App\Controller\Api;

use App\Repository\Renter\RenterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Contracts\Service\Attribute\Required;

#[AsController]
class RenterController extends AbstractController
{
    #[Required]
    public RenterRepository $renterRepository;


    public function __invoke(#[MapQueryParameter] ?string $word): JsonResponse
    {
        $renters = $this->renterRepository->findBy(['name' => $word]);

        if (!$renters) {
            throw $this->createNotFoundException('Renters not found');
        }

        return $this->json($renters, 200, [], ['groups' => 'renter:item']);
    }
}