<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260504122656 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE behavior_assessment (id INT AUTO_INCREMENT NOT NULL, period VARCHAR(10) NOT NULL, assessed_by VARCHAR(10) NOT NULL, criteria VARCHAR(255) NOT NULL, rating VARCHAR(15) DEFAULT NULL, booklet_id INT NOT NULL, INDEX IDX_DC9CEA40668144B3 (booklet_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE company_progress (id INT AUTO_INCREMENT NOT NULL, date DATE DEFAULT NULL, observations LONGTEXT DEFAULT NULL, booklet_id INT NOT NULL, INDEX IDX_3A96E72B668144B3 (booklet_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE company_visit (id INT AUTO_INCREMENT NOT NULL, visit_date DATE DEFAULT NULL, trainer_name VARCHAR(255) DEFAULT NULL, student_comments LONGTEXT DEFAULT NULL, tutor_comments LONGTEXT DEFAULT NULL, trainer_comments LONGTEXT DEFAULT NULL, booklet_id INT NOT NULL, INDEX IDX_48F4B5C668144B3 (booklet_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE skill_assessment (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(3) DEFAULT NULL, comment LONGTEXT DEFAULT NULL, booklet_id INT NOT NULL, skill_id INT NOT NULL, INDEX IDX_E2DBE102668144B3 (booklet_id), INDEX IDX_E2DBE1025585C142 (skill_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE behavior_assessment ADD CONSTRAINT FK_DC9CEA40668144B3 FOREIGN KEY (booklet_id) REFERENCES booklet (id)');
        $this->addSql('ALTER TABLE company_progress ADD CONSTRAINT FK_3A96E72B668144B3 FOREIGN KEY (booklet_id) REFERENCES booklet (id)');
        $this->addSql('ALTER TABLE company_visit ADD CONSTRAINT FK_48F4B5C668144B3 FOREIGN KEY (booklet_id) REFERENCES booklet (id)');
        $this->addSql('ALTER TABLE skill_assessment ADD CONSTRAINT FK_E2DBE102668144B3 FOREIGN KEY (booklet_id) REFERENCES booklet (id)');
        $this->addSql('ALTER TABLE skill_assessment ADD CONSTRAINT FK_E2DBE1025585C142 FOREIGN KEY (skill_id) REFERENCES skill (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE behavior_assessment DROP FOREIGN KEY FK_DC9CEA40668144B3');
        $this->addSql('ALTER TABLE company_progress DROP FOREIGN KEY FK_3A96E72B668144B3');
        $this->addSql('ALTER TABLE company_visit DROP FOREIGN KEY FK_48F4B5C668144B3');
        $this->addSql('ALTER TABLE skill_assessment DROP FOREIGN KEY FK_E2DBE102668144B3');
        $this->addSql('ALTER TABLE skill_assessment DROP FOREIGN KEY FK_E2DBE1025585C142');
        $this->addSql('DROP TABLE behavior_assessment');
        $this->addSql('DROP TABLE company_progress');
        $this->addSql('DROP TABLE company_visit');
        $this->addSql('DROP TABLE skill_assessment');
    }
}
