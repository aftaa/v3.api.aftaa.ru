<?php

namespace App\Repository;

use App\Entity\Link;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Link>
 *
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

    public function save(Link $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Link $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param int $blockId
     * @return Link[]
     */
    public function findLinksByBlockId(int $blockId): array
    {
        return $this->createQueryBuilder('l')
            ->select('l.name, l.id, l.href')
            ->join('l.block', 'b')
            ->where('l.block=:block_id')
            ->andWhere('l.deleted=FALSE AND b.deleted=FALSE')
            ->setParameter('block_id', $blockId)
            ->orderBy('l.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
