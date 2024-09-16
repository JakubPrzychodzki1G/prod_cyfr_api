<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240729180958 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE media_object_activities (media_object_id INT NOT NULL, activities_id INT NOT NULL, PRIMARY KEY(media_object_id, activities_id))');
        $this->addSql('CREATE INDEX IDX_90976BA264DE5A5 ON media_object_activities (media_object_id)');
        $this->addSql('CREATE INDEX IDX_90976BA22A4DB562 ON media_object_activities (activities_id)');
        $this->addSql('ALTER TABLE media_object_activities ADD CONSTRAINT FK_90976BA264DE5A5 FOREIGN KEY (media_object_id) REFERENCES media_object (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE media_object_activities ADD CONSTRAINT FK_90976BA22A4DB562 FOREIGN KEY (activities_id) REFERENCES activities (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE media_object DROP CONSTRAINT fk_14d431322a4db562');
        $this->addSql('DROP INDEX idx_14d431322a4db562');
        $this->addSql('ALTER TABLE media_object DROP activities_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE media_object_activities DROP CONSTRAINT FK_90976BA264DE5A5');
        $this->addSql('ALTER TABLE media_object_activities DROP CONSTRAINT FK_90976BA22A4DB562');
        $this->addSql('DROP TABLE media_object_activities');
        $this->addSql('ALTER TABLE media_object ADD activities_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media_object ADD CONSTRAINT fk_14d431322a4db562 FOREIGN KEY (activities_id) REFERENCES activities (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_14d431322a4db562 ON media_object (activities_id)');
    }
}
