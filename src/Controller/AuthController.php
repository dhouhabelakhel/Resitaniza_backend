<?php

namespace App\Controller;

use App\Entity\Resident;
use App\Entity\Provider;
use App\Entity\PropertyManger;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Doctrine\Persistence\ManagerRegistry; 
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AuthController extends AbstractController
{
    private $jwtManager;
    private $doctrine; 
    private $passwordEncoder; 
    private $tokenStorage;

    public function __construct( TokenStorageInterface $tokenStorage,JWTTokenManagerInterface $jwtManager,ManagerRegistry $doctrine,        UserPasswordHasherInterface $passwordHasher
    )
    {        $this->tokenStorage = $tokenStorage;

        $this->jwtManager = $jwtManager;
        $this->doctrine=$doctrine;
        $this->passwordEncoder = $passwordHasher;

    }
    #[Route('/testauth', name: 'app_testauth',methods: [ 'POST'])]
public function testauth(Request $req){
    $credentials = json_decode($req->getContent(), true);
    $userRole = $this->getUserRoleByEmail($credentials['email']);
    $user =$this->doctrine->getRepository('App\Entity\\' . ucfirst($userRole))
    ->findOneBy(['email' => $credentials['email']]);
   $test= $this->isValidPassword($user, $credentials['password'],$userRole,$credentials['email']);
   $newuser=new Resident();

   $data = [
       'id' => $user->getId(),
       'email' => $user->getEmail(),
       'role' => $userRole
   ];
   $newuser->setId($data['id']);
return $this->json($newuser->getId());
}
    #[Route('/auth', name: 'app_auth',methods: [ 'POST'])]

    public function login(Request $request): JsonResponse
    {
        $credentials = json_decode($request->getContent(), true);

        if (!isset($credentials['email']) || !isset($credentials['password'])) {
            return new JsonResponse(['error' => 'Email and password are required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $userRole = $this->getUserRoleByEmail($credentials['email']);

        if (!$userRole) {
            return new JsonResponse(['error' => 'User not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $user =$this->doctrine->getRepository('App\Entity\\' . ucfirst($userRole))
            ->findOneBy(['email' => $credentials['email']]);

        if (!$user || !$this->isValidPassword($user, $credentials['password'],$userRole,$credentials['email'])) {
            return new JsonResponse(['error' => 'invalid password'], JsonResponse::HTTP_NOT_FOUND);

        
        }

        $token = $this->jwtManager->create($user);

        return new JsonResponse(['token' => $token]);
    }

    private function getUserRoleByEmail(string $email): ?string
    {
        $resident = $this->doctrine->getRepository('App\Entity\Resident')->findOneBy(['email' => $email]);
        if ($resident) {
            return 'Resident';
        }

        $provider = $this->doctrine->getRepository('App\Entity\Provider')->findOneBy(['email' => $email]);
        if ($provider) {
            return 'Provider';
        }

        $manager = $this->doctrine->getRepository('App\Entity\PropertyManger')->findOneBy(['email' => $email]);
        if ($manager) {
            return 'PropertyManger';
        }

        return null;
    }
    private function isValidPassword(UserInterface $user, string $password,$userType,$email): bool
    {
        switch ($userType) {
            case 'Resident':
                $user = $this->doctrine->getRepository('App\Entity\Resident')->findOneBy(['email' => $email]);
                break;
            case 'Provider':
                $user = $this->doctrine->getRepository('App\Entity\Provider')->findOneBy(['email' => $email]);
                break;
            case 'PropertyManger':
                $user = $this->doctrine->getRepository('App\Entity\PropertyManger')->findOneBy(['email' => $email]);
                break;
            default:
                return new JsonResponse(['error' => 'Invalid user type'], JsonResponse::HTTP_BAD_REQUEST);
        }
        
      return  $isPasswordValid = $this->passwordEncoder->isPasswordValid($user, $password);    }
      #[Route('/logout', name: 'app_logout',methods: [ 'POST'])]

      public function logout(): Response
      {
          $this->tokenStorage->setToken(null);
  
          return $this->json('logout!!',JsonResponse::HTTP_OK);
      }
    }
