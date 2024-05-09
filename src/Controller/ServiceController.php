<?php

namespace App\Controller;

use App\Entity\Service;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
#[Route('/Api/Service')]

class ServiceController extends AbstractController
{
    #[Route('/', name: 'app_service',methods:['GET'])]
    public function index(NormalizerInterface $normalizer,PaginatorInterface $paginator,Request $request,ServiceRepository $service): JsonResponse
    {
       $services=$service->findAll();
       $pagination = $paginator->paginate(
        $services,
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
        'services' => $normalizedData,
        'pagination' => [
            'currentPage' => $pagination->getCurrentPageNumber(),
            'itemsPerPage' => $pagination->getItemNumberPerPage(),
            'totalItems' => $pagination->getTotalItemCount(),
            'totalPages' => $totalPages,
        ]
    ]);
    }
    #[Route('/add', name: 'add_service',methods:['Post'])]
 public function addService(Request $request,ServiceRepository $service,EntityManagerInterface $entityManager, SerializerInterface $serializer):JsonResponse{
    $newservice = $serializer->deserialize($request->getContent(), Service::class, 'json');
    $existedService=$service->findOneBy(['name'=>$newservice->getName()]);
    if ($existedService){
        return $this->json("alerdy exist", JsonResponse::HTTP_FORBIDDEN);
    }else {
        $entityManager->persist($newservice);
        $entityManager->flush();
        return $this->json($newservice, JsonResponse::HTTP_CREATED);

    }

 }
    #[Route('/{id}', name: 'show_service',methods:['GET'])]
public function getServiceById(? Service $service):JsonResponse{
    if($service){
        return $this->json($service, JsonResponse::HTTP_OK);

        }else  return $this->json("Service not found",  JsonResponse::HTTP_NOT_FOUND);

}
#[Route('/edit/{id}', name: 'edit_service',methods:['PUT'])]
public function editService(?Service $service,Request $request, SerializerInterface $serializer,EntityManagerInterface $entityManager)
{
    if ($service) {      
        $updatedservice = $serializer->deserialize(
            $request->getContent(),
           Service::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $service]
        );
    
        $entityManager->persist($updatedservice);
        $entityManager->flush();
        return $this->json($updatedservice, JsonResponse::HTTP_CREATED);
        

    } else {
        return $this->json("Not found", JsonResponse::HTTP_NOT_FOUND);
    }
}

#[Route('/delete/{id}', name: 'delete_service',methods:['DELETE'])]
public function deleteService(?Service $service,EntityManagerInterface $entityManager):JsonResponse{
    if($service){
        $entityManager->remove($service);
        $entityManager->flush();
        return new JsonResponse('Service deleted successfully', Response::HTTP_OK);
    } else
        return new JsonResponse("Not found", JsonResponse::HTTP_NOT_FOUND);
}

}
