<?php

namespace App\Services;

use OpenAI;
use Symfony\Component\HttpFoundation\Response;

class OpenAIService
{
    private $client;

    public function __construct()
    {
        $this->client = OpenAI::client($_ENV['OPENAI_API_KEY']);
    }

    public function generatePostContent(string $whatUserWants, bool $generateImages): array|string
    {
        $allowed_keywords_roots = array(
            "pływ",          // pływanie, pływak, pływacki, pływając, pływają
            "basen",         // basen, baseny, basenowy
            "wod",           // woda, wodny, wodzie, wodą
            "trening",       // trening, treningowy, trenując
            "zawod",         // zawody, zawodnik, zawodniczka, zawodowy
            "kurs",          // kurs, kursy, kursowy
            "nauk",          // nauka, nauczanie, uczący
            "instrukt",      // instruktor, instruktorka, instruktażowy
            "ratown",        // ratownik, ratowniczka, ratowniczy, ratownictwo
            "technik",       // technika, techniczny, techniczna, techniką
            "kraul",         // kraul, kraulem, kraulowy
            "żabk",          // żabka, żabką, żabkowy
            "grzbiet",       // grzbiet, grzbietowy, grzbietem
            "dowoln",        // dowolny, dowolna, dowolnie, dowolnym
            "motyl",         // motylek, motylkowy, motylem
            "dystans",       // dystans, dystansowy, dystansem
            "nawrot",        // nawroty, nawrotowy, nawrotem
            "start",         // start, starty, startowy, startować
            "klub",          // klub, klubowy, klubie, kluby
            "członk",        // członkostwo, członek, członkowie, członkowski
            "zapis",         // zapisy, zapisany, zapisywać, zapisując
            "harmonogram",   // harmonogram, harmonogramowy
            "termin",        // terminarz, termin, terminowy, terminy
            "zajęc",         // zajęcia, zajęcie, zajęciami
            "lekcj",         // lekcje, lekcja, lekcyjny
            "grup",          // grupy, grupowy, grupa
            "opłat",         // opłaty, opłat, opłatowy
            "składk",        // składka, składki, składkowy
            "regulamin",     // regulamin, regulaminowy
            "sekcj",         // sekcja, sekcje, sekcyjny
            "zarząd",        // zarząd, zarządzać, zarządzenie
            "wynik",         // wyniki, wynikowy, wynikiem
            "eliminac",      // eliminacje, eliminacyjny, eliminując
            "finał",         // finał, finałowy, finałem
            "nagrod",        // nagrody, nagrodzony, nagradzać
            "podium",        // podium, podiumowy
            "mistrz",        // mistrzostwa, mistrz, mistrzowski
            "puchar",        // puchar, pucharowy
            "rywalizac",     // rywalizacja, rywalizacyjny, rywalizując
            "zgłosz",        // zgłoszenie, zgłoszony, zgłosić
            "strój",         // strój, stroje, strojem
            "okular",        // okulary, okularowy
            "czepek",        // czepek, czepki
            "płetw",         // płetwy, płetwa, płetwami
            "deska",         // deska, deski, deską
            "bojka",         // bojka, bojki, bojkami
            "tor",           // tor, tory, torowy
            "stoper",        // stoper, stoperem
            "zegar",         // zegar, zegary, zegarem
            "plan",          // plan, plany, planowy
            "rozgrzewk",     // rozgrzewka, rozgrzewkowy
            "saun",          // sauna, sauny, saunowy
            "jacuzzi",       // jacuzzi
            "odnow",         // odnowa, odnawianie, odnowiony
            "fizjoterap",    // fizjoterapia, fizjoterapeuta, fizjoterapeutyczny
            "rehabilitac",   // rehabilitacja, rehabilitacyjny, rehabilitować
            "stretch",       // stretching, stretchować
            "diet",          // dieta, diety, dietetyczny
            "regenerac",     // regeneracja, regeneracyjny
            "kondycj",       // kondycja, kondycyjny
            "ścian",         // ściana, ściany, ścianowy
            "blok",          // blok, bloki, blokiem
            "sztafet",       // sztafeta, sztafety, sztafetowy
            "tor",           // tor, torowy
            "licencj",       // licencja, licencyjny
            "medal",         // medal, medalowy, medalem
            "klas",          // klasy, klasa, klasyczny, klaso
            "rekreac",       // rekreacja, rekreacyjny
            "przepis",       // przepisy, przepisowy, przepisem
            "organizac",     // organizacja, organizacyjny, organizując
            "ranking",       // ranking, rankingowy
            "rekord",        // rekord, rekordowy
            "czasówk",       // czasówka, czasówkami
            "zawodnik",      // zawodnik, zawodnicy, zawodniczka, zawodniczy
            "mityng",        // mityng, mityngi
            "oboz",          // obóz, obozy, obozowy
            "szkoł",         // szkoła, szkoły, szkolenie, szkolny
            "aquapark",      // aquapark
            "delfin",        // delfin, delfinowy
            "triathlon",     // triathlon, triathlonowy
            "bezpieczeństw", // bezpieczeństwo, bezpieczny
            "pierwsz",       // pierwsza, pierwsze, pierwszy
            "procedur",      // procedura, procedury, procedurami
            "ratownictw",    // ratownictwo, ratownictwem
            "skok",          // skoki, skokiem, skokowy
            "nurk",          // nurkowanie, nurkowy, nurkując
            "wytrzymał",     // wytrzymałość, wytrzymałościowy
            "sprzęt",        // sprzęt, sprzętowy
            "trener",        // trener, trenerka, trenerski
            "pływaln",        // pływalnia, pływalni, pływalniowy
            "przepły",
            "otyliad",
            "ogólnopol",
            "wodn",
            "woda",
        );

        // Sprawdź, czy w wiadomości pojawia się rdzeń któregokolwiek z dozwolonych słów
        $notAllowed = true;
        foreach ($allowed_keywords_roots as $keyword_root) {
            if (strpos(strtolower($whatUserWants), $keyword_root)) {
                $notAllowed = false;
            }
        }

        if ($notAllowed) {
            return "Przepraszam, mogę generować tylko treści związane z klubem.";
        }

        $prompt = $whatUserWants;
        $response = $this->client->chat()->create([
            'model' => 'ft:gpt-4o-2024-08-06:personal:postsv2:A6MrKDni',
            'messages' => [
                ['role' => 'system', 'content' => "Marv to właściciel klubu pływackiego 'Posejdon Konin', który jest zabawny i czasem delikatnie sarkastyczny."],
                ['role' => 'user', 'content' => $prompt.'. Post powinnien mieć odpowiednie wciecia, przerwy, punkty i tym podobne.'],
            ],
            'max_tokens' => 1000,
        ]);

        $topicResponse = $this->client->chat()->create([
            'model' => 'ft:gpt-4o-2024-08-06:personal:postsv2:A6MrKDni',
            'messages' => [
                ['role' => 'system', 'content' => "Marv to właściciel klubu pływackiego 'Posejdon Konin', który jest zabawny i czasem delikatnie sarkastyczny."],
                ['role' => 'user', 'content' => "Stwórz tytuł postu (maksymalnie 90 znaków) na podstawie poniższego tekstu, który jest treścią postu: " . $response['choices'][0]['message']['content']],
            ],
            'max_tokens' => 1000,
        ]);

        if($response){
            $image = $this->generateImage($response['choices'][0]['message']['content']);
        }

        return [
            'content' => $response['choices'][0]['message']['content'],
            'title' => $topicResponse['choices'][0]['message']['content'],
            'image' => $image
        ];
    }

    public function generateImage(string $postContent): string
    {
        $promptToGenerateImages = $this->client->chat()->create([
            'model' => 'gpt-4o',
            'messages' => [
                ['role' => 'user', 'content' => 'wygeneruj na podstawie poniższego tekstu prompt dla dall-e 3 w języku angielskim, dzięki któremu wygeneruje obrazy oparte o ten tekst.
            ' . $postContent. '
            Jeśli w tekscie nie jest podony konkretny wiek postaci, to powinny być to młode osoby w wieku 15-20 lat.'],
            ],
            'max_tokens' => 1000,
        ]);
        // dd($promptToGenerateImages['choices'][0]['message']['content']);
        $response = $this->client->images()->create([
            'model' => 'dall-e-3',
            'prompt' => $promptToGenerateImages['choices'][0]['message']['content'].' Characters should have friendly looking faces and they came from poland. Realistic photography, cinematic lighting, in the style of Canon EOS R5.',
            'n' => 1,
            'quality' => 'hd',
            'style' => 'natural',
            'size' => '1024x1024',
            'response_format' => 'b64_json',
        ]);
        
        return $this->saveBase64Image($response['data'][0]['b64_json']);
    }

    private function saveBase64Image(string $base64Image): string
    {
        // Decode the Base64 string
        $decodedData = base64_decode($base64Image);
        $filename = uniqid('image_', true) . '.png';
        // Save the decoded data to a file
        file_put_contents('F:\poseidon\baranApi\public\media\\'.$filename, $decodedData);

        return $filename;
    }
}