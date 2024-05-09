<?php

namespace App\Controller;

use App\Entity\OfferService;
use App\Entity\Provider;
use App\Entity\Service;
use App\Repository\OfferServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


#[Route('/Api/OfferService')]
class OfferServiceController extends AbstractController
{
    #[Route('/', name: 'app_offer_service', methods: ['GET'])]
    public function getAllOffer(NormalizerInterface $normalizer,Request $request,PaginatorInterface $paginator,OfferServiceRepository $offer): JsonResponse
    {
        $offers=$offer->findAll();
        $pagination = $paginator->paginate(
            $offers,
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
                    'offer' => $normalizedData,
                    'pagination' => [
                        'currentPage' => $pagination->getCurrentPageNumber(),
                        'itemsPerPage' => $pagination->getItemNumberPerPage(),
                        'totalItems' => $pagination->getTotalItemCount(),
                        'totalPages' => $totalPages,
                    ]
                ]);
                
    }
    #[Route('/{id}', name: 'show_offer_service', methods: ['GET'])]
    public function show(?OfferService $offer): JsonResponse
    {
        if($offer){
            return $this->json($offer, JsonResponse::HTTP_OK);

        }else  return $this->json("Offer not found",  JsonResponse::HTTP_NOT_FOUND);

    }

    #[Route('/add', name: 'add_offer_service', methods: ['POST'])]
    public function addOffer(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $requestData = json_decode($request->getContent(), true);

        $provider = $entityManager->getRepository(Provider::class)->find($requestData['provider']);
        if (!$provider) {
            return $this->json("Provider not found", JsonResponse::HTTP_NOT_FOUND);
        }

        $service = $entityManager->getRepository(Service::class)->find($requestData['service']);
        if (!$service) {
            return $this->json("Service not found",  JsonResponse::HTTP_NOT_FOUND);
        }
        $existedOffer = $entityManager->getRepository(OfferService::class)->findBy(["provider" => $provider, "service" => $service]);
        if ($existedOffer) {
            return $this->json("offer exist", JsonResponse::HTTP_FORBIDDEN);
        }
        $offer = $serializer->deserialize($request->getContent(), OfferService::class, 'json');
        // $provider->addOfferService($offer);
        // $service->addOfferService($offer);
        $offer->setProvider($provider);
        $offer->setService($service);
        $entityManager->persist($offer);
        $entityManager->flush();
        return $this->json($offer, JsonResponse::HTTP_CREATED);
    }
    //edit only price or description
    #[Route('/edit/{id}', name: 'edit_offer_service', methods: ['Put'])]
    public function edit(
        Request $request,
        ?OfferService $offer,
        EntityManagerInterface $entityManager
    ) {
        if ($offer) {
            $requestData = json_decode($request->getContent(), true);
    
            if (isset($requestData['price'])) {
                $offer->setPrice($requestData['price']);
            }
            if (isset($requestData['description'])) {
                $offer->setDescription($requestData['description']);
            }
    
            $entityManager->persist($offer);
            $entityManager->flush();
    
            return $this->json($offer, JsonResponse::HTTP_OK);
        } else {
            return $this->json("Not found", JsonResponse::HTTP_NOT_FOUND);
        }
    }
    #[Route('/delete/{id}', name: 'delete_offer_service', methods: ['DELETE'])]
    public function delete(?OfferService $offer, EntityManagerInterface $entityManager): JsonResponse
    {
        if ($offer) {
            $entityManager->remove($offer);
            $entityManager->flush();
            return new JsonResponse('offer deleted successfully', Response::HTTP_OK);
        } else

            return new JsonResponse("Not found", JsonResponse::HTTP_NOT_FOUND);
    }
}
