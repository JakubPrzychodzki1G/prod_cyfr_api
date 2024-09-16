<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230821190403 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE activities_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE players_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE settings_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE activities (id INT NOT NULL, custom_href VARCHAR(255) DEFAULT NULL, title VARCHAR(80) NOT NULL, text TEXT DEFAULT NULL, creation_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, modification_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, title_image VARCHAR(255) DEFAULT NULL, is_deleted BOOLEAN NOT NULL, delete_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE players (id INT NOT NULL, first_name VARCHAR(100) DEFAULT NULL, last_name VARCHAR(100) DEFAULT NULL, sex INT DEFAULT NULL, birth_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, school_name VARCHAR(255) DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, street_and_number VARCHAR(100) DEFAULT NULL, zip_code VARCHAR(25) DEFAULT NULL, parent_first_name VARCHAR(100) DEFAULT NULL, parent_last_name VARCHAR(100) DEFAULT NULL, parent2_first_name VARCHAR(100) DEFAULT NULL, parent2_last_name VARCHAR(100) DEFAULT NULL, contact_email VARCHAR(255) DEFAULT NULL, main_number VARCHAR(25) DEFAULT NULL, additional_number VARCHAR(25) DEFAULT NULL, player_number VARCHAR(25) DEFAULT NULL, is_deleted BOOLEAN NOT NULL, delete_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, creation_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, modification_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, is_verified BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE settings (id INT NOT NULL, name VARCHAR(255) NOT NULL, value VARCHAR(255) DEFAULT NULL, modification_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, is_editable BOOLEAN NOT NULL, is_logged BOOLEAN NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE activities_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE players_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE settings_id_seq CASCADE');
        $this->addSql('DROP TABLE activities');
        $this->addSql('DROP TABLE players');
        $this->addSql('DROP TABLE settings');
    }
}
