<?php

namespace App\Controller;

use App\Entity\Bilan;
use App\Entity\Booklet;
use App\Form\BilanType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/bilan')]
#[IsGranted('ROLE_FORMATEUR')]
class BilanController extends AbstractController
{
    #[Route('/booklet/{id}/new', name: 'app_bilan_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Booklet $booklet, EntityManagerInterface $em): Response
    {
        if ($booklet->getBilan()) {
            return $this->redirectToRoute('app_bilan_edit', ['id' => $booklet->getBilan()->getId()]);
        }

        $bilan = new Bilan();
        $bilan->setBooklet($booklet);
        $form = $this->createForm(BilanType::class, $bilan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bilan->setCreatedAt(new \DateTime());
            $em->persist($bilan);
            $em->flush();
            $this->addFlash('success', 'Bilan créé.');
            return $this->redirectToRoute('app_booklet_show', ['id' => $booklet->getId()]);
        }

        return $this->render('bilan/new.html.twig', [
            'form' => $form,
            'booklet' => $booklet,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_bilan_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Bilan $bilan, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(BilanType::class, $bilan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Bilan modifié.');
            return $this->redirectToRoute('app_booklet_show', ['id' => $bilan->getBooklet()->getId()]);
        }

        return $this->render('bilan/edit.html.twig', [
            'form' => $form,
            'bilan' => $bilan,
        ]);
    }
}