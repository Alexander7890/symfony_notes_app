<?php

namespace App\Repository;

use App\Entity\Note;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class NoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Note::class);
    }

    /**
     * @return array{total:int, items: list<Note>}
     */
    public function searchPaginated(
        string $search,
        string $sortBy,
        string $direction,
        int $page,
        int $perPage
    ): array {
        $sortKey = strtolower($sortBy);
        $allowedSorts = [
            'createdat' => 'createdAt',
            'created_at' => 'createdAt',
            'title' => 'title',
            'id' => 'id',
        ];
        $sortBy = $allowedSorts[$sortKey] ?? 'createdAt';

        $direction = strtolower($direction) === 'asc' ? 'ASC' : 'DESC';

        $qb = $this->createQueryBuilder('n');

        $search = trim($search);
        if ($search !== '') {
            $qb
                ->andWhere('LOWER(n.title) LIKE :term OR LOWER(n.text) LIKE :term')
                ->setParameter('term', '%' . mb_strtolower($search) . '%');
        }

        $qb->orderBy('n.' . $sortBy, $direction);

        $countQb = clone $qb;
        $count = (int) $countQb
            ->resetDQLPart('orderBy')
            ->select('COUNT(n.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $items = $qb
            ->setFirstResult(max(0, ($page - 1) * $perPage))
            ->setMaxResults($perPage)
            ->getQuery()
            ->getResult();

        return [
            'total' => $count,
            'items' => $items,
        ];
    }
}