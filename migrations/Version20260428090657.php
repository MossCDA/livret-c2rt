<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260428090657 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activite_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type_formation_id INT NOT NULL, INDEX IDX_FB0E4E5BD543922B (type_formation_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE booklet (id INT AUTO_INCREMENT NOT NULL, storage TINYINT DEFAULT NULL, formation_id INT NOT NULL, user_id INT NOT NULL, skill_id INT NOT NULL, INDEX IDX_818DB7205200282E (formation_id), INDEX IDX_818DB720A76ED395 (user_id), INDEX IDX_818DB7205585C142 (skill_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, begin_at DATETIME NOT NULL, end_at DATETIME NOT NULL, begin_stage_at DATETIME DEFAULT NULL, end_stage_at DATETIME DEFAULT NULL, storage TINYINT DEFAULT NULL, time_center INT DEFAULT NULL, time_stage INT DEFAULT NULL, type_formation_id INT NOT NULL, INDEX IDX_404021BFD543922B (type_formation_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE grade (id INT AUTO_INCREMENT NOT NULL, grade VARCHAR(2) DEFAULT NULL, comment LONGTEXT DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE skill (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, activite_type_id INT NOT NULL, INDEX IDX_5E3DE4778FEA4B16 (activite_type_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE slot (id INT AUTO_INCREMENT NOT NULL, begin_at DATETIME NOT NULL, end_at DATETIME NOT NULL, content LONGTEXT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, user_id INT NOT NULL, INDEX IDX_AC0E2067A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE type_formation (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, detail VARCHAR(255) DEFAULT NULL, code VARCHAR(10) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE vacancy (id INT AUTO_INCREMENT NOT NULL, begin_at DATETIME NOT NULL, end_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE activite_type ADD CONSTRAINT FK_FB0E4E5BD543922B FOREIGN KEY (type_formation_id) REFERENCES type_formation (id)');
        $this->addSql('ALTER TABLE booklet ADD CONSTRAINT FK_818DB7205200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
        $this->addSql('ALTER TABLE booklet ADD CONSTRAINT FK_818DB720A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE booklet ADD CONSTRAINT FK_818DB7205585C142 FOREIGN KEY (skill_id) REFERENCES skill (id)');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BFD543922B FOREIGN KEY (type_formation_id) REFERENCES type_formation (id)');
        $this->addSql('ALTER TABLE skill ADD CONSTRAINT FK_5E3DE4778FEA4B16 FOREIGN KEY (activite_type_id) REFERENCES activite_type (id)');
        $this->addSql('ALTER TABLE slot ADD CONSTRAINT FK_AC0E2067A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activite_type DROP FOREIGN KEY FK_FB0E4E5BD543922B');
        $this->addSql('ALTER TABLE booklet DROP FOREIGN KEY FK_818DB7205200282E');
        $this->addSql('ALTER TABLE booklet DROP FOREIGN KEY FK_818DB720A76ED395');
        $this->addSql('ALTER TABLE booklet DROP FOREIGN KEY FK_818DB7205585C142');
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BFD543922B');
        $this->addSql('ALTER TABLE skill DROP FOREIGN KEY FK_5E3DE4778FEA4B16');
        $this->addSql('ALTER TABLE slot DROP FOREIGN KEY FK_AC0E2067A76ED395');
        $this->addSql('DROP TABLE activite_type');
        $this->addSql('DROP TABLE booklet');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE grade');
        $this->addSql('DROP TABLE skill');
        $this->addSql('DROP TABLE slot');
        $this->addSql('DROP TABLE type_formation');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE vacancy');
    }
}
