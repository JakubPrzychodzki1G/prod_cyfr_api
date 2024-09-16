<?php

// src/EventListener/UserChangedNotifier.php
namespace App\EventListener;

use App\Entity\MediaObject;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Events;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\Type;

#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: MediaObject::class)]
#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: MediaObject::class)]
class MediaObjectRelatedToSomethingListener
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ParameterBagInterface $params
    )
    {
    }
    public function postUpdate(MediaObject $mediaObject, PostUpdateEventArgs $event): void
    {
        $this->moveFileToNotTmp($mediaObject);
    }

    public function postPersist(MediaObject $mediaObject, PostPersistEventArgs $event): void
    {
        $this->moveFileToNotTmp($mediaObject);
    }

    private function moveFileToNotTmp(MediaObject $mediaObject): void
    {
        if (!$mediaObject->getIsTmp()) return;

        $extractor = new ReflectionExtractor(); // You can customize this extractor if needed
        $properties = $extractor->getProperties(MediaObject::class);

        foreach ($properties as $property) {
            $types = $extractor->getTypes(MediaObject::class, $property);
            foreach ($types as $type) {
                
                if(!$type->isCollection()) continue;

                $getterName = "get".ucfirst($property);

                if($mediaObject->$getterName()->isEmpty()) continue;

                $filesystem = new Filesystem();

                $mediaObject->setIsTmp(false);
                $oldPath = $this->params->get("PUBLIC_PATH")."/".$this->params->get("TMP_PATH")."/".$mediaObject->getFilePath();
                $newPath = $this->params->get("PUBLIC_PATH")."/".$this->params->get("MEDIA_PATH")."/".$mediaObject->getFilePath();
                
                if(!$filesystem->exists($oldPath)) continue;
                    
                $filesystem->rename($oldPath, $newPath);

                $this->entityManager->persist($mediaObject);
                $this->entityManager->flush();
            }
        }
    }
}