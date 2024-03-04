<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function findAllOrderByDate(bool $includeDisable = false): array
    {
        $query = $this->createQueryBuilder('a')
            ->select('a, c')
            ->leftJoin('a.categories', 'c')
            ->join('a.user', 'u');

        if (!$includeDisable) {
            $query
                ->andWhere('a.enable = :enable')
                ->setParameter('enable', true);
        }

        $query->orderBy('a.createdAt', 'DESC');
        return $query
            ->getQuery()
            ->getResult();
    }

    // public function findAllOrderByTitre(): array
    // {
    //     return $this->createQueryBuilder('c')
    //         ->orderBy('c.titre', 'ASC')
    //         ->getQuery()
    //         ->getResult();
    // }
    //    /**
    //     * @return Article[] Returns an array of Article objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Article
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
