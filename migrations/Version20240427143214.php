<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240427143214 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_object ADD activities_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media_object DROP content_url');
        $this->addSql('ALTER TABLE media_object ADD CONSTRAINT FK_14D431322A4DB562 FOREIGN KEY (activities_id) REFERENCES activities (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_14D431322A4DB562 ON media_object (activities_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE media_object DROP CONSTRAINT FK_14D431322A4DB562');
        $this->addSql('DROP INDEX IDX_14D431322A4DB562');
        $this->addSql('ALTER TABLE media_object ADD content_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE media_object DROP activities_id');
    }
}
