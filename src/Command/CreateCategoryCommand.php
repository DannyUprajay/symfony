<?php

namespace App\Command;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-category',
    description: 'Add a short description for your command',
)]
class CreateCategoryCommand extends Command
{


    public function __construct(private EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument(
            'categoryName',
            InputArgument::REQUIRED,
            'Nom de la catégorie'
        );
//        $this
//            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
//            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
//        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $categoryName = $input->getArgument('categoryName');

        $categoryEntity = new Category();
        $categoryEntity ->setLabel($categoryName);

        $this->entityManager->persist($categoryEntity);
        $this->entityManager->flush();

        $output->writeln("L'entité " . $categoryName . " a été envoyé en bdd");

        return Command::SUCCESS;
    }
}
