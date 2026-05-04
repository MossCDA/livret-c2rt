<?php

namespace App\DataFixtures;

use App\Entity\ActiviteType;
use App\Entity\BehaviorAssessment;
use App\Entity\Booklet;
use App\Entity\CompanyProgress;
use App\Entity\CompanyVisit;
use App\Entity\Formation;
use App\Entity\Skill;
use App\Entity\SkillAssessment;
use App\Entity\TypeFormation;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $manager): void
    {
        // --- USERS ---
        $formateur = new User();
        $formateur->setEmail('formateur@c2rt.fr');
        $formateur->setFirstName('Jean');
        $formateur->setLastName('Formateur');
        $formateur->setRoles(['ROLE_FORMATEUR']);
        $formateur->setPassword($this->hasher->hashPassword($formateur, 'Admin1234'));
        $manager->persist($formateur);

        $apprenant = new User();
        $apprenant->setEmail('apprenant@c2rt.fr');
        $apprenant->setFirstName('Marie');
        $apprenant->setLastName('Apprenant');
        $apprenant->setRoles(['ROLE_APPRENANT']);
        $apprenant->setPassword($this->hasher->hashPassword($apprenant, 'Admin1234'));
        $manager->persist($apprenant);

        // --- TYPE FORMATION ---
        $typeCDA = new TypeFormation();
        $typeCDA->setName('Concepteur Developpeur d\'Applications');
        $manager->persist($typeCDA);

        // --- FORMATION ---
        $formation = new Formation();
        $formation->setName('CDA 2026');
        $formation->setBeginAt(new \DateTime('2026-02-09'));
        $formation->setEndAt(new \DateTime('2026-12-18'));
        $formation->setTimeCenter(1127);
        $formation->setTimeStage(315);
        $formation->setTypeFormation($typeCDA);
        $manager->persist($formation);

        // --- ACTIVITE TYPES (3 CCP du CDA) ---
        $ccp1 = new ActiviteType();
        $ccp1->setName('CCP1 - Developper une application securisee');
        $ccp1->setTypeFormation($typeCDA);
        $manager->persist($ccp1);

        $ccp2 = new ActiviteType();
        $ccp2->setName('CCP2 - Concevoir et developper une application securisee organisee en couches');
        $ccp2->setTypeFormation($typeCDA);
        $manager->persist($ccp2);

        $ccp3 = new ActiviteType();
        $ccp3->setName('CCP3 - Preparer le deploiement d\'une application securisee');
        $ccp3->setTypeFormation($typeCDA);
        $manager->persist($ccp3);

        // --- SKILLS CDA (11 competences du livret) ---
        $skillsData = [
            ['Installer et configurer son environnement de travail', $ccp1],
            ['Developper des interfaces utilisateurs', $ccp1],
            ['Developper des composants metiers', $ccp1],
            ['Contribuer a la gestion d\'un projet informatique', $ccp1],
            ['Analyser les besoins et maquetter une application', $ccp2],
            ['Definir l\'architecture logicielle d\'une application', $ccp2],
            ['Concevoir et mettre en place une base de donnees relationnelle', $ccp2],
            ['Developper des composants d\'acces aux donnees SQL et NoSQL', $ccp2],
            ['Preparer et executer les plans de tests d\'une application', $ccp3],
            ['Preparer et documenter le deploiement d\'une application', $ccp3],
            ['Contribuer a la mise en production dans une demarche DevOps', $ccp3],
        ];

        $skills = [];
        foreach ($skillsData as [$name, $activiteType]) {
            $skill = new Skill();
            $skill->setName($name);
            $skill->setActiviteType($activiteType);
            $manager->persist($skill);
            $skills[] = $skill;
        }

        // --- BOOKLET demo (semaine 1) ---
        $booklet = new Booklet();
        $booklet->setUser($apprenant);
        $booklet->setFormation($formation);
        $booklet->setWeekNumber(1);
        $booklet->setWeekStart(new \DateTime('2026-02-09'));
        $booklet->setWeekContent('Installation de l\'environnement de developpement : PHP, Symfony, Git, VSCode. Premier projet Symfony Hello World.');
        $booklet->setValidated(false);
        $manager->persist($booklet);

        // --- SKILL ASSESSMENTS demo ---
        $statuses = ['A', 'A', 'NA', 'AT', 'NA', 'NA', 'NA', 'NA', 'NA', 'NA', 'NA'];
        foreach ($skills as $i => $skill) {
            $assessment = new SkillAssessment();
            $assessment->setBooklet($booklet);
            $assessment->setSkill($skill);
            $assessment->setStatus($statuses[$i]);
            $assessment->setComment($i === 0 ? 'Environnement configure avec succes' : null);
            $manager->persist($assessment);
        }

        // --- COMPANY PROGRESS demo ---
        $progress = new CompanyProgress();
        $progress->setBooklet($booklet);
        $progress->setDate(new \DateTime('2026-02-10'));
        $progress->setObservations('Prise en main du projet Symfony existant. Lecture du code source.');
        $manager->persist($progress);

        // --- BEHAVIOR ASSESSMENT demo (savoir-etre milieu - formateur) ---
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

        foreach ($criterias as $criteria) {
            $behavior = new BehaviorAssessment();
            $behavior->setBooklet($booklet);
            $behavior->setPeriod('mid');
            $behavior->setAssessedBy('trainer');
            $behavior->setCriteria($criteria);
            $behavior->setRating('satisfactory');
            $manager->persist($behavior);
        }

        $manager->flush();
    }
}
