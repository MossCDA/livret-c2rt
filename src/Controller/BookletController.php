<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/booklet')]
class BookletController extends AbstractController
{
    #[Route('/', name: 'app_booklet_index')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        return $this->render('booklet/index.html.twig');
    }
}