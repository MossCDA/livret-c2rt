<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260428131026 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ecf (id INT AUTO_INCREMENT NOT NULL, comment LONGTEXT DEFAULT NULL, grade VARCHAR(2) DEFAULT NULL, evaluated_at DATETIME DEFAULT NULL, no VARCHAR(255) NOT NULL, booklet_id INT NOT NULL, skill_id INT DEFAULT NULL, INDEX IDX_5B5C2CD0668144B3 (booklet_id), INDEX IDX_5B5C2CD05585C142 (skill_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE ecf ADD CONSTRAINT FK_5B5C2CD0668144B3 FOREIGN KEY (booklet_id) REFERENCES booklet (id)');
        $this->addSql('ALTER TABLE ecf ADD CONSTRAINT FK_5B5C2CD05585C142 FOREIGN KEY (skill_id) REFERENCES skill (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ecf DROP FOREIGN KEY FK_5B5C2CD0668144B3');
        $this->addSql('ALTER TABLE ecf DROP FOREIGN KEY FK_5B5C2CD05585C142');
        $this->addSql('DROP TABLE ecf');
    }
}
