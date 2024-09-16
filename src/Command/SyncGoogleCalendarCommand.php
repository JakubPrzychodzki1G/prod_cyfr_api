<?php
// src/Command/SyncGoogleCalendarCommand.php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Google\Client;
use Google\Service\Calendar;
use App\Repository\LessonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use App\Entity\Lesson;
use App\Repository\UserRepository;
use DateTime;

#[AsCommand(
    name: 'app:sync-google-calendar',
    description: 'Creates a new user.',
    hidden: false,
    aliases: ['app:sync-google-calendar']
)]
class SyncGoogleCalendarCommand extends Command
{
    protected static $defaultName = 'app:sync-google-calendar';

    private $lessonRepository;
    private $entityManager;
    private $userRepository;

    public function __construct(LessonRepository $lessonRepository, EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->lessonRepository = $lessonRepository;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Syncs Google Calendar with Lesson database table.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = new Client();
        $client->setApplicationName('Google Calendar API PHP Quickstart');
        $client->setScopes(Calendar::CALENDAR_READONLY);
        $client->setAuthConfig('poseidon-app.json');
        $client->setAccessType('offline');
        
        $service = new Calendar($client);
        $calendarId = 'klubposejdonkonin@gmail.com';
        $optParams = array(
          'maxResults' => 100,
          'singleEvents' => true,
          'timeMin' => date('c'),
        );
        $results = $service->events->listEvents($calendarId, $optParams);
        $events = $results->getItems();
        foreach ($events as $event) {
            $newLesson = new Lesson();
            $newLesson->setName($event->getSummary());
            $newLesson->setStartDateTime(new \DateTime($event->getStart()->getDateTime()));
            $newLesson->setEndDateTime(new \DateTime($event->getEnd()->getDateTime()));
            $newLesson->setPool($event->getLocation() ? $event->getLocation() : $event->getSummary());
            $newLesson->setDescription($event->getDescription());
            $newLesson->setCreationDate(new \DateTime());
            $newLesson->setIsDeleted(false);
            $newLesson->setModDateTime(new \DateTime());
            $newLesson->setDuration((strtotime($event->getEnd()->getDateTime()) - strtotime($event->getStart()->getDateTime()))/60);
            $newLesson->setCoach($this->userRepository->findOneBy(['email' => $event->getCreator()->getEmail()]));
            $newLesson->setIsInvidual(false);
            $this->entityManager->persist($newLesson);
            $this->entityManager->flush();

        }

        return Command::SUCCESS;
    }
}