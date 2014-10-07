<?php
namespace Insannu\Fetcher\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Cilex\Command\Command;

use Insannu\Fetcher\Connector\Ldap;

class EntCommand extends Command {

    protected function configure()
    {
        $this
            ->setName('fetcher:ent')
            ->setDescription('Import people from Ent');
    }
     protected function execute(InputInterface $input, OutputInterface $output)
     {
         $text = "hello";

         $output->writeln($text); 
     }
}
