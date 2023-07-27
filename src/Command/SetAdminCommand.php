<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:set_admin',
    description: 'Add a short description for your command',
)]
class SetAdminCommand extends Command
{


    public function __construct(private EntityManagerInterface $entityManager, private UserRepository $userRepository)
    {
        parent::__construct();
    }


    protected function configure(): void
    {
        $this->addArgument('userEmail', InputArgument::REQUIRED, 'Email');

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $userEmail = $input->getArgument('userEmail');
       $userEntity = $this->userRepository->findOneBy(['email' => $userEmail]);

        if($userEntity !== null){
            $userEntity->setRoles(["ROLE_ADMIN"]);
            $this->entityManager->persist($userEntity);
            $this->entityManager->flush();
            $output->writeln($userEmail . ' est maintenant admin');
            return Command::SUCCESS;
        }else{
            $output->writeln($userEmail . " n'existe pas");
            return Command::FAILURE;
        }

    }
}
