<?php

namespace App\Repository;

use App\Entity\ReportRow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReportRow>
 *
 * @method ReportRow|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReportRow|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReportRow[]    findAll()
 * @method ReportRow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportRowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReportRow::class);
    }

//    /**
//     * @return ReportRow[] Returns an array of ReportRow objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ReportRow
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
