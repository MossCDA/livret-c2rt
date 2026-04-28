<?php

namespace App\Service;

use App\Entity\Booklet;
use App\Entity\User;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BookletExportService
{
    public function exportUserBooklet(User $user, array $booklets): StreamedResponse
    {
        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(11);

        $section = $phpWord->addSection();

        // Titre
        $section->addText(
            'LIVRET DE SUIVI — ' . strtoupper($user->getFirstName() . ' ' . $user->getLastName()),
            ['bold' => true, 'size' => 16],
            ['alignment' => Jc::CENTER]
        );

        $formation = $user->getFormation();
        if ($formation) {
            $section->addText(
                'Formation : ' . $formation->getName(),
                ['size' => 12],
                ['alignment' => Jc::CENTER]
            );
        }

        $section->addTextBreak(2);

        foreach ($booklets as $booklet) {
            // En-tête semaine
            $section->addText(
                'Semaine ' . $booklet->getWeekNumber() . ' — ' . ($booklet->getWeekStart() ? $booklet->getWeekStart()->format('d/m/Y') : ''),
                ['bold' => true, 'size' => 13]
            );

            $section->addText(
                'Statut : ' . ($booklet->isValidated() ? 'Validé' : 'En attente'),
                ['italic' => true, 'size' => 10]
            );

            $section->addTextBreak(1);

            $section->addText('Activités réalisées :', ['bold' => true]);
            $section->addText($booklet->getWeekContent() ?? '-');

            $section->addTextBreak(1);
            $section->addLine(['weight' => 1, 'color' => 'AAAAAA', 'width' => 400]);
            $section->addTextBreak(1);
        }

        $filename = 'livret_' . $user->getLastName() . '_' . $user->getFirstName() . '.docx';

        $response = new StreamedResponse(function () use ($phpWord) {
            $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }
}