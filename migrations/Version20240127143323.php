<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240127143323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE grade_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE grade (id INT NOT NULL, player_id INT NOT NULL, added_by_id INT NOT NULL, value VARCHAR(255) NOT NULL, wage DOUBLE PRECISION DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, create_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, mod_time TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, is_deleted BOOLEAN NOT NULL, is_archived BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_595AAE3499E6F5DF ON grade (player_id)');
        $this->addSql('CREATE INDEX IDX_595AAE3455B127A4 ON grade (added_by_id)');
        $this->addSql('ALTER TABLE grade ADD CONSTRAINT FK_595AAE3499E6F5DF FOREIGN KEY (player_id) REFERENCES players (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE grade ADD CONSTRAINT FK_595AAE3455B127A4 FOREIGN KEY (added_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE grade_id_seq CASCADE');
        $this->addSql('ALTER TABLE grade DROP CONSTRAINT FK_595AAE3499E6F5DF');
        $this->addSql('ALTER TABLE grade DROP CONSTRAINT FK_595AAE3455B127A4');
        $this->addSql('DROP TABLE grade');
    }
}
