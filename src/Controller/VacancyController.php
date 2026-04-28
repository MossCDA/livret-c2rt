<?php

namespace App\Controller;

use App\Entity\Vacancy;
use App\Form\VacancyType;
use App\Repository\VacancyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/vacancy')]
#[IsGranted('ROLE_FORMATEUR')]
class VacancyController extends AbstractController
{
    #[Route('/', name: 'app_vacancy_index', methods: ['GET'])]
    public function index(VacancyRepository $repo): Response
    {
        return $this->render('vacancy/index.html.twig', [
            'vacancies' => $repo->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_vacancy_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $vacancy = new Vacancy();
        $form = $this->createForm(VacancyType::class, $vacancy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($vacancy);
            $em->flush();
            $this->addFlash('success', 'Période de vacances créée.');
            return $this->redirectToRoute('app_vacancy_index');
        }

        return $this->render('vacancy/new.html.twig', ['form' => $form]);
    }

    #[Route('/{id}/edit', name: 'app_vacancy_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vacancy $vacancy, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(VacancyType::class, $vacancy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Période modifiée.');
            return $this->redirectToRoute('app_vacancy_index');
        }

        return $this->render('vacancy/edit.html.twig', ['vacancy' => $vacancy, 'form' => $form]);
    }

    #[Route('/{id}', name: 'app_vacancy_delete', methods: ['POST'])]
    public function delete(Request $request, Vacancy $vacancy, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vacancy->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($vacancy);
            $em->flush();
            $this->addFlash('success', 'Période supprimée.');
        }
        return $this->redirectToRoute('app_vacancy_index');
    }
}