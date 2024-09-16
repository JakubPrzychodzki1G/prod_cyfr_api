<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240126112307 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE settings DROP long_value');
        $this->addSql('ALTER TABLE settings 
                                ALTER COLUMN value DROP DEFAULT, 
                                ALTER COLUMN value TYPE JSON USING value::JSON
                                ');
        $this->addSql("INSERT INTO 
                        settings 
                        VALUES (
                            1, 'clubName', '{\"value\": \"Klub 4WiezeWHannowerze\"}'::json, now(), 'Nazwa klubu', true, false, true)
                        ");
        $this->addSql("INSERT INTO 
                        settings 
                        VALUES (
                            2, 'clubLogo', '{\"value\": \"https://www.w3schools.com/howto/img_avatar.png\"}'::json, now(), 'logo', true, false, true)
                        ");
        $this->addSql("INSERT INTO 
                        settings 
                        VALUES (
                            3, 'heroTitle', '{\"value\": \"HeroTitle\"}'::json, now(), 'naglowek w hero section', true, false, true)
                        ");
        $this->addSql("INSERT INTO 
                        settings 
                        VALUES (
                            4, 'heroDescription', '{\"value\": \"heroDescription\"}'::json, now(), 'opis w hero section', true, false, true)
                        ");
        $this->addSql("INSERT INTO 
                        settings 
                        VALUES (
                            5, 'aboutUsTitlte', '{\"value\": \"aboutUsTitlte\"}'::json, now(), 'nagłówek w o nas', true, false, true)
                        ");
        $this->addSql("INSERT INTO 
                        settings 
                        VALUES (
                            6, 
                            'opinions',
                            '{\"value\": 
                                    [{
                                        \"username\": \"Jan Kowalski\", 
                                        \"userTitle\": \"Trener\",
                                        \"userImage\": \"https://www.w3schools.com/howto/img_avatar.png\",
                                        \"opinion\": \"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec euismod, nisl quis ultricies ultricies, nunc nisl ultricies nunc, quis ultricies nisl nisl quis nisl. Aliquam erat volutpat. Sed euismod, nisl quis ultricies ultricies, nunc nisl ultricies nunc, quis ultricies nisl nisl quis nisl. Aliquam erat volutpat.\"
                                    },
                                    {
                                        \"username\": \"Jan Kowalski\", 
                                        \"userTitle\": \"Trener\",
                                        \"userImage\": \"https://www.w3schools.com/howto/img_avatar.png\",
                                        \"opinion\": \"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec euismod, nisl quis ultricies ultricies, nunc nisl ultricies nunc, quis ultricies nisl nisl quis nisl. Aliquam erat volutpat. Sed euismod, nisl quis ultricies ultricies, nunc nisl ultricies nunc, quis ultricies nisl nisl quis nisl. Aliquam erat volutpat.\"
                                    },
                                    {
                                        \"username\": \"Jan Kowalski\", 
                                        \"userTitle\": \"Trener\",
                                        \"userImage\": \"https://www.w3schools.com/howto/img_avatar.png\",
                                        \"opinion\": \"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec euismod, nisl quis ultricies ultricies, nunc nisl ultricies nunc, quis ultricies nisl nisl quis nisl. Aliquam erat volutpat. Sed euismod, nisl quis ultricies ultricies, nunc nisl ultricies nunc, quis ultricies nisl nisl quis nisl. Aliquam erat volutpat.\"
                                    }
                                ]    
                            }'::json, 
                            now(), 
                            'opinie', 
                            true, 
                            false, 
                            true
                            )
                        ");
        $this->addSql("INSERT INTO 
                        settings 
                        VALUES (
                            7,
                            'questions',
                            '{\"value\": [
                                    {
                                        \"question\": \"Pytanie\", 
                                        \"answer\": \"Odpowiedz\"
                                    },
                                    {
                                        \"question\": \"Pytanie\", 
                                        \"answer\": \"Odpowiedz\"
                                    },
                                    {
                                        \"question\": \"Pytanie\", 
                                        \"answer\": \"Odpowiedz\"
                                    },
                                    {
                                        \"question\": \"Pytanie\", 
                                        \"answer\": \"Odpowiedz\"
                                    }
                                ] 
                            }'::json, 
                            now(), 
                            'pytania', 
                            true, 
                            false, 
                            true
                            )
                        ");
        $this->addSql("INSERT INTO 
                        settings 
                        VALUES (
                            8,
                            'colors',
                            '{ \"value\": {\"colorBase\": {\"value\": \"rgba(255, 255, 255, 1)\"},\"colorSecondary\": {\"value\": \"rgba(0, 0, 0, 1)\"},\"colorAdditional\": {\"value\": \"rgba(0, 0, 0, 1)\"}}}'::json, 
                            now(), 
                            'kolory systemu', 
                            true, 
                            false, 
                            true
                            )
                        ");
        $this->addSql("INSERT INTO 
                        settings 
                        VALUES (
                            9, 'facebookLink', '{\"value\": \"facebookLink\"}'::json, now(), 'link do facebooka', true, false, true)
                        ");
        $this->addSql("INSERT INTO 
                        settings 
                        VALUES (
                            10, 'instagramLink', '{\"value\": \"instagramLink\"}'::json, now(), 'link do instagrama', true, false, true)
                        ");
        $this->addSql("INSERT INTO 
                        settings 
                        VALUES (
                            11, 'tweeterLink', '{\"value\": \"tweeterLink\"}'::json, now(), 'link do twittera', true, false, true)
                        ");
        $this->addSql("INSERT INTO 
                        settings 
                        VALUES (
                            12, 'linkedInLink', '{\"value\": \"linkedInLink\"}'::json, now(), 'link do linkedIn-a', true, false, true)
                        ");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE settings ADD long_value TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE settings ALTER value TYPE VARCHAR(255)');
        $this->addSql("DELETE FROM settings WHERE name in ('clubName', 'clubLogo', 'heroTitle', 'heroDescription', 'aboutUsTitlte', 'opinions', 'questions', 'facebookLink', 'instagramLink', 'tweeterLink', 'linkedInLink')");
    }
}
