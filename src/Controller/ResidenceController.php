<?php

namespace App\Controller;

use App\Entity\Residence;
use App\Repository\ResidenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/Api/residence')]
class ResidenceController extends AbstractController
{
    #[Route('/add', name: 'app_residence')]
    public function addResidence(Request $request,EntityManagerInterface $entitymanager,ResidenceRepository $residence,SerializerInterface  $serializer ): Response
    {$newResidence= $serializer->deserialize($request->getContent(), Residence::class, 'json');
        $existedResidence=$residence->findBy(["name"=>$newResidence->getName(),"city"=>$newResidence->getCity(),"street"=>$newResidence->getStreet()]);
        if($existedResidence){
            return $this->json("alerdy exist", JsonResponse::HTTP_FORBIDDEN);
        }else{
            $entitymanager->persist($newResidence);
            $entitymanager->flush();
            return $this->json($newResidence,JsonResponse::HTTP_CREATED);
        }
    }
}
