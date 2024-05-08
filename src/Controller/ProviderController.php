<?php

namespace App\Controller;

use App\Entity\Provider;
use App\Entity\Service;
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
        return $this->json($providerRepository->findAll(), JsonResponse::HTTP_OK);
    }
    #[Route('/new', name: 'app_provider_new', methods: ['POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        UserPasswordHasherInterface $hasher
    ) {
        $data = json_decode($request->getContent(), true);
        $provider = $serializer->deserialize($request->getContent(), Provider::class, 'json');
        $existedemail = $entityManager->getRepository(Provider::class)->findOneBy(['email' => $provider->getEmail()]);
        $existedcin = $entityManager->getRepository(Provider::class)->findOneBy(['cin' => $provider->getCin()]);
        if ($existedemail || $existedcin) {
            return $this->json("alerdy exist", JsonResponse::HTTP_FORBIDDEN);
        } else {
            $hashedPassword = $hasher->hashPassword($provider, $provider->getPassword());
            $provider->setPassword($hashedPassword);
            $entityManager->persist($provider);
            foreach ($data['services'] as $serviceData) {
                $service = $entityManager->getRepository(Service::class)->findOneBy(['name' => $serviceData['name']]);
                if (!$service) {
                    $service = new Service();
                    $service->setName($serviceData['name']);
                    $entityManager->persist($service);
                }
            }

            $entityManager->flush();
            return $this->json($provider, JsonResponse::HTTP_CREATED);
        }
    }
    #[Route('/{id}', name: 'app_provider_show', methods: ['GET'])]
    public function show(?Provider $provider, EntityManagerInterface $entityManager): Response
    {
if($provider){
    return $this->json($provider, JsonResponse::HTTP_OK);

}else{
    return $this->json("Not found", JsonResponse::HTTP_NOT_FOUND);
  
}
    }
    #[Route('/{id}/edit', name: 'app_provider_edit', methods: ['Put'])]
    public function edit(
        Request $request,
        ?Provider $provider,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
    ) {
if($provider){
    $updatedprovider = $serializer->deserialize(
        $request->getContent(),
        Provider::class,
        'json',
        [AbstractNormalizer::OBJECT_TO_POPULATE => $provider]
    );

    $entityManager->persist($updatedprovider);
    $entityManager->flush();
    return $this->json($updatedprovider, JsonResponse::HTTP_CREATED);
}else return $this->json("Not found", JsonResponse::HTTP_NOT_FOUND);
       
    }
    #[Route('/delete/{id}', name: 'app_provider_delete', methods: ['DELETE'])]
    public function delete( ?Provider $provider, EntityManagerInterface $entityManager): JsonResponse
    {if ($provider){

        $entityManager->remove($provider);
        $entityManager->flush();
        return new JsonResponse(['message' => 'provider deleted successfully'], Response::HTTP_OK);
    }else     return $this->json("Not found", JsonResponse::HTTP_NOT_FOUND);

    }
}
