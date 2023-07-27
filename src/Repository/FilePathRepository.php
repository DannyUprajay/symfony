<?php

namespace App\Repository;

use App\Entity\FilePath;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FilePath>
 *
 * @method FilePath|null find($id, $lockMode = null, $lockVersion = null)
 * @method FilePath|null findOneBy(array $criteria, array $orderBy = null)
 * @method FilePath[]    findAll()
 * @method FilePath[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilePathRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FilePath::class);
    }

//    /**
//     * @return FilePath[] Returns an array of FilePath objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FilePath
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
