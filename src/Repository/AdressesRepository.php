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
    public function findAdresses($idClientAdresses)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT *
            FROM clients as c
            INNER JOIN adresses  as a
            ON c.id = a.idClientAdresse
            WHERE c.user_id = :user_id
            ';
            $params = ['user_id' => $idClientAdresses]; // recupère la valeur de l'url

        $resultSet = $conn->executeQuery($sql,$params);

        return $resultSet->fetchAllAssociative();// returns un tableau de tableau SANS objet
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