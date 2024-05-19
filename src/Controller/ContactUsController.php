<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactUsController extends AbstractController
{
    #[Route('/api/contact_us', name: 'app_contact_us',methods:['POST'])]
    public function sendEmail(Request $request, MailerInterface $mailer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email']) || !isset($data['message'])) {
            return new JsonResponse(['error' => 'Missing email or message'], 400);
        }

        $email = (new Email())
            ->from($data['email'])
            ->to('dhouhabelakhel2001@gmail.com')
            ->subject($data['subject'])
            ->text($data['message']);

        $mailer->send($email);

        return new JsonResponse(['message' => 'Email sent successfully'], 200);
    }
}
