<?php
namespace Insannu\Fetcher\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Cilex\Command\Command;

use Insannu\Fetcher\Connector\Ldap;

class LdapCommand extends Command {

    protected function configure()
    {
        $this
            ->setName('fetcher:ldap')
            ->setDescription('Import people from LDAP');
    }
     protected function execute(InputInterface $input, OutputInterface $output)
     {
         $text = "hello";

         $output->writeln($text); 
     }
}
