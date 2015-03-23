<?php
namespace Insannu\Fetcher\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Cilex\Command\Command;

use Insannu\Fetcher\Connector\Ent;

class DirectoryPictureCommand extends Command {

    protected function configure()
    {
        $this
            ->setName('fetcher:picture:directory')
            ->setDescription('Import people from Ent');
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $text = 'Ent dumped';
        $ent = new Ent($this->getContainer());
        $ent->parseFile();
        $output->writeln($text); 
    }
}
