<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Review;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
#[Route('/api/review')]

class ReviewController extends AbstractController
{  #[Route('/', name: 'app_provider')]
    public function index(ReviewRepository $reviewRepository): Response
    {
        return $this->json($reviewRepository->findAll(), JsonResponse::HTTP_OK);
    }
     #[Route('/', name: 'create_review', methods: ['POST'])]
    public function addReview(
        Request $request,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ): JsonResponse {
        $review = $serializer->deserialize($request->getContent(), Review::class, 'json');

        $errors = $validator->validate($review);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json($errorMessages, JsonResponse::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($review);
        $entityManager->flush();

        return $this->json($review, JsonResponse::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'get_review', methods: ['GET'])]
    public function get(?Review $review): JsonResponse {
        if($review){
            return $this->json($review,JsonResponse::HTTP_FOUND);

        }else return $this->json('not found',JsonResponse::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', name: 'update_review', methods: ['PUT'])]
    public function update(
        Request $request,
        Review $review,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ): JsonResponse {
        $serializer->deserialize($request->getContent(), Review::class, 'json', ['object_to_populate' => $review]);

        $errors = $validator->validate($review);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json($errorMessages, JsonResponse::HTTP_BAD_REQUEST);
        }

        $entityManager->flush();

        return $this->json($review);
    }

    #[Route('/{id}', name: 'delete_review', methods: ['DELETE'])]
    public function delete(?Review $review, EntityManagerInterface $entityManager): JsonResponse {
      if($review){
        $entityManager->remove($review);
        $entityManager->flush();

        return $this->json('Review deleted successfully', Response::HTTP_OK);
    }
       else         return new JsonResponse("Not found", JsonResponse::HTTP_NOT_FOUND);

    }
  
}
