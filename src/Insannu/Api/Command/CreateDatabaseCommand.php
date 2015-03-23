<?php
namespace Insannu\Api\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Cilex\Command\Command;

use Insannu\Api\Model\Student;
use Insannu\Api\Model\User;

class CreateDatabaseCommand extends Command {

    protected function configure()
    {
        $this
            ->setName('database:create')
            ->setDescription('Create database');
    }
     protected function execute(InputInterface $input, OutputInterface $output)
     {
         $s = new Student($this->getContainer());
         $s->initDb();

         $u = new User($this->getContainer());
         $u->initDb();

         $text = "Database created";

         $output->writeln($text); 
     }
}

