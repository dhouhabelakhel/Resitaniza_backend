<?php

namespace App\Controller;

use App\Repository\MangerResidenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\MangerResidence;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
#[Route('/api/manager_residence')]
class ManagerResidenceController extends AbstractController
{
    #[Route('/', name: 'app_manager_residence')]
    public function index(MangerResidenceRepository $mangerResidenceRepository): Response
    {
        return $this->json($mangerResidenceRepository->findAll(),JsonResponse::HTTP_OK);
        
    }
    #[Route('/', name: 'app_manager_residence_add', methods: ['POST'])]
    public function add(
        Request $request,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ): Response {
        $data = json_decode($request->getContent(), true);
        $residence_manger = new MangerResidence();
        $serializer->deserialize(json_encode($data), MangerResidence::class, 'json', ['object_to_populate' => $residence_manger]);

        $errors = $validator->validate($residence_manger);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json($errorMessages, JsonResponse::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($residence_manger);
        $entityManager->flush();

        return $this->json($residence_manger, JsonResponse::HTTP_CREATED);
    }

   
    #[Route('/{id}', name: 'app_manager_residence_update', methods: ['PUT'])]
    public function update(
        Request $request,
        MangerResidence $mangerResidence,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ): Response {
        $data = json_decode($request->getContent(), true);
        $serializer->deserialize(json_encode($data), MangerResidence::class, 'json', ['object_to_populate' => $mangerResidence]);

        $errors = $validator->validate($mangerResidence);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json($errorMessages, JsonResponse::HTTP_BAD_REQUEST);
        }

        $entityManager->flush();

        return $this->json($mangerResidence, JsonResponse::HTTP_OK);
    }

  
}
