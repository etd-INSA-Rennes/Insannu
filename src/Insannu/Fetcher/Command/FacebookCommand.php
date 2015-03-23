<?php
namespace Insannu\Fetcher\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Cilex\Command\Command;

use Insannu\Fetcher\Connector\Ldap;

class FacebookCommand extends Command {

    protected function configure()
    {
        $this
            ->setName('fetcher:facebook')
            ->setDescription('Import people from Facebook');
    }
     protected function execute(InputInterface $input, OutputInterface $output)
    {
         $text = "hello";

         $output->writeln($text); 
     }
}
