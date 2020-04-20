<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Page;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * @method Page|null find($id, $lockMode = null, $lockVersion = null)
 * @method Page|null findOneBy(array $criteria, array $orderBy = null)
 * @method Page[]    findAll()
 * @method Page[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Page::class);
    }

    /**
     * @param int $userId
     * @return Page[]
     */
    public function findByUserId(int $userId): array
    {
        return $this->findBy(['user' => $userId]);
    }

    /**
     * @param User $user
     * @return bool
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createMainPage(User $user): bool
    {
        $pages = $user->getPages();

        if (count($pages)) {
            return false;
        }

        $page = new Page();
        $page->setUrl('/');
        $page->setTitle('Main');
        $page->setUser($user);

        $em = $this->getEntityManager();
        $em->persist($page);
        $em->flush();

        return true;
    }
}
