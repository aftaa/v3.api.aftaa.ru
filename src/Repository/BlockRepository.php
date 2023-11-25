<?php

namespace App\Repository;

use App\Entity\Block;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Block>
 *
 * @method Block|null find($id, $lockMode = null, $lockVersion = null)
 * @method Block|null findOneBy(array $criteria, array $orderBy = null)
 * @method Block[]    findAll()
 * @method Block[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Block::class);
    }

    public function save(Block $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Block $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return array
     */
    public function findNotDeletedOrdered(): array
    {
        $qb = $this->createQueryBuilder('b')
            ->addOrderBy('b.sort')
            ->where('b.deleted=FALSE');
        return $qb->getQuery()->getResult();
    }

    /**
     * @return array
     */
    public function findTrash(): array
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.sort')
            ->getQuery()->execute();
    }

    /**
     * @return Block[]
     */
    public function findNotDeleted(): array
    {
        $qb = $this->createQueryBuilder('b')
            ->where('b.deleted=FALSE')
            ->orderBy('b.name', 'ASC');
        return $qb->getQuery()->getResult(AbstractQuery::HYDRATE_ARRAY);
    }

    public function findPublicData()
    {
        $qb = $this->createQueryBuilder('b')
            ->where('b.deleted=FALSE')
            ->andWhere('b.private=FALSE')
            ->orderBy('b.name', 'ASC');
        return $qb->getQuery()->getResult();
    }
}
