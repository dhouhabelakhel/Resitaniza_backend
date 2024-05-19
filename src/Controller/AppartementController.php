<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Appartment;
use App\Repository\ApartmentRepository;
use App\Repository\AppartmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
#[Route('/api/appartement')]

class AppartementController extends AbstractController
{
    #[Route('/', name: 'all_appartement',methods:['GET'])]
    public function index(AppartmentRepository $appartmentRepository): Response
    {
        return $this->json($appartmentRepository->findAll(), JsonResponse::HTTP_OK);
    }
    #[Route('/', name: 'create_apartment', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ): JsonResponse {
        $apartment = $serializer->deserialize($request->getContent(), Appartment::class, 'json');

        $errors = $validator->validate($apartment);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json($errorMessages, JsonResponse::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($apartment);
        $entityManager->flush();

        return $this->json($apartment, JsonResponse::HTTP_CREATED);
    }

    #[Route('/apartments/{id}', name: 'get_apartment', methods: ['GET'])]
    public function get(?Appartment $apartment): JsonResponse {
        if($apartment){
            return $this->json($apartment,JsonResponse::HTTP_FOUND);

        }else return $this->json('not found',JsonResponse::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', name: 'update_apartment', methods: ['PUT'])]
    public function update(
        Request $request,
        Appartment $apartment,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ): JsonResponse {
        $serializer->deserialize($request->getContent(), Appartment::class, 'json', ['object_to_populate' => $apartment]);

        $errors = $validator->validate($apartment);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json($errorMessages, JsonResponse::HTTP_BAD_REQUEST);
        }

        $entityManager->flush();

        return $this->json($apartment);
    }

    #[Route('/{id}', name: 'delete_apartment', methods: ['DELETE'])]
    public function delete(?Appartment $apartment, EntityManagerInterface $entityManager): JsonResponse {
       
        if($apartment){
            $entityManager->remove($apartment);
            $entityManager->flush();
            return $this->json('appartement deleted',JsonResponse::HTTP_OK);

        }else return $this->json('not found',JsonResponse::HTTP_NOT_FOUND);
    }
}
