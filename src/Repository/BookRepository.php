<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

       /**
        * @return Book[] Returns an array of Book objects
        */
       public function findByYearPublished(int $year = null): array
       {
           $qb = $this->createQueryBuilder('b')
               ->andWhere('b.isOnLine = :val1')
               ->setParameter('val1', 1);
             

                if($year !== null) {
                    $qb->andWhere('b.YearPublished = :val2')
                    ->setParameter('val2', $year );
                }

            return $qb
                    ->getQuery()
                    ->getResult();
           
       }

    //    public function findOneBySomeField($value): ?Book
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
