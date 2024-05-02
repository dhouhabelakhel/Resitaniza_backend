<?php

namespace App\Controller;

use App\Entity\Provider;
use App\Repository\ProviderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer; 
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
#[Route('/Api/provider')]

class ProviderController extends AbstractController
{
 
    #[Route('/', name: 'app_provider')]
    public function index(ProviderRepository $providerRepository): Response
    {     
           return $this->json($providerRepository->findAll(),JsonResponse::HTTP_OK);
    }
    #[Route('/new', name: 'app_provider_new', methods: [ 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,SerializerInterface $serializer,
    UrlGeneratorInterface $urlGenerator,UserPasswordHasherInterface $hasher)
    {try {
        $provider = $serializer->deserialize($request->getContent(), Provider::class, 'json');
    
        $hashedPassword = $hasher->hashPassword($provider, $provider->getPassword());
        $provider->setPassword($hashedPassword);
        
        $entityManager->persist($provider);
        $entityManager->flush();
    
        $jsonprovider = $serializer->serialize($provider, 'json', ['groups' => 'getproviders']);
        
        return $this->json($provider, JsonResponse::HTTP_CREATED);
    } catch (\Throwable $th) {
        return $this->json($th,JsonResponse::HTTP_BAD_REQUEST);

    }
        

    }
    #[Route('/{id}', name: 'app_provider_show', methods: ['GET'])]
    public function show(Provider $provider): Response
    {
        return $this->json($provider,JsonResponse::HTTP_OK);
    }
        #[Route('/{id}/edit', name: 'app_provider_edit', methods: ['Put'])]
    public function edit(Request $request, Provider $provider,
     EntityManagerInterface $entityManager,
     SerializerInterface $serializer,
     )
    {
        try {
            $updatedprovider = $serializer->deserialize($request->getContent(), 
            Provider::class, 
            'json', 
            [AbstractNormalizer::OBJECT_TO_POPULATE => $provider]);
            
            $entityManager->persist($updatedprovider);
            $entityManager->flush();
            return $this->json($updatedprovider,JsonResponse::HTTP_CREATED);
        } catch (\Throwable $th) {
            return $this->json($$th,JsonResponse::HTTP_BAD_REQUEST);

        }
      

    }
    #[Route('/delete/{id}', name: 'app_provider_delete', methods: ['DELETE'])]
    public function delete(Request $request, Provider $provider, EntityManagerInterface $entityManager): JsonResponse
    {
            $entityManager->remove($provider);
            $entityManager->flush();
            return new JsonResponse(['message' => 'provider deleted successfully'], Response::HTTP_OK);
       
    }
}
