<?php

namespace App\Repository;

use App\Entity\Banniere;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Banniere>
 *
 * @method Banniere|null find($id, $lockMode = null, $lockVersion = null)
 * @method Banniere|null findOneBy(array $criteria, array $orderBy = null)
 * @method Banniere[]    findAll()
 * @method Banniere[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BanniereRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Banniere::class);
    }

//    /**
//     * @return Banniere[] Returns an array of Banniere objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Banniere
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
