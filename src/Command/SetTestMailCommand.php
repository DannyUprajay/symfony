<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'app:set-test-mail',
    description: 'Add a short description for your command',
)]
class SetTestMailCommand extends Command
{


    public function __construct(
        private HttpClientInterface $httpClient
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $response = $this->httpClient->request('POST', "https://api.brevo.com/v3/smtp/email", [
            "headers" => [
                "accept" => 'application/json',
                "api-key"=> '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',
                'content-type'=>'application/json'
            ],
            'json' => [
                'sender' => [
                    'name' => 'danny uprajay',
                    'email' => 'danny.uprajay@gmail.com'
                ],
                'to' => [
                    [
                        'name' => 'test',
                        'email' => 'dannydu10@live.fr'
                    ],

                ],
                "subject" => 'Bonjour !',
                'htmlContent' => '<html><head></head><body><p>Hello,</p>This is my first transactional email sent from Brevo.</p></body></html>'
            ]
        ]);


        return Command::SUCCESS;
    }
}
