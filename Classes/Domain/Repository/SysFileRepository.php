<?php

declare(strict_types=1);

namespace Koehnlein\Falduplicates\Domain\Repository;

use Doctrine\DBAL\Exception;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class SysFileRepository
{
    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_file');
        $queryBuilder->from('sys_file');
        return $queryBuilder;
    }

    /**
     * @return Connection
     */
    protected function getConnection()
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('sys_file');
    }

    /**
     * Find all sha1 hashes with multiple records
     *
     * @return array
     * @throws Exception
     */
    public function findMultipleFileHashes(bool $includeMissing = false): array
    {
        $hashes = [];

        if ($includeMissing) {
            $where = '';
        } else {
            $where = ' WHERE missing=0 ';
        }

        $stmt = $this->getConnection()->executeQuery('SELECT sha1, count(uid) as cnt FROM sys_file ' . $where . ' GROUP BY sha1 ORDER BY cnt DESC');
        while ($row = $stmt->fetchAssociative()) {
            if ($row['cnt'] > 1) {
                $hashes[] = $row['sha1'];
            } else {
                break;
            }
        }

        return $hashes;
    }

    /**
     * Find all file records by sha1 hash
     *
     * @param string $sha1
     * @return array
     */
    public function findBySha1($sha1, bool $includeMissing = false)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder
            ->select('*')
            ->where(
                $queryBuilder->expr()->eq('sha1', $queryBuilder->createNamedParameter($sha1))
            );

        if (!$includeMissing) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq('missing', $queryBuilder->createNamedParameter(0))
            );
        }

        return $queryBuilder->executeQuery()->fetchAllAssociative();
    }
}
