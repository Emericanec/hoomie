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

    /**
     * @param int $userId
     * @return User[]
     */
    public function findByUserId(int $userId): array
    {
        return $this->findBy(['user' => $userId]);
    }
}
