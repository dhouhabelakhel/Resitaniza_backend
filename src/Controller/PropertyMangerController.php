<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\PropertyMangerRepository;
use App\Entity\PropertyManger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer; 
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
#[Route('/Api/PropertyManger')]

class PropertyMangerController extends AbstractController
{
    #[Route('/', name: 'app_property_manger')]
    public function index(PropertyMangerRepository $propertyManger): Response
    {
      return $this->json($propertyManger->findAll(),JsonResponse::HTTP_OK);
    }
    #[Route('/new', name: 'app_manger_new', methods: [ 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,SerializerInterface $serializer,
    UrlGeneratorInterface $urlGenerator,UserPasswordHasherInterface $hasher)
    {try {
        $manger = $serializer->deserialize($request->getContent(), PropertyManger::class, 'json');
    
        $hashedPassword = $hasher->hashPassword($manger, $manger->getPassword());
        $manger->setPassword($hashedPassword);
        
        $entityManager->persist($manger);
        $entityManager->flush();
    
        $jsonmanger = $serializer->serialize($manger, 'json', ['groups' => 'getmangers']);
        
        return $this->json($manger, JsonResponse::HTTP_CREATED);
    } catch (\Throwable $th) {
        return $this->json($th,JsonResponse::HTTP_BAD_REQUEST);

    }
        

    }
    #[Route('/{id}', name: 'app_manger_show', methods: ['GET'])]
    public function show(PropertyManger $manger): Response
    {
        return $this->json($manger,JsonResponse::HTTP_OK);
    }
    #[Route('/{id}/edit', name: 'app_manger_edit', methods: ['Put'])]
    public function edit(Request $request, PropertyManger $manger,
     EntityManagerInterface $entityManager,
     SerializerInterface $serializer,
     )
    {
        try {
            $updatedManger = $serializer->deserialize($request->getContent(), 
            PropertyManger::class, 
            'json', 
            [AbstractNormalizer::OBJECT_TO_POPULATE => $manger]);
            
            $entityManager->persist($updatedManger);
            $entityManager->flush();
            return $this->json($updatedManger,JsonResponse::HTTP_CREATED);
        } catch (\Throwable $th) {
            return $this->json($$th,JsonResponse::HTTP_BAD_REQUEST);

        }
      

    }
    #[Route('/delete/{id}', name: 'app_manger_delete', methods: ['DELETE'])]
    public function delete(Request $request, PropertyManger $manger, EntityManagerInterface $entityManager): JsonResponse
    {
            $entityManager->remove($manger);
            $entityManager->flush();
            return new JsonResponse(['message' => 'Manger deleted successfully'], Response::HTTP_OK);
       
    }
}
