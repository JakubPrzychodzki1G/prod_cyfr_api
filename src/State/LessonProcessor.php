<?php
// api/src/Sate/UserProcessor.php

namespace App\State;

use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Lesson;
use App\Entity\Attendance;
use App\Repository\LessonRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\Mapping\ClassMetadata;


/**
 * @implements ProcessorInterface<User, User|void>
 */
final class LessonProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: PersistProcessor::class)]
        private ProcessorInterface $persistProcessor,
        private EntityManagerInterface $entityManager,
        private LessonRepository $lessonRepository,
    )
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []) //: Lesson|null
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('players_id', 'PlayerId');
        $query = 
            $this->entityManager->createNativeQuery('SELECT * FROM lesson LEFT JOIN lesson_players lp on lp.lesson_id = id WHERE id = :id', $rsm);
        $query->setParameter('id', $data->getId());
        $res = $query->getResult();
        $newPlayersIds = array_map(function($player) {
            return $player->getId();
        }, $data->getPlayers()->toArray());
        if(count($res) > count($data->getPlayers())) {
            foreach($res as $value){
                if(!in_array($value['PlayerId'], $newPlayersIds)) {
                    $attendanceToDelete = $data->getAttendances()->filter(function($attendance) use ($value) {
                        return $attendance->getPlayer()->getId() === $value['PlayerId'];
                    })->first();
                    if($attendanceToDelete){
                        $this->entityManager->remove($attendanceToDelete);
                    }
                }
            }
        }

        $result = $this->persistProcessor->process($data, $operation, $uriVariables, $context);

        return $result;
    }
}