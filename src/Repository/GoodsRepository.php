<?php

namespace App\Repository;

use App\Entity\Goods;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Goods|null find($id, $lockMode = null, $lockVersion = null)
 * @method Goods|null findOneBy(array $criteria, array $orderBy = null)
 * @method Goods[]    findAll()
 * @method Goods[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GoodsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Goods::class);
    }

    public function findOneVisibleBy(array $criteria, array $orderBy = null): ?Goods
    {
        $criteria = array_merge(['status' => Goods::STATUS_ACTIVE], $criteria);
        return $this->findOneBy($criteria, $orderBy);
    }

    public function findVisible($id): ?Goods
    {
        return $this->findOneVisibleBy(['id' => $id]);
    }

    public function findVisibleBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        $criteria = array_merge(['status' => Goods::STATUS_ACTIVE], $criteria);
        return $this->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param int $userId
     * @return User[]
     */
    public function findByUserId(int $userId): array
    {
        return $this->findVisibleBy(['user' => $userId]);
    }
}
