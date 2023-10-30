<?php

namespace App\Repository;

use App\Entity\Clients;
use App\Entity\FormulaireDemandeProduit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FormulaireDemandeProduit>
 *
 * @method FormulaireDemandeProduit|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormulaireDemandeProduit|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormulaireDemandeProduit[]    findAll()
 * @method FormulaireDemandeProduit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormulaireDemandeProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormulaireDemandeProduit::class);
    }

    public function createForm(FormulaireDemandeProduit $formulaireDemandeProduit, int $refClient ): void
    {
        // Obtient la connexion à la base de données
        $connection = $this->getEntityManager()->getConnection();

        // Exécute la requête SQL
        $statement = $connection->prepare('UPDATE FormulaireDemandeProduit as formu
                    SET typeProduit = :typeProduit,
                        descriptionProduit = :descriptionProduit,
                    WHERE c.user_id = :user_id
                    ');
        $statement->bindValue('typeProduit', $formulaireDemandeProduit->getTypeProduit());
        $statement->bindValue('descriptionProduit', $formulaireDemandeProduit->getDescriptionProduit());
        $statement->bindValue('refClient', $refClient);

        $statement->executeStatement();
    }

    public function findAllFormsByClient($user_id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT *
            FROM clients as c
            INNER JOIN formulaire_demande_produit  as FD
            ON c.id = FD.ref_client_id
            WHERE c.id = :user_id
            ';
            $params = ['user_id' => $user_id]; // recupère la valeur de l'url

        $resultSet = $conn->executeQuery($sql,$params);

        return $resultSet->fetchAllAssociative();// returns un tableau de tableau SANS objet
    }




    //    /**
    //     * @return FormulaireDemandeProduit[] Returns an array of FormulaireDemandeProduit objects
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

    //    public function findOneBySomeField($value): ?FormulaireDemandeProduit
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
