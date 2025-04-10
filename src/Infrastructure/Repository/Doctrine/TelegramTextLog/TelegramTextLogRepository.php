<?php

namespace App\Infrastructure\Repository\Doctrine\TelegramTextLog;

use App\Infrastructure\Doctrine\Entity\TelegramTextLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TelegramTextLog>
 *
 * @method TelegramTextLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method TelegramTextLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method TelegramTextLog[]    findAll()
 * @method TelegramTextLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class TelegramTextLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TelegramTextLog::class);
    }

    public function save(TelegramTextLog $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
