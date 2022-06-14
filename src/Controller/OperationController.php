<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OperationController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('index.html.twig', [
            'controller_name' => 'Home',
        ]);
    }

    #[Route('/operation/{method}', name: 'app_operation', requirements: ['method' => 'deposit|withdraw'])]
    public function index($method): Response
    {
        return $this->render('operation/index.html.twig', [
            'controller_name' => 'OperationController',
            'method' => $method,
        ]);
    }
}
