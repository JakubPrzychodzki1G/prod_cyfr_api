<?php
// api/src/Sate/UserProcessor.php

namespace App\State;

use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Repository\PlayersRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\Email;

/**
 * @implements ProcessorInterface<User, User|void>
 */
final class PlayersProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: PersistProcessor::class)]
        private ProcessorInterface $persistProcessor,
        private EntityManagerInterface $entityManager,
        private PlayersRepository $lessonRepository,
        private TransportInterface $transport,
    )
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []) //: Lesson|null
    {
        $result = $this->persistProcessor->process($data, $operation, $uriVariables, $context);

        $dataToShowInEmail = [
            ['name' => 'Imię', 'value' => $data->getFirstName()],
            ['name' => 'Nazwisko', 'value' => $data->getLastName()],
            ['name' => 'Data urodzenia', 'value' => $data->getBirthDate()->format('d/m/Y')],
            ['name' => 'Płeć', 'value' => $data->getSex() ? 'Kobieta' : 'Mężczyzna'],
            ['name' => 'Nazwa szkoły', 'value' => $data->getSchoolName()],
            ['name' => 'Miasto', 'value' => $data->getCity()],
            ['name' => 'Ulica', 'value' => $data->getStreetAndNumber()],
            ['name' => 'Kod pocztowy', 'value' => $data->getZipCode()],
            ['name' => 'Imie rodzica', 'value' => $data->getParentFirstName()],
            ['name' => 'Nazwisko rodzica', 'value' => $data->getParentLastName()],
            ['name' => 'Imie drugiego rodzica', 'value' => $data->getParent2FirstName()],
            ['name' => 'Nazwisko drugiego rodzica', 'value' => $data->getParent2LastName()],
            ['name' => 'Email kontaktowy', 'value' => $data->getContactEmail()],
            ['name' => 'Numer telefonu', 'value' => $data->getMainNumber()],
            ['name' => 'Dodatkowy numer telefonu', 'value' => $data->getAdditionalNumber()],
            ['name' => 'Numer zawodnika', 'value' => $data->getPlayerNumber()],
            ['name' => 'Data dodania', 'value' => $data->getCreationDate()->format('d/m/Y')],
            ['name' => 'Dodatkowe informacje', 'value' => $data->getComments()]
        ];

        try {

            $mailer = new Mailer($this->transport);

            $email = (new Email())
                ->from('klub@posejdonkonin.pl')
                ->to('klubposejdonkonin@gmail.com')
                ->subject('Został dodany nowy zawodnik, ' . $data->getFirstName() . ' ' . $data->getLastName())
                ->text('Dodano nowego zawodnika do Klubu Pływackiego Posejdon Konin')
                ->html('<html>
                            <body>
                                    <div style="margin:0;padding:0" bgcolor="#FFFFFF">
                                        <table width="100%" height="100%" style="min-width:348px" border="0" cellspacing="0" cellpadding="0" lang="en">
                                            <tbody>
                                                <tr height="32" style="height:32px">
                                                    <td></td>
                                                </tr>
                                                <tr align="center">
                                                    <td>
                                                        <div>
                                                            <div></div>
                                                        </div>
                                                        <table border="0" cellspacing="0" cellpadding="0" style="padding-bottom:20px;max-width:516px;min-width:220px">
                                                            <tbody>
                                                                <tr>
                                                                    <td width="8" style="width:8px"></td>
                                                                    <td>
                                                                        <div style="border-style:solid;border-width:thin;border-color:#dadce0;border-radius:8px;padding:40px 20px" align="center" class="m_-7935641736316714706mdv2rw">
                                                                            <img src="https://scontent.fpoz2-1.fna.fbcdn.net/v/t39.30808-6/387838387_122130735776020785_9163988692848140362_n.jpg?stp=cp6_dst-jpg&_nc_cat=106&ccb=1-7&_nc_sid=6ee11a&_nc_ohc=17-RmESj8AwQ7kNvgFyRQRQ&_nc_ht=scontent.fpoz2-1.fna&cb_e2o_trans=t&oh=00_AYCAaweygDoar-XzVjxS5rWXkZW2u4IKX_gz8oDjnHyCcw&oe=66E073C4" width="64" height="64" aria-hidden="true" style="margin-bottom:16px" alt="PosejdonKonin" data-bit="iit">
                                                                            <div style="font-family:\'Google Sans\',Roboto,RobotoDraft,Helvetica,Arial,sans-serif;border-bottom:thin solid #dadce0;color:rgba(0,0,0,0.87);line-height:32px;padding-bottom:24px;text-align:center;word-break:break-word">
                                                                                <div style="font-size:24px">Dodano <a>nowego zawodnika</a> do Klubu Pływackiego Posejdon Konin</div>
                                                                            </div>
                                                                            <div style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:14px;color:rgba(0,0,0,0.87);line-height:20px;padding-top:20px;text-align:left">
                                                                                <br>
                                                                                <table align="center" style="margin-top:8px">
                                                                                    <tbody>'
                                                                                        .array_reduce($dataToShowInEmail,function($acc, $item){
                                                                                            return $acc . '
                                                                                            <tr style="line-height:normal">
                                                                                                <td align="right" style="padding-right:8px">
                                                                                                    <p style="margin:0">'.$item['name'].'</p>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <a style="font-family:\'Google Sans\',Roboto,RobotoDraft,Helvetica,Arial,sans-serif;color:rgba(0,0,0,0.87);font-size:14px;line-height:20px">'.$item['value'].'</a>
                                                                                                </td>
                                                                                            </tr>';
                                                                                        }, '').
                                                                                    '</tbody>
                                                                                </table> 
                                                                            </div>
                                                                            <span class="im">
                                                                                <div style="padding-top:20px;font-size:12px;line-height:16px;color:#5f6368;letter-spacing:0.3px;text-align:center">
                                                                                    Link do Panelu Administracyjnego z nowym Zawodnikiem (kliknij w imie i nazwisko):<br>
                                                                                    <br>
                                                                                    <a style="color:rgba(0,0,0,0.87);text-decoration:inherit" href="posejdonkonin.pl/players/show/'.$data->getId().'">
                                                                                        '.$data->getFirstName().' '.$data->getLastName().'
                                                                                    </a>
                                                                                </div>
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <div class="adm">
                                                                                <div id="q_64" class="ajR h4">
                                                                                    <div class="ajT"></div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="h5">
                                                                                <div style="text-align:left">
                                                                                    <div style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;color:rgba(0,0,0,0.54);font-size:11px;line-height:18px;padding-top:12px;text-align:center">
                                                                                        <div>Wysłaliśmy tego e-maila, by poinformować Cię o dodaniu nowego zawodnika do systemu.</div>
                                                                                        <div style="direction:ltr">© 2024 Posejdon Konin, <a class="m_-7935641736316714706afal" style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;color:rgba(0,0,0,0.54);font-size:11px;line-height:18px;padding-top:12px;text-align:center">23/103, ul. 11 listopada, Konin, Polska</a></div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td width="8" style="width:8px"></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                            </body>
                        </html>');

            $mailer->send($email);
        
        } catch (\Symfony\Component\Mailer\Exception\TransportExceptionInterface  $e) {
            dd($e);
        }

        return $result;
    }
}