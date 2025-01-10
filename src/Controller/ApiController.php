<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    #[Route('/api/ok', name: 'api_ok', methods: ['GET'])]
    public function ok(): Response
    {
        return new Response('OK!', Response::HTTP_OK);
    }
}