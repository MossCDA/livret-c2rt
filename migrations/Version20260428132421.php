<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260428132421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bilan (id INT AUTO_INCREMENT NOT NULL, content LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL, booklet_id INT NOT NULL, UNIQUE INDEX UNIQ_F4DF4F44668144B3 (booklet_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE bilan ADD CONSTRAINT FK_F4DF4F44668144B3 FOREIGN KEY (booklet_id) REFERENCES booklet (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bilan DROP FOREIGN KEY FK_F4DF4F44668144B3');
        $this->addSql('DROP TABLE bilan');
    }
}
