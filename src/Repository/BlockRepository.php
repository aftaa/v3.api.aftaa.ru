<?php

namespace App\Repository;

use App\Entity\Block;
use App\Entity\Link;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
     * @param bool $deleted
     * @return Block[]
     */
    public function findBlocks(bool $deleted = false): array
    {
        $qb = $this->createQueryBuilder('b')
            ->addOrderBy('b.sort');
        if ($deleted) {
            $qb->where('b.deleted=TRUE');
        } else {
            $qb->where('b.deleted=FALSE');
        }
        return $qb->getQuery()->getResult();
    }

    public function findATrash(): array
    {
        /** @var Block[] $blocks */
        $blocks = $this->createQueryBuilder('b')
            ->orderBy('b.sort')
            ->getQuery()->execute();
        $result = [];
        foreach ($blocks as $block) {
            $resultBlock = [
                'id' => $block->getId(),
                'name' => $block->getName(),
                'col' => $block->getCol(),
                'sort' => $block->getSort(),
                'deleted' => $block->isDeleted(),
                'private' => $block->isPrivate(),
                'links' => [],
            ];
            foreach ($block->getLinks() as $link) {
                if ($link->isDeleted()) {
                    $resultBlock['links'][$link->getId()] = [
                        'id' => $link->getId(),
                        'name' => $link->getName(),
                        'href' => $link->getHref(),
                        'icon' => str_replace('https://v2.api.aftaa.ru', 'https://v3.api.aftaa.ru', $link->getIcon()),
                        'private' => $link->isPrivate(),
                    ];
                }
            }
            $result[$block->getCol()][$block->getId()] = $resultBlock;
        }

        foreach ($result as $col => $blocks) {
            foreach ($blocks as $blockId => $block) {
                if ($block['deleted'] || count($block['links']) > 0) {
                    usort($block['links'], function (array $link1, array $link2): int {
                        return strcmp($link1['name'], $link2['name']);
                    });
                    continue;
                }
                unset($result[$col][$blockId]);
            }
        }
        return $result;
    }

    /**
     * @return Block[]
     */
    public function findCollection(): array
    {
        $qb = $this->createQueryBuilder('b')
            ->where('b.deleted=FALSE')
            ->orderBy('b.name');
        return $qb->getQuery()->execute();
    }
}
