<?php

namespace App\Controller;

use App\Entity\MediaObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\Json;
use ApiPlatform\Api\IriConverterInterface;


#[AsController]
final class CreateMediaObjectAction extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(Request $request, IriConverterInterface $iriConverter): JsonResponse
    {
        $uploadedFiles = $request->files->all();
        $save = $request->request->get('save') === "true";
        $returnArray = array();

        foreach ($uploadedFiles["file"] as $uploadedFile) {
            // dd($uploadedFile);
            if (!$uploadedFile || !exif_imagetype($uploadedFile)) {
                throw new BadRequestHttpException('"file" is required');
            }

            $mediaObject = new MediaObject();
            $mediaObject->file = $uploadedFile;

            if ($save) {
                $mediaObject->setDirectory($_ENV["MEDIA_PATH"]);
                $mediaObject->setIsTmp(false);
            }

            $this->entityManager->persist($mediaObject);
            $this->entityManager->flush();

            $returnArray["returnData"][] = [
                '@context' => 'api/contexts/MediaObject',
                '@id' => $iriConverter->getIriFromResource($mediaObject),
                '@type' => 'MediaObject',
                'id' => $mediaObject->getId(),
                'filePath' => $mediaObject->getFilePath(),
                'isTmp' => $mediaObject->getIsTmp()
            ];
        }
        // dd($mediaObject);
        return new JsonResponse($returnArray);
        
    }
}