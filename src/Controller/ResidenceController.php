<?php

namespace App\Controller;

use App\Entity\Residence;
use App\Repository\ResidenceRepository;
use App\Repository\ResidentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/residence')]
class ResidenceController extends AbstractController
{    #[Route('/', name: 'all_residence',methods:['Get'])]
public function getAll(ResidentRepository $residenceRepository){
    return $this->json($residenceRepository->getAll(),JsonResponse::HTTP_OK);
}

    #[Route('/', name: 'add_residence',methods:['POST'])]
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
    #[Route('/{id}', name: 'get_residence', methods: ['GET'])]
    public function get(?Residence $residence): JsonResponse {
        if($residence){
        return $this->json($residence,JsonResponse::HTTP_FOUND);
        }
        else return $this->json('not found',JsonResponse::HTTP_NOT_FOUND);
    }
    #[Route('/{id}', name: 'update_residence', methods: ['PUT'])]
    public function update(
        Request $request,
        Residence $residence,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ): JsonResponse {
        $serializer->deserialize($request->getContent(), Residence::class, 'json', ['object_to_populate' => $residence]);

        $errors = $validator->validate($residence);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json($errorMessages, JsonResponse::HTTP_BAD_REQUEST);
        }

        $entityManager->flush();

        return $this->json($residence);
    }

    #[Route('/{id}', name: 'delete_residence', methods: ['DELETE'])]
    public function delete(?Residence $residence, EntityManagerInterface $entityManager): JsonResponse {
        if($residence){
            $entityManager->remove($residence);
            $entityManager->flush();
    
            return $this->json('residence deleted',JsonResponse::HTTP_OK);
        }else return $this->json('not found',JsonResponse::HTTP_NOT_FOUND);
       
    }

}
