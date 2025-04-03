<?php

declare(strict_types=1);

namespace Koehnlein\Falduplicates\Command;

use Koehnlein\Falduplicates\Service\DuplicateService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class FindDuplicates extends Command
{
    protected function configure()
    {
        $this
            ->setDescription('Find and list FAL duplications')
            ->setHelp('Find and list FAL duplications by sys_file.sha1')
            ->addOption('includeMissing', null, InputOption::VALUE_NONE, 'Include records marked as missing');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var DuplicateService $duplicateService */
        $duplicateService = GeneralUtility::makeInstance(DuplicateService::class);
        $duplicateService->setOutput($output);

        if ($duplicates = $duplicateService->findDuplicates($input->getOption('includeMissing'))) {
            $output->writeln('storage;path;is missing');

            foreach ($duplicates as $hashGroup) {
                /** @var File $file */
                foreach ($hashGroup as $file) {
                    $output->writeln(sprintf(
                        '%s;%s;%s',
                        $file->getStorage()->getName(),
                        $file->getIdentifier(),
                        $file->getProperty('missing')
                    ));
                }
                $output->writeln('');
            }
        }

        return 0;
    }
}
