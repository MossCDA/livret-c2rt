<?php

namespace App\Controller;

use App\Entity\Booklet;
use App\Entity\User;
use App\Form\BookletType;
use App\Repository\BookletRepository;
use App\Service\BookletExportService;
use App\Service\BookletPdfExportService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/booklet')]
#[IsGranted('ROLE_USER')]
class BookletController extends AbstractController
{
    #[Route('/', name: 'app_booklet_index', methods: ['GET'])]
    public function index(BookletRepository $repo): Response
    {
        $user = $this->getUser();

        if ($this->isGranted('ROLE_FORMATEUR')) {
            $booklets = $repo->findAll();
        } else {
            $booklets = $repo->findBy(['user' => $user]);
        }

        return $this->render('booklet/index.html.twig', [
            'booklets' => $booklets,
        ]);
    }

    #[Route('/new', name: 'app_booklet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $booklet = new Booklet();
        $form = $this->createForm(BookletType::class, $booklet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $booklet->setUser($this->getUser());
            $booklet->setFormation($this->getUser()->getFormation());
            $booklet->setValidated(false);
            $em->persist($booklet);
            $em->flush();
            $this->addFlash('success', 'Entrée ajoutée au livret.');
            return $this->redirectToRoute('app_booklet_index');
        }

        return $this->render('booklet/new.html.twig', ['form' => $form]);
    }

    #[Route('/{id}', name: 'app_booklet_show', methods: ['GET'])]
    public function show(Booklet $booklet): Response
    {
        if (!$this->isGranted('ROLE_FORMATEUR') && $booklet->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('booklet/show.html.twig', ['booklet' => $booklet]);
    }

    #[Route('/{id}/edit', name: 'app_booklet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Booklet $booklet, EntityManagerInterface $em): Response
    {
        if ($booklet->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        if ($booklet->isValidated()) {
            $this->addFlash('error', 'Ce livret a été validé, il ne peut plus être modifié.');
            return $this->redirectToRoute('app_booklet_index');
        }

        $form = $this->createForm(BookletType::class, $booklet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Entrée modifiée.');
            return $this->redirectToRoute('app_booklet_index');
        }

        return $this->render('booklet/edit.html.twig', ['booklet' => $booklet, 'form' => $form]);
    }

    #[Route('/{id}/validate', name: 'app_booklet_validate', methods: ['POST'])]
    #[IsGranted('ROLE_FORMATEUR')]
    public function validate(Booklet $booklet, EntityManagerInterface $em): Response
    {
        $booklet->setValidated(true);
        $booklet->setValidatedAt(new \DateTime());
        $em->flush();
        $this->addFlash('success', 'Livret validé.');
        return $this->redirectToRoute('app_booklet_show', ['id' => $booklet->getId()]);
    }

    #[Route('/export/{id}', name: 'app_booklet_export', methods: ['GET'])]
    #[IsGranted('ROLE_FORMATEUR')]
    public function export(User $user, BookletRepository $repo, BookletExportService $exportService): Response
    {
        $booklets = $repo->findBy(['user' => $user], ['weekNumber' => 'ASC']);
        return $exportService->exportUserBooklet($user, $booklets);
    }

    #[Route('/export-pdf/{id}', name: 'app_booklet_export_pdf', methods: ['GET'])]
    #[IsGranted('ROLE_FORMATEUR')]
    public function exportPdf(User $user, BookletRepository $repo, BookletPdfExportService $pdfExportService): Response
    {
        $booklets = $repo->findBy(['user' => $user], ['weekNumber' => 'ASC']);
        return $pdfExportService->exportUserBooklet($user, $booklets);
    }

    #[Route('/{id}', name: 'app_booklet_delete', methods: ['POST'])]
    public function delete(Request $request, Booklet $booklet, EntityManagerInterface $em): Response
    {
        if ($booklet->getUser() !== $this->getUser() && !$this->isGranted('ROLE_FORMATEUR')) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete'.$booklet->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($booklet);
            $em->flush();
            $this->addFlash('success', 'Entrée supprimée.');
        }

        return $this->redirectToRoute('app_booklet_index');
    }
}