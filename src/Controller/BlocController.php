<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Bloc;
use App\Repository\BlocRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
#[Route('/api/bloc')]

class BlocController extends AbstractController
{
    #[Route('/', name: 'all_appartement',methods:['GET'])]
    public function index(BlocRepository $blocRepository): Response
    {
        return $this->json($blocRepository->findAll(), JsonResponse::HTTP_OK);
    }
    #[Route('/', name: 'create_bloc', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ): JsonResponse {
        $bloc = $serializer->deserialize($request->getContent(), Bloc::class, 'json');

        $errors = $validator->validate($bloc);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json($errorMessages, JsonResponse::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($bloc);
        $entityManager->flush();

        return $this->json($bloc, JsonResponse::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'get_bloc', methods: ['GET'])]
    public function get(?Bloc $bloc): JsonResponse {
        if($bloc){
            return $this->json($bloc,JsonResponse::HTTP_FOUND);

        }
        else return $this->json('not found',JsonResponse::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', name: 'update_bloc', methods: ['PUT'])]
    public function update(
        Request $request,
        Bloc $bloc,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ): JsonResponse {
        $serializer->deserialize($request->getContent(), Bloc::class, 'json', ['object_to_populate' => $bloc]);

        $errors = $validator->validate($bloc);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json($errorMessages, JsonResponse::HTTP_BAD_REQUEST);
        }

        $entityManager->flush();

        return $this->json($bloc);
    }

    #[Route('/{id}', name: 'delete_bloc', methods: ['DELETE'])]
    public function delete(Bloc $bloc, EntityManagerInterface $entityManager): JsonResponse {
       if($bloc){

        $entityManager->remove($bloc);
        $entityManager->flush();

        return $this->json('bloc deleted', JsonResponse::HTTP_OK);
       }    else return $this->json('not found',JsonResponse::HTTP_NOT_FOUND);

    }
}
