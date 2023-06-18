<?php

namespace Purush\CstoreBundle\Command;

use Pimcore\Console\AbstractCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Purush\CstoreBundle\Service\ClassificationStoreService;

class ClassificationStoreExportCommand extends AbstractCommand
{
    public function __construct(private ClassificationStoreService $service) {
        parent::__construct();
    }
    public function configure(): void
    {
        $this->setName('oimcore:cstore:export')
            ->setDescription('Exports the classification store configurations')
            ->addOption('filepath', 'f', InputOption::VALUE_REQUIRED, 'adds a file path to export json')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
//        GroupConfig KeyConfig Group Key Collection CollectionRelation
        $filepath = $input->getOption('filepath');
        if ($filepath) {
            $this->service->exportTo($filepath); //exports to filepath
        } else {
            $output->writeln($this->service->export()); //exports to stdout stream
        }
        return Command::SUCCESS;
    }
}
