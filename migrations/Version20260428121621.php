<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260428121621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booklet ADD week_content LONGTEXT DEFAULT NULL, ADD validated TINYINT DEFAULT NULL, ADD datetime VARCHAR(255) NOT NULL, ADD validated_at DATETIME DEFAULT NULL, ADD week_number INT DEFAULT NULL, ADD week_start DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booklet DROP week_content, DROP validated, DROP datetime, DROP validated_at, DROP week_number, DROP week_start');
    }
}
