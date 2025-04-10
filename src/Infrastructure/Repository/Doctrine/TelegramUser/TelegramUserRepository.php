<?php

namespace App\Infrastructure\Repository\Doctrine\TelegramUser;

use App\Infrastructure\Doctrine\Entity\TelegramUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TelegramUser>
 *
 * @method TelegramUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method TelegramUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method TelegramUser[]    findAll()
 * @method TelegramUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class TelegramUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TelegramUser::class);
    }

    public function save(TelegramUser $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByChatId(int $chatId): ?TelegramUser
    {
        return $this->findOneBy(['chatId' => $chatId]);
    }
}
