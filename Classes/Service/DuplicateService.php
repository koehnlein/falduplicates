<?php

declare(strict_types=1);

namespace Koehnlein\Falduplicates\Service;

use Koehnlein\Falduplicates\Domain\Repository\SysFileRepository;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class DuplicateService
{
    /**
     * @var ResourceFactory
     */
    protected $resourceFactory;

    /**
     * @var SysFileRepository
     */
    protected $sysFileRepository;

    public function __construct()
    {
        $this->resourceFactory = ResourceFactory::getInstance();
        $this->sysFileRepository = GeneralUtility::makeInstance(SysFileRepository::class);
    }

    /**
     * Find and return duplicates grouped by hash
     *
     * @param bool $includeMissing
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     * @throws \TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException
     */
    public function findDuplicates($includeMissing = false): array
    {
        $duplicates = [];

        foreach ($this->sysFileRepository->findMultipleFileHashes($includeMissing) as $hash) {
            if ($files = $this->sysFileRepository->findBySha1($hash, $includeMissing)) {
                foreach ($files as $file) {
                    $duplicates[$hash][] = $this->resourceFactory->getFileObject($file['uid'], $file);
                }
            }
        }

        return $duplicates;
    }
}
