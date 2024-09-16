<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231220211619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lesson_players (lesson_id INT NOT NULL, players_id INT NOT NULL, PRIMARY KEY(lesson_id, players_id))');
        $this->addSql('CREATE INDEX IDX_D3321101CDF80196 ON lesson_players (lesson_id)');
        $this->addSql('CREATE INDEX IDX_D3321101F1849495 ON lesson_players (players_id)');
        $this->addSql('ALTER TABLE lesson_players ADD CONSTRAINT FK_D3321101CDF80196 FOREIGN KEY (lesson_id) REFERENCES lesson (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lesson_players ADD CONSTRAINT FK_D3321101F1849495 FOREIGN KEY (players_id) REFERENCES players (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE lesson_players DROP CONSTRAINT FK_D3321101CDF80196');
        $this->addSql('ALTER TABLE lesson_players DROP CONSTRAINT FK_D3321101F1849495');
        $this->addSql('DROP TABLE lesson_players');
    }
}
