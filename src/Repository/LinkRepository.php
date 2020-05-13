<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Link;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Link|null find($id, $lockMode = null, $lockVersion = null)
 * @method Link|null findOneBy(array $criteria, array $orderBy = null)
 * @method Link[]    findAll()
 * @method Link[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Link::class);
    }

    public function updateSort(int $linkId, int $sort)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $where = $queryBuilder->expr()->eq('l.id', $linkId);
        $queryBuilder->update(Link::class, 'l')->set('l.sort', $sort)->where($where)->getFirstResult();
        $query = $queryBuilder->getQuery();
        return $query->execute();
    }
}
