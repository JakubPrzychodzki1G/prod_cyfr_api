<?php

namespace App\Controller;

use App\Services\OpenAIService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use App\Entity\Activities;
use App\Entity\MediaObject;
use Doctrine\ORM\EntityManagerInterface;

#[IsGranted('ROLE_COACH')]
class PostGeneratorController extends AbstractController
{

    public function __construct(
        #[Autowire(service: OpenAIService::class)]
        private OpenAIService $openAIService,
        private EntityManagerInterface $entityManager
    )
    {
    }

    #[Route(
        '/api/post_generator', 
        name: 'post_generator', 
        methods: ['POST']
    )]
    public function chat(Request $request): Response
    {
        $payload = $request->getPayload();

        $prompt = $payload->get('question');
        $date = $payload->get('date');
        $generateImages = $payload->get('generateImages');

        if(empty($prompt) || empty($date) || !$postDate=new \DateTime($date)) {
            return new Response('{"message": "Please provide a prompt and a correct date"}', 500);
        }
        try {
            $generatedData = $this->openAIService->generatePostContent($prompt, $generateImages);
        } catch (\Exception $e) {
            throw new \Exception("Error while generating content: {$e->getMessage()}");
        }

        if(is_string($generatedData)) {
            return new Response("{\"message\": \"Error while generating content\", \"cause\":{$generatedData}}", 500);
        }

        if($generatedData['image']) {
            $mediaObject = new MediaObject();
            $mediaObject->setFilePath($generatedData['image']);
            $mediaObject->setIsTmp(false);
            $this->entityManager->persist($mediaObject);
        }

        $newActivity = new Activities();
        $newActivity->setTitle($generatedData['title']);
        $newActivity->setText($generatedData['content']);
        
        if($mediaObject){
            $newActivity->addImage($mediaObject);
            $newActivity->setTitleImage("/{$_ENV['MEDIA_PATH']}/{$mediaObject->getFilePath()}");
        }

        $newActivity->setCreationDate(new \DateTime());
        $newActivity->setIsDeleted(false);
        $newActivity->setDate($postDate);

        $this->entityManager->persist($newActivity);
        $this->entityManager->flush();

        return new Response(json_encode($newActivity));
    }
}