<?php

namespace App\Repository;

use App\Entity\Link;
use App\Entity\View;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    public function findTop(int $limit = 42): array
    {
            $qb = $this->createQueryBuilder('v')
                ->innerJoin(Link::class, 'l', 'WITH', 'v.link=l')
                ->select('COUNT(v) AS count')
                ->addSelect('l.name, l.icon, l.href, l.id')
                ->setMaxResults($limit)
                ->orderBy('COUNT(v)', 'DESC')
                ->groupBy('l.id');
            return $qb->getQuery()->execute();
    }

    public function getViews()
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

//    /**
//     * @return View[] Returns an array of View objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?View
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
