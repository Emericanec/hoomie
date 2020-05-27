<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Node;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Node|null find($id, $lockMode = null, $lockVersion = null)
 * @method Node|null findOneBy(array $criteria, array $orderBy = null)
 * @method Node[]    findAll()
 * @method Node[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Node::class);
    }

    public function updateSort(int $id, int $sort): void
    {
        $where = $this->_em->createQueryBuilder()->expr()->eq('n.id', $id);
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->update(Node::class, 'n')
            ->set('n.sort', $sort)
            ->where($where)->getFirstResult();
        $query = $queryBuilder->getQuery();
        $query->execute();
    }
}
