<?php

namespace Purush\CstoreBundle\Command;

use Pimcore\Console\AbstractCommand;
use Pimcore\Model\DataObject\Classificationstore\CollectionConfig;
use Pimcore\Model\DataObject\Classificationstore\CollectionGroupRelation;
use Pimcore\Model\DataObject\Classificationstore\GroupConfig;
use Pimcore\Model\DataObject\Classificationstore\KeyConfig;
use Pimcore\Model\DataObject\Classificationstore\KeyGroupRelation;
use Pimcore\Model\DataObject\Classificationstore\StoreConfig;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Purush\CstoreBundle\Service\ClassificationStoreService;

class ClassificationStoreImportCommand extends AbstractCommand
{
    public function __construct(private ClassificationStoreService $service)
    {
        parent::__construct();    
    }

    public function configure(): void
    {
        $this->setName('pimcore:cstore:import')
            ->addOption('filepath', 'f', InputOption::VALUE_OPTIONAL, 'JSON path to import the cstore configuration')
            ->addOption('override', null, InputOption::VALUE_NEGATABLE, 'Whether to override the existing cstore configuration in case of name clash')
        ;
    }

    /**
     * @throws \Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $filepath = $input->getOption('filepath');
        $override = $input->getOption('override') ?? false;
        $content = file_get_contents($filepath);
        if (!$content) {
            throw new \Exception('Filepath must be valid & json');
        }
        $this->service->import($content, $override);
        return Command::SUCCESS;
    }
}
