<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\DemandeService;
use App\Repository\DemandeServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Knp\Component\Pager\PaginatorInterface;
#[Route('/api/DemandeService')]

class DemandeServiceController extends AbstractController
{
    #[Route('/', name: 'app_Demande_Service', methods: ['GET'])]
    public function getAllDemandes(NormalizerInterface $normalizer,Request $request,PaginatorInterface $paginator,DemandeServiceRepository $Demande): JsonResponse
    {
        $demandes=$Demande->findAll();
        $pagination = $paginator->paginate(
            $demandes,
            $request->query->getInt('page', 1), 
            2
        );
                $data = $pagination->getItems();
                $normalizedData = [];
                foreach ($data as $item) {
                    $normalizedData[] = $normalizer->normalize($item);
                }
                $totalPages = ceil($pagination->getTotalItemCount() / $pagination->getItemNumberPerPage());

                return new JsonResponse([
                    'demandes_service' => $normalizedData,
                    'pagination' => [
                        'currentPage' => $pagination->getCurrentPageNumber(),
                        'itemsPerPage' => $pagination->getItemNumberPerPage(),
                        'totalItems' => $pagination->getTotalItemCount(),
                        'totalPages' => $totalPages,
                    ]
                ]);
                
    }
    #[Route('/', name: 'create_demande_service', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ): JsonResponse {
        $demandeService = $serializer->deserialize($request->getContent(), DemandeService::class, 'json');

        $errors = $validator->validate($demandeService);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json($errorMessages, JsonResponse::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($demandeService);
        $entityManager->flush();

        return $this->json($demandeService, JsonResponse::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'get_demande_service', methods: ['GET'])]
    public function get(?DemandeService $demandeService): JsonResponse {
        if($demandeService){
            return $this->json($demandeService,JsonResponse::HTTP_FOUND);
        }
        else return $this->json('Demande Service not found',JsonResponse::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', name: 'update_demande_service', methods: ['PUT'])]
    public function update(
        Request $request,
        DemandeService $demandeService,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ): JsonResponse {
        $serializer->deserialize($request->getContent(), DemandeService::class, 'json', ['object_to_populate' => $demandeService]);

        $errors = $validator->validate($demandeService);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json($errorMessages, JsonResponse::HTTP_BAD_REQUEST);
        }

        $entityManager->flush();

        return $this->json($demandeService);
    }

    #[Route('/{id}', name: 'delete_demande_service', methods: ['DELETE'])]
    public function delete(?DemandeService $demandeService, EntityManagerInterface $entityManager): JsonResponse {
        if($demandeService){
            $entityManager->remove($demandeService);
            $entityManager->flush();
    
            return $this->json('demande deleted successfully', Response::HTTP_OK);
        }
        return new JsonResponse("Not found", JsonResponse::HTTP_NOT_FOUND);

    }
}
