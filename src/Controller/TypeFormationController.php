<?php

namespace App\Controller;

use App\Entity\TypeFormation;
use App\Form\TypeFormationType;
use App\Repository\TypeFormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/type-formation')]
#[IsGranted('ROLE_FORMATEUR')]
class TypeFormationController extends AbstractController
{
    #[Route('/', name: 'app_type_formation_index', methods: ['GET'])]
    public function index(TypeFormationRepository $repo): Response
    {
        return $this->render('type_formation/index.html.twig', [
            'type_formations' => $repo->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_type_formation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $typeFormation = new TypeFormation();
        $form = $this->createForm(TypeFormationType::class, $typeFormation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($typeFormation);
            $em->flush();
            $this->addFlash('success', 'Titre professionnel créé.');
            return $this->redirectToRoute('app_type_formation_index');
        }

        return $this->render('type_formation/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_type_formation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TypeFormation $typeFormation, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TypeFormationType::class, $typeFormation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Titre professionnel modifié.');
            return $this->redirectToRoute('app_type_formation_index');
        }

        return $this->render('type_formation/edit.html.twig', [
            'type_formation' => $typeFormation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_formation_delete', methods: ['POST'])]
    public function delete(Request $request, TypeFormation $typeFormation, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeFormation->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($typeFormation);
            $em->flush();
            $this->addFlash('success', 'Titre professionnel supprimé.');
        }
        return $this->redirectToRoute('app_type_formation_index');
    }
}