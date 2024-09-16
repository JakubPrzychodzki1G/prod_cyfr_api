<?php

namespace App\DataFixtures;

use App\Entity\Activities;
use App\Entity\Players;
use App\Entity\Settings;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        for ($x = 0; $x <= 8; $x++) {
            $activity = new Activities();
            $activity->setTitle('Zapisy do sekcji sportowej');
            $activity->setText('Zapisy do klubu Posejdon konin juz sa dostepne!');
            $activity->setCreationDate(new \DateTime($x." minutes ago"));
            $activity->setIsDeleted(false);
            $activity->setTitleImage('https://images.pexels.com/photos/1263349/pexels-photo-1263349.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1');
            $activity->setDate(new \DateTime('now'));
            $activity->setCustomHref("/sport-section/records");
            $manager->persist($activity);

            $activity2 = new Activities();
            $activity2->setTitle('Zapisy do sekcji rekreacyjnej');
            $activity2->setText('Zapisy do klubu Posejdon konin juz sa dostepne!');
            $activity2->setCreationDate(new \DateTime($x." minutes ago"));
            $activity2->setIsDeleted(false);
            $activity2->setTitleImage('https://images.pexels.com/photos/6651598/pexels-photo-6651598.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1');
            $activity2->setDate(new \DateTime('now'));
            $activity2->setCustomHref("/recreation-section/records");
            $manager->persist($activity2);
        }
        $player = new Players();
        $player->setFirstName("Jan");
        $player->setLastName("Kowalski");
        $player->setBirthDate(new \DateTime("1999-01-01"));
        $player->setIsDeleted(false);
        $player->setSex(0);
        $player->setSchoolName("ZS nr 1");
        $player->setCity("Konin");
        $player->setStreetAndNumber("ul. Szkolna 1");
        $player->setZipCode("62-510");
        $player->setParentFirstName("Anna");
        $player->setParentLastName("Kowalska");
        $player->setParent2FirstName("Jan");
        $player->setParent2LastName("Kowalski");
        $player->setContactEmail("kowalscy@kowalscy.com");
        $player->setMainNumber("123456789");
        $player->setAdditionalNumber("987654321");
        $player->setPlayerNumber("123456444");
        $player->setIsVerified(true);
        $player->setCreationDate(new \DateTime("now"));
        $player->setModificationDate(new \DateTime("now"));
        $player->setSkill(4);
        $player->setComments("Brak uwag");
        $player->setSection("sport-section");
        $manager->persist($player);
        $user = new User();
        $user->setEmail("admin@admin.com");
        $user->setPassword("$2y$13$9W5EeuljUAfBwbCy7E572eQFhHRXzdPAoATnde3PEa3elSszSxbhS");
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setIsVerified(true);
        $user->setUsername("admin");
        $user->setName("Admin");
        $user->setLastName("Admin");
        $user->setIsDeleted(false);
        $user->setIsCoach(true);
        $manager->persist($user);
        $user = new User();
        $user->setEmail("trener@trener.com");
        $user->setPassword("$2y$13$9W5EeuljUAfBwbCy7E572eQFhHRXzdPAoATnde3PEa3elSszSxbhS");
        $user->setRoles(["ROLE_COACH"]);
        $user->setIsVerified(true);
        $user->setUsername("trener");
        $user->setName("Trener");
        $user->setLastName("Trener");
        $user->setIsDeleted(false);
        $user->setIsCoach(true);
        $manager->persist($user);
        $user = new User();
        $user->setEmail("gracz@gracz.com");
        $user->setPassword("$2y$13$9W5EeuljUAfBwbCy7E572eQFhHRXzdPAoATnde3PEa3elSszSxbhS");
        $user->setRoles(["ROLE_PLAYER"]);
        $user->setIsVerified(true);
        $user->setUsername("gracz");
        $user->setName("Gracz");
        $user->setLastName("Gracz");
        $user->setIsDeleted(false);
        $user->setIsCoach(false);
        $user->setPlayer($player);
        $manager->persist($user);
        $settings = new Settings();
        $settings->setName("clubName");
        $settings->setValue(["value" => "Klub 4WiezeWHannowerze"]);
        $settings->setModificationDate(new \DateTime("now"));
        $settings->setDescription("Nazwa klubu");
        $settings->setIsEditable(true);
        $settings->setIsLogged(false);
        $settings->setIsPublic(true);
        $manager->persist($settings);
        $settings = new Settings();
        $settings->setName("clubLogo");
        $settings->setValue(["value" => "https://www.w3schools.com/howto/img_avatar.png"]);
        $settings->setModificationDate(new \DateTime("now"));
        $settings->setDescription("logo");
        $settings->setIsEditable(true);
        $settings->setIsLogged(false);
        $settings->setIsPublic(true);
        $manager->persist($settings);
        $settings = new Settings();
        $settings->setName("heroTitle");
        $settings->setValue(["value" => "HeroTitle"]);
        $settings->setModificationDate(new \DateTime("now"));
        $settings->setDescription("naglowek w hero section");
        $settings->setIsEditable(true);
        $settings->setIsLogged(false);
        $settings->setIsPublic(true);
        $manager->persist($settings);
        $settings = new Settings();
        $settings->setName("heroDescription");
        $settings->setValue(["value" => "heroDescription"]);
        $settings->setModificationDate(new \DateTime("now"));
        $settings->setDescription("opis w hero section");
        $settings->setIsEditable(true);
        $settings->setIsLogged(false);
        $settings->setIsPublic(true);
        $manager->persist($settings);
        $settings = new Settings();
        $settings->setName("aboutUsTitlte");
        $settings->setValue(["value" => "aboutUsTitlte"]);
        $settings->setModificationDate(new \DateTime("now"));
        $settings->setDescription("nagłówek w o nas");
        $settings->setIsEditable(true);
        $settings->setIsLogged(false);
        $settings->setIsPublic(true);
        $manager->persist($settings);
        $settings = new Settings();
        $settings->setName("opinions");
        $settings->setValue(["value" => [
            ["username" => "Jan Kowalski", "userTitle" => "Trener", "userImage" => "https://www.w3schools.com/howto/img_avatar.png", "opinion" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec euismod, nisl quis ultricies ultricies, nunc nisl ultricies nunc, quis ultricies nisl nisl quis nisl. Aliquam erat volutpat. Sed euismod, nisl quis ultricies ultricies, nunc nisl ultricies nunc, quis ultricies nisl nisl quis nisl. Aliquam erat volutpat."],
            ["username" => "Jan Kowalski", "userTitle" => "Trener", "userImage" => "https://www.w3schools.com/howto/img_avatar.png", "opinion" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec euismod, nisl quis ultricies ultricies, nunc nisl ultricies nunc, quis ultricies nisl nisl quis nisl. Aliquam erat volutpat. Sed euismod, nisl quis ultricies ultricies, nunc nisl ultricies nunc, quis ultricies nisl nisl quis nisl. Aliquam erat volutpat."],
            ["username" => "Jan Kowalski", "userTitle" => "Trener", "userImage" => "https://www.w3schools.com/howto/img_avatar.png", "opinion" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec euismod, nisl quis ultricies ultricies, nunc nisl ultricies nunc, quis ultricies nisl nisl quis nisl. Aliquam erat volutpat. Sed euismod, nisl quis ultricies ultricies, nunc nisl ultricies nunc, quis ultricies nisl nisl quis nisl. Aliquam erat volutpat."]
        ]]);
        $settings->setModificationDate(new \DateTime("now"));
        $settings->setDescription("opinie");
        $settings->setIsEditable(true);
        $settings->setIsLogged(false);
        $settings->setIsPublic(true);
        $manager->persist($settings);
        $settings = new Settings();
        $settings->setName("questions");
        $settings->setValue(["value" => [
            ["question" => "Pytanie 1 Opinia o klubie", "answer" => "Odpowiedz 1Opinia o klubie "],
            ["question" => "Opinia o klubie Pytanie 2", "answer" => "Odpowiedz 2Opinia o klubie "],
            ["question" => "Pytanie", "answer" => "Odpowiedz"],
            ["question" => "Pytanie 4", "answer" => "Odpowiedz 4"]
        ]]);
        $settings->setModificationDate(new \DateTime("now"));
        $settings->setDescription("pytania");
        $settings->setIsEditable(true);
        $settings->setIsLogged(false);
        $settings->setIsPublic(true);
        $manager->persist($settings);
        $settings = new Settings();
        $settings->setName("facebookLink");
        $settings->setValue(["value" => "facebookLink"]);
        $settings->setModificationDate(new \DateTime("now"));
        $settings->setDescription("link do facebooka");
        $settings->setIsEditable(true);
        $settings->setIsLogged(false);
        $settings->setIsPublic(true);
        $manager->persist($settings);
        $settings = new Settings();
        $settings->setName("instagramLink");
        $settings->setValue(["value" => "instagramLink"]);
        $settings->setModificationDate(new \DateTime("now"));
        $settings->setDescription("link do instagrama");
        $settings->setIsEditable(true);
        $settings->setIsLogged(false);
        $settings->setIsPublic(true);
        $manager->persist($settings);
        $settings = new Settings();
        $settings->setName("tweeterLink");
        $settings->setValue(["value" => "tweeterLink"]);
        $settings->setModificationDate(new \DateTime("now"));
        $settings->setDescription("link do twittera");
        $settings->setIsEditable(true);
        $settings->setIsLogged(false);
        $settings->setIsPublic(true);
        $manager->persist($settings);
        $settings = new Settings();
        $settings->setName("linkedInLink");
        $settings->setValue(["value" => "linkedInLink"]);
        $settings->setModificationDate(new \DateTime("now"));
        $settings->setDescription("link do linkedIn-a");
        $settings->setIsEditable(true);
        $settings->setIsLogged(false);
        $settings->setIsPublic(true);
        $manager->persist($settings);
        $settings = new Settings();
        $settings->setName("colors");
        $settings->setValue(["value" => ["colorBase" => ["value" => "#ff0000"], "colorSecondary" => ["value" => "#09ff00"], "colorAdditional" => ["value" => "#2e18f7"]]]);
        $settings->setModificationDate(new \DateTime("now"));
        $settings->setDescription("kolory systemu");
        $settings->setIsEditable(true);
        $settings->setIsLogged(false);
        $settings->setIsPublic(true);
        $manager->persist($settings);
        $manager->flush();
    }
}
