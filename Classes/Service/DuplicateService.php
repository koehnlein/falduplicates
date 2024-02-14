<?php

declare(strict_types=1);

namespace Koehnlein\Falduplicates\Service;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException;
use Koehnlein\Falduplicates\Domain\Repository\SysFileRepository;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class DuplicateService implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var ResourceFactory
     */
    protected $resourceFactory;

    /**
     * @var SysFileRepository
     */
    protected $sysFileRepository;

    protected ?OutputInterface $output;

    public function __construct()
    {
        $this->resourceFactory = GeneralUtility::makeInstance(ResourceFactory::class);
        $this->sysFileRepository = GeneralUtility::makeInstance(SysFileRepository::class);
    }

    public function setOutput(OutputInterface $output): void
    {
        $this->output = $output;
    }

    /**
     * Find and return duplicates grouped by hash
     *
     * @param bool $includeMissing
     * @return array
     * @throws FileDoesNotExistException
     */
    public function findDuplicates($includeMissing = false): array
    {
        $duplicates = [];

        foreach ($this->sysFileRepository->findMultipleFileHashes($includeMissing) as $hash) {
            if ($files = $this->sysFileRepository->findBySha1($hash, $includeMissing)) {
                foreach ($files as $file) {
                    try {
                        $duplicates[$hash][] = $this->resourceFactory->getFileObject($file['uid'], $file);
                    } catch (\TypeError) { // @phpstan-ignore-line
                        $message = sprintf('Error for file uid %s (%s) in storage %s', $file['uid'], $file['identifier'], $file['storage']);
                        $this->logger?->error($message, $file);
                        $this->output?->writeln("<error>$message</error>");
                    }
                }
            }
        }

        return $duplicates;
    }
}
