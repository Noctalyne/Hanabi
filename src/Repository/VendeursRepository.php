<?php

namespace App\Repository;

use App\Entity\Vendeurs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vendeurs>
 *
 * @method Vendeurs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vendeurs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vendeurs[]    findAll()
 * @method Vendeurs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VendeursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vendeurs::class);
    }


    public function findUser($user_id) // retourne un objet client
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT * 
            FROM `Vendeurs` as v
            INNER JOIN `user` as u
            ON u.user_id =  v.vendeur_user_id 
            WHERE u.user_id = :user_id
            ';
        $params = ['user_id' => $user_id]; // recupère la valeur de l'url
        $resultSet = $conn->executeQuery($sql, $params);

        $user = $resultSet->fetchAssociative();// returns un tableau de tableau SANS objet

        $nom = $user['nom'];
        $prenom = $user['prenom'];

        $vendeur = new Vendeurs(); // instancie un nouveaux client

        // attribue les donné récupéré en tableau au donné qui correspondent
        $vendeur->setNom($nom); 
        $vendeur->setPrenom($prenom);

        // $client->setNom($user['nom']);
       
        return $vendeur; // retourne un objet d instance clients
    }

    public function findAllWithUser()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT * 
            FROM `Vendeurs` as v
            INNER JOIN `user` as u
            ON u.user_id =  v.vendeur_user_id 
            ';

        $resultSet = $conn->executeQuery($sql);

        return $resultSet->fetchAllAssociative();// returns un tableau de tableau SANS objet
    }


        // force la maj des éléments --> evite les doublons
        public function vendeurUpdate(Vendeurs $vendeurs, int $user_id){
            // Obtient la connexion à la base de données
            $connection = $this->getEntityManager()->getConnection();
    
            // Exécute la requête SQL -> $statement représente une action de requête préparé
            $statement = $connection->prepare('UPDATE vendeurs as v
                SET nom = :nom,
                    prenom = :prenom
                WHERE v.vendeur_user_id = :user_id
                ');
            // insérer les données en bdd tous en évitant les injections avec bindValue
            $statement->bindValue('nom', $vendeurs->getNom()); 
            $statement->bindValue('prenom', $vendeurs->getPrenom());
            $statement->bindValue('user_id', $user_id);
    
            $statement->executeStatement();
        }



//    /**
//     * @return Vendeurs[] Returns an array of Vendeurs objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Vendeurs
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
