<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ResidentRepository;
use App\Entity\Resident;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

#[Route('/api/resident')]
class ResidentController extends AbstractController
{
    #[Route('/', name: 'app_resident',methods:['GET'])]
    public function getResidents(ResidentRepository $residentRepository): Response
    {
        return $this->json($residentRepository->findAll(), JsonResponse::HTTP_OK);
    }
    #[Route('/', name: 'app_resident_new', methods: ['POST'])]
    public function addResident(ResidentRepository $resident, Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, UserPasswordHasherInterface $hasher)
    {
        $newresident = $serializer->deserialize($request->getContent(), Resident::class, 'json');
        $existedemail = $resident->findOneBy(['email' => $newresident->getEmail()]);
        $existedcin = $resident->findOneBy(['cin' => $newresident->getCin()]);
        if ($existedemail || $existedcin) {
            return $this->json("alerdy exist", JsonResponse::HTTP_FORBIDDEN);
        } else {
            $hashedPassword = $hasher->hashPassword($newresident, $newresident->getPassword());
            $newresident->setPassword($hashedPassword);
            $entityManager->persist($newresident);
            $entityManager->flush();
            return $this->json($newresident, JsonResponse::HTTP_CREATED);
        }
    }
    #[Route('/{id}', name: 'app_resident_show', methods: ['GET'])]
    public function finResident(?Resident $resident): Response
    {
        if ($resident){
            return $this->json($resident, JsonResponse::HTTP_OK);

        }else return $this->json("Not found", JsonResponse::HTTP_NOT_FOUND);
    }
    #[Route('/{id}', name: 'app_resident_edit', methods: ['Put'])]
    public function updateResident(
        Request $request,
        ?Resident $resident,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
    ) {if($resident){
        $updatedResident = $serializer->deserialize(
            $request->getContent(),
            Resident::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $resident]
        );

        $entityManager->persist($updatedResident);
        $entityManager->flush();
        return $this->json($updatedResident, JsonResponse::HTTP_CREATED);
    }else return $this->json("Not found", JsonResponse::HTTP_NOT_FOUND);
           
        
    }
    #[Route('/{id}', name: 'app_resident_delete', methods: ['DELETE'])]
    public function deleteResident(? Resident $resident, EntityManagerInterface $entityManager): JsonResponse
    {if ($resident){

        $entityManager->remove($resident);
        $entityManager->flush();
        return new JsonResponse(['Resident deleted successfully'], Response::HTTP_OK);
    }else return $this->json("Not found", JsonResponse::HTTP_NOT_FOUND);
    }
}
