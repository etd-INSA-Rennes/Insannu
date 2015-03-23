<?php
namespace Insannu\Fetcher\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Cilex\Command\Command;

use Insannu\Fetcher\Connector\Ent;

class LegacyPictureCommand extends Command {

    protected function configure()
    {
        $this
            ->setName('fetcher:picture:legacy')
            ->setDescription('Import people from folder');
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $text = 'Ent dumped';
        $ent = new Ent($this->getContainer());
        $ent->parseFolder();
        $output->writeln($text); 
    }
}
