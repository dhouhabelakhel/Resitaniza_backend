<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ResidentRepository;
use App\Entity\resident;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer; 
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
#[Route('/Api/resident')]
class ResidentController extends AbstractController
{
    #[Route('/', name: 'app_resident')]
    public function getResidents(ResidentRepository $residentRepository): Response
    {     
           return $this->json($residentRepository->findAll(),JsonResponse::HTTP_OK);
    }
    #[Route('/new', name: 'app_resident_new', methods: [ 'POST'])]
    public function addResident(Request $request, EntityManagerInterface $entityManager,SerializerInterface $serializer,
    UrlGeneratorInterface $urlGenerator,UserPasswordHasherInterface $hasher)
    {
        $resident = $serializer->deserialize($request->getContent(), Resident::class, 'json');
    
        $hashedPassword = $hasher->hashPassword($resident, $resident->getPassword());
        $resident->setPassword($hashedPassword);
        
        $entityManager->persist($resident);
        $entityManager->flush();
    
        $jsonResident = $serializer->serialize($resident, 'json', ['groups' => 'getResidents']);
        
        return $this->json($resident, JsonResponse::HTTP_CREATED);
   
        

    }
    #[Route('/{id}', name: 'app_resident_show', methods: ['GET'])]
    public function finResident(Resident $resident): Response
    {
        return $this->json($resident,JsonResponse::HTTP_OK);
    }
        #[Route('/{id}/edit', name: 'app_resident_edit', methods: ['Put'])]
    public function updateResident(Request $request, Resident $resident,
     EntityManagerInterface $entityManager,
     SerializerInterface $serializer,
     )
    {
        try {
            $updatedResident = $serializer->deserialize($request->getContent(), 
            Resident::class, 
            'json', 
            [AbstractNormalizer::OBJECT_TO_POPULATE => $resident]);
            
            $entityManager->persist($updatedResident);
            $entityManager->flush();
            return $this->json($updatedResident,JsonResponse::HTTP_CREATED);
        } catch (\Throwable $th) {
            return $this->json($$th,JsonResponse::HTTP_BAD_REQUEST);

        }
      

    }
    #[Route('/delete/{id}', name: 'app_resident_delete', methods: ['DELETE'])]
    public function deleteResident(Request $request, Resident $resident, EntityManagerInterface $entityManager): JsonResponse
    {
            $entityManager->remove($resident);
            $entityManager->flush();
            return new JsonResponse(['message' => 'Resident deleted successfully'], Response::HTTP_OK);
       
    }
}
