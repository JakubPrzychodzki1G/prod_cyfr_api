<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231028110109 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE attendance_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE lesson_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE attendance (id INT NOT NULL, player_id INT NOT NULL, lesson_id INT NOT NULL, is_present BOOLEAN NOT NULL, comments TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6DE30D9199E6F5DF ON attendance (player_id)');
        $this->addSql('CREATE INDEX IDX_6DE30D91CDF80196 ON attendance (lesson_id)');
        $this->addSql('CREATE TABLE lesson (id INT NOT NULL, coach_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, skill_level VARCHAR(255) DEFAULT NULL, pool VARCHAR(255) NOT NULL, equipment TEXT DEFAULT NULL, duration DOUBLE PRECISION NOT NULL, is_invidual BOOLEAN NOT NULL, age_group VARCHAR(255) DEFAULT NULL, objectives TEXT DEFAULT NULL, fees VARCHAR(255) DEFAULT NULL, start_date_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, comments TEXT DEFAULT NULL, is_deleted BOOLEAN DEFAULT NULL, delete_date_time TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, mod_date_time TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, creation_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F87474F33C105691 ON lesson (coach_id)');
        $this->addSql('ALTER TABLE attendance ADD CONSTRAINT FK_6DE30D9199E6F5DF FOREIGN KEY (player_id) REFERENCES players (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE attendance ADD CONSTRAINT FK_6DE30D91CDF80196 FOREIGN KEY (lesson_id) REFERENCES lesson (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lesson ADD CONSTRAINT FK_F87474F33C105691 FOREIGN KEY (coach_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE attendance_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE lesson_id_seq CASCADE');
        $this->addSql('ALTER TABLE attendance DROP CONSTRAINT FK_6DE30D9199E6F5DF');
        $this->addSql('ALTER TABLE attendance DROP CONSTRAINT FK_6DE30D91CDF80196');
        $this->addSql('ALTER TABLE lesson DROP CONSTRAINT FK_F87474F33C105691');
        $this->addSql('DROP TABLE attendance');
        $this->addSql('DROP TABLE lesson');
    }
}
