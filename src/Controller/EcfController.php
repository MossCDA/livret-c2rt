<?php

namespace App\Controller;

use App\Entity\Booklet;
use App\Entity\Ecf;
use App\Form\EcfType;
use App\Repository\EcfRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/ecf')]
#[IsGranted('ROLE_FORMATEUR')]
class EcfController extends AbstractController
{
    #[Route('/booklet/{id}/new', name: 'app_ecf_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Booklet $booklet, EntityManagerInterface $em): Response
    {
        $ecf = new Ecf();
        $ecf->setBooklet($booklet);
        $form = $this->createForm(EcfType::class, $ecf);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ecf->setEvaluatedAt(new \DateTime());
            $em->persist($ecf);
            $em->flush();
            $this->addFlash('success', 'ECF ajoutée.');
            return $this->redirectToRoute('app_booklet_show', ['id' => $booklet->getId()]);
        }

        return $this->render('ecf/new.html.twig', [
            'form' => $form,
            'booklet' => $booklet,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ecf_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ecf $ecf, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(EcfType::class, $ecf);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'ECF modifiée.');
            return $this->redirectToRoute('app_booklet_show', ['id' => $ecf->getBooklet()->getId()]);
        }

        return $this->render('ecf/edit.html.twig', [
            'form' => $form,
            'ecf' => $ecf,
        ]);
    }

    #[Route('/{id}', name: 'app_ecf_delete', methods: ['POST'])]
    public function delete(Request $request, Ecf $ecf, EntityManagerInterface $em): Response
    {
        $bookletId = $ecf->getBooklet()->getId();
        if ($this->isCsrfTokenValid('delete'.$ecf->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($ecf);
            $em->flush();
            $this->addFlash('success', 'ECF supprimée.');
        }
        return $this->redirectToRoute('app_booklet_show', ['id' => $bookletId]);
    }
}