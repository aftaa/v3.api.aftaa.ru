<?php

namespace App\Repository;

use App\Entity\Block;
use App\Entity\Link;
use App\Entity\View;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<View>
 *
 * @method View|null find($id, $lockMode = null, $lockVersion = null)
 * @method View|null findOneBy(array $criteria, array $orderBy = null)
 * @method View[]    findAll()
 * @method View[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ViewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, View::class);
    }

    public function save(View $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(View $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param int $limit
     * @return array
     */
    public function findTop(int $limit = 42): array
    {
            $qb = $this->createQueryBuilder('v')
                ->innerJoin(Link::class, 'l', 'WITH', 'v.link=l')
                ->innerJoin(Block::class, 'b', 'WITH', 'l.block=b')
                ->select('COUNT(v) AS count')
                ->addSelect('l.name, l.icon, l.href, l.id')
                ->setMaxResults($limit)
                ->where('l.deleted=FALSE')
                ->andWhere('b.deleted=FALSE')
                ->orderBy('COUNT(v)', 'DESC')
                ->groupBy('l.id');
            return $qb->getQuery()->execute();
    }

    /**
     * @return array
     */
    public function getViews(): array
    {
        $qb = $this->createQueryBuilder('v')
            ->innerJoin(Link::class, 'l', 'WITH', 'v.link=l')
            ->select('COUNT(v) AS count')
            ->addSelect('l.id')
            ->groupBy('l.id');
        $rows = $qb->getQuery()->execute();
        $views = [];
        foreach ($rows as $row) {
            $views[$row['id']] = $row['count'];
        }
        return $views;
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function findViews(?int $linkId): int
    {
        return $this->createQueryBuilder('v')
            ->select('COUNT(v)')
            ->where('v.link=:linkId')
            ->setParameter('linkId', $linkId)
            ->getQuery()->getSingleScalarResult();
    }
}
