<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\PropertyMangerRepository;
use App\Entity\PropertyManger;
use App\Entity\Residence;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/PropertyManger')]

class PropertyMangerController extends AbstractController
{
    #[Route('/', name: 'app_property_manger')]
    public function index(PropertyMangerRepository $propertyManger): Response
    {
        return $this->json($propertyManger->findAll(), JsonResponse::HTTP_OK);
    }
    #[Route('/', name: 'app_manger_new', methods: ['POST'])]
    public function addManger(ValidatorInterface $validator,PropertyMangerRepository $manger, Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, UserPasswordHasherInterface $hasher)
    {   $newmanger = $serializer->deserialize($request->getContent(), PropertyManger::class, 'json');
        $existedemail = $manger->findOneBy(['email' => $newmanger->getEmail()]);
        $existedcin = $manger->findOneBy(['cin' => $newmanger->getCin()]);
        if ($existedemail || $existedcin) {
            return $this->json("alerdy exist", JsonResponse::HTTP_FORBIDDEN);
        } else {
            $photo=$newmanger->getPicture();
            if($photo instanceof UploadedFile){
                $fileTypeValidator = new Assert\File([
                    'maxSize' => '2M', 
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                        'image/gif',
                    ],
                    'mimeTypesMessage' => 'Please upload a valid image file (JPEG, PNG, or GIF).',
                ]);
                $errors = $validator->validate($photo, $fileTypeValidator);
                if (count($errors) > 0) {
                    $errorMessages = [];
                    foreach ($errors as $error) {
                        $errorMessages[] = $error->getMessage();
                    }
                    return $this->json($errorMessages, JsonResponse::HTTP_BAD_REQUEST);
                }
                $uploadsDirectory = $this->getParameter('uploads_directory');
                $photoName = md5(uniqid()).'.'.$photo->guessExtension();
                $photo->move($uploadsDirectory, $photoName);
                $newmanger->setPicture($photoName);
                $hashedPassword = $hasher->hashPassword($newmanger, $newmanger->getPassword());
                $newmanger->setPassword($hashedPassword);
                $entityManager->persist($newmanger);
                $entityManager->flush();
                return $this->json($newmanger, JsonResponse::HTTP_CREATED);
            }else{
                return $this->json("Photo must be provided", JsonResponse::HTTP_BAD_REQUEST);

            }
       }
    }
    #[Route('/{id}', name: 'app_manger_show', methods: ['GET'])]
    public function show(?PropertyManger $manger): Response
    {if($manger){
        return $this->json($manger, JsonResponse::HTTP_OK);

    }else return $this->json("Not found", JsonResponse::HTTP_NOT_FOUND);
    }
    #[Route('/{id}', name: 'app_manger_edit', methods: ['Put'])]
    public function edit(
        Request $request,
       ? PropertyManger $manger,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
    ) {if ($manger){
        $updatedManger = $serializer->deserialize(
            $request->getContent(),
            PropertyManger::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $manger]
        );

        $entityManager->persist($updatedManger);
        $entityManager->flush();
        return $this->json($updatedManger, JsonResponse::HTTP_CREATED);
    }else return $this->json("Not found", JsonResponse::HTTP_NOT_FOUND);

       
    }



    #[Route('/{id}', name: 'app_manger_delete', methods: ['DELETE'])]
    public function delete(? PropertyManger $manger, EntityManagerInterface $entityManager): JsonResponse
    {if($manger){
        $entityManager->remove($manger);
        $entityManager->flush();
        return new JsonResponse(['message' => 'Manger deleted successfully'], Response::HTTP_OK);
    }else return $this->json("Not found", JsonResponse::HTTP_NOT_FOUND);
       
    }
}
