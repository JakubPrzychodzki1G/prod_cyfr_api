<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231015135752 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE swim_group_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE players_swim_group (players_id INT NOT NULL, swim_group_id INT NOT NULL, PRIMARY KEY(players_id, swim_group_id))');
        $this->addSql('CREATE INDEX IDX_3F8EE82AF1849495 ON players_swim_group (players_id)');
        $this->addSql('CREATE INDEX IDX_3F8EE82A61859147 ON players_swim_group (swim_group_id)');
        $this->addSql('CREATE TABLE swim_group (id INT NOT NULL, coach_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_19C967BE3C105691 ON swim_group (coach_id)');
        $this->addSql('ALTER TABLE players_swim_group ADD CONSTRAINT FK_3F8EE82AF1849495 FOREIGN KEY (players_id) REFERENCES players (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE players_swim_group ADD CONSTRAINT FK_3F8EE82A61859147 FOREIGN KEY (swim_group_id) REFERENCES swim_group (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE swim_group ADD CONSTRAINT FK_19C967BE3C105691 FOREIGN KEY (coach_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE swim_group_id_seq CASCADE');
        $this->addSql('ALTER TABLE players_swim_group DROP CONSTRAINT FK_3F8EE82AF1849495');
        $this->addSql('ALTER TABLE players_swim_group DROP CONSTRAINT FK_3F8EE82A61859147');
        $this->addSql('ALTER TABLE swim_group DROP CONSTRAINT FK_19C967BE3C105691');
        $this->addSql('DROP TABLE players_swim_group');
        $this->addSql('DROP TABLE swim_group');
    }
}
