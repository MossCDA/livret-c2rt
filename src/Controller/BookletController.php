<?php

namespace App\Controller;

use App\Entity\Booklet;
use App\Entity\BehaviorAssessment;
use App\Entity\CompanyProgress;
use App\Entity\CompanyVisit;
use App\Entity\SkillAssessment;
use App\Entity\User;
use App\Form\BookletType;
use App\Repository\BookletRepository;
use App\Repository\SkillRepository;
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
            $this->addFlash('success', 'Entree ajoutee au livret.');
            return $this->redirectToRoute('app_booklet_index');
        }

        return $this->render('booklet/new.html.twig', ['form' => $form]);
    }

    #[Route('/{id}/livret', name: 'app_booklet_livret', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_FORMATEUR')]
    public function livret(
        Request $request,
        Booklet $booklet,
        EntityManagerInterface $em,
        SkillRepository $skillRepo,
        BookletRepository $bookletRepo
    ): Response {
        $stagiaire = $booklet->getUser();
        $formation = $booklet->getFormation();

        // Toutes les semaines du stagiaire
        $allWeeks = $bookletRepo->findBy(
            ['user' => $stagiaire, 'formation' => $formation],
            ['weekNumber' => 'ASC']
        );

        // Toutes les competences CDA
        $skills = $skillRepo->findAll();

        // Traitement POST : sauvegarde des evaluations
        if ($request->isMethod('POST')) {
            $data = $request->request->all();

            foreach ($allWeeks as $week) {
                // Contenu semaine
                if (isset($data['week_content'][$week->getId()])) {
                    $week->setWeekContent($data['week_content'][$week->getId()]);
                }

                // Skill assessments
                if (isset($data['skill'][$week->getId()])) {
                    foreach ($data['skill'][$week->getId()] as $skillId => $skillData) {
                        $skill = $skillRepo->find($skillId);
                        if (!$skill) continue;

                        // Chercher assessment existant
                        $assessment = null;
                        foreach ($week->getSkillAssessments() as $existing) {
                            if ($existing->getSkill()->getId() === $skill->getId()) {
                                $assessment = $existing;
                                break;
                            }
                        }

                        if (!$assessment) {
                            $assessment = new SkillAssessment();
                            $assessment->setBooklet($week);
                            $assessment->setSkill($skill);
                            $em->persist($assessment);
                        }

                        $assessment->setStatus($skillData['status'] ?? 'NA');
                        $assessment->setComment($skillData['comment'] ?? null);
                    }
                }

                // Company progress
                if (isset($data['company_progress'][$week->getId()])) {
                    $progressData = $data['company_progress'][$week->getId()];
                    $progress = $week->getCompanyProgress()->first() ?: null;

                    if (!$progress && !empty($progressData['observations'])) {
                        $progress = new CompanyProgress();
                        $progress->setBooklet($week);
                        $em->persist($progress);
                    }

                    if ($progress) {
                        $progress->setObservations($progressData['observations'] ?? null);
                        if (!empty($progressData['date'])) {
                            $progress->setDate(new \DateTime($progressData['date']));
                        }
                    }
                }
            }

            // Company visit
            if (isset($data['visit'])) {
                $visitData = $data['visit'];
                $visit = $booklet->getCompanyVisits()->first() ?: null;

                if (!$visit) {
                    $visit = new CompanyVisit();
                    $visit->setBooklet($booklet);
                    $em->persist($visit);
                }

                $visit->setTrainerName($visitData['trainerName'] ?? null);
                $visit->setStudentComments($visitData['studentComments'] ?? null);
                $visit->setTutorComments($visitData['tutorComments'] ?? null);
                $visit->setTrainerComments($visitData['trainerComments'] ?? null);
                if (!empty($visitData['visitDate'])) {
                    $visit->setVisitDate(new \DateTime($visitData['visitDate']));
                }
            }

            // Behavior assessments
            $criterias = [
                'Respecter les horaires et la duree du travail',
                'Etre assidu',
                'Se presenter dans une tenue vestimentaire compatible',
                'S\'integrer a l\'equipe',
                'Respect des consignes de securite',
                'Rendre compte de son travail',
                'Faire preuve de motivation',
                'A un comportement agreable',
                'Sait accepter la hierarchie',
                'Fait des efforts pour surmonter les difficultes',
                'Faire preuve de maitrise et de calme',
                'Agir de facon organisee et methodique',
                'Respecter les regles et consignes de travail',
                'Respecter le materiel et l\'environnement technique',
            ];

            foreach (['mid', 'end'] as $period) {
                foreach (['student', 'trainer'] as $assessedBy) {
                    foreach ($criterias as $criteria) {
                        $key = $period . '_' . $assessedBy . '_' . md5($criteria);
                        $rating = $data['behavior'][$key] ?? null;

                        // Chercher assessment existant
                        $behavior = null;
                        foreach ($booklet->getBehaviorAssessments() as $existing) {
                            if ($existing->getPeriod() === $period
                                && $existing->getAssessedBy() === $assessedBy
                                && $existing->getCriteria() === $criteria) {
                                $behavior = $existing;
                                break;
                            }
                        }

                        if (!$behavior && $rating) {
                            $behavior = new BehaviorAssessment();
                            $behavior->setBooklet($booklet);
                            $behavior->setPeriod($period);
                            $behavior->setAssessedBy($assessedBy);
                            $behavior->setCriteria($criteria);
                            $em->persist($behavior);
                        }

                        if ($behavior) {
                            $behavior->setRating($rating);
                        }
                    }
                }
            }

            $em->flush();
            $this->addFlash('success', 'Livret sauvegarde.');
            return $this->redirectToRoute('app_booklet_livret', ['id' => $booklet->getId()]);
        }

        return $this->render('booklet/livret.html.twig', [
            'booklet'   => $booklet,
            'stagiaire' => $stagiaire,
            'formation' => $formation,
            'allWeeks'  => $allWeeks,
            'skills'    => $skills,
        ]);
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
            $this->addFlash('error', 'Ce livret a ete valide, il ne peut plus etre modifie.');
            return $this->redirectToRoute('app_booklet_index');
        }

        $form = $this->createForm(BookletType::class, $booklet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Entree modifiee.');
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
        $this->addFlash('success', 'Livret valide.');
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
            $this->addFlash('success', 'Entree supprimee.');
        }

        return $this->redirectToRoute('app_booklet_index');
    }
}
