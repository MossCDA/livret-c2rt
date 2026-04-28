<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BookletController extends AbstractController
{
    #[Route('/booklet', name: 'app_booklet')]
    public function index(): Response
    {
        return $this->render('booklet/index.html.twig', [
            'controller_name' => 'BookletController',
        ]);
    }
}
