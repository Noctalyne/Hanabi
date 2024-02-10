<?php

namespace App\Repository;

use App\Entity\Adresses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Adresses>
 *
 * @method Adresses|null find($id, $lockMode = null, $lockVersion = null)
 * @method Adresses|null findOneBy(array $criteria, array $orderBy = null)
 * @method Adresses[]    findAll()
 * @method Adresses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdressesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Adresses::class);
    }




    // récupère toutes les adresses du clients
    public function findAdresses(int $id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT *
            FROM user as u
            INNER JOIN adresses  as a
            WHERE u.id = :id
            ';
        $params = ['id' => $id]; // recupère la valeur de l'url

        $resultSet = $conn->executeQuery($sql, $params);

        return $resultSet->fetchAllAssociative(); // returns un tableau de tableau SANS objet
    }


    // récupère UNE adresse de l'utilisateur
    public function findOneAdresses(int $id_adrss)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
                SELECT *
                FROM user as u
                INNER JOIN adresses  as a
                WHERE a.id = :id_adrss
                ';
        $params = ['id_adrss' => $id_adrss]; // recupère la valeur de l'url

        $resultSet = $conn->executeQuery($sql, $params);

        return $resultSet->fetchAllAssociative(); // returns un tableau de tableau SANS objet
    }

    //    /**
    //     * @return Adresses[] Returns an array of Adresses objects
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

    //    public function findOneBySomeField($value): ?Adresses
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
