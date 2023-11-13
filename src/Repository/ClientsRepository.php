<?php

namespace App\Repository;

use App\Entity\Clients;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Clients>
 *
 * @method Clients|null find($id, $lockMode = null, $lockVersion = null)
 * @method Clients|null findOneBy(array $criteria, array $orderBy = null)
 * @method Clients[]    findAll()
 * @method Clients[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Clients::class);
    }

    // public function renvoieObjetClient($paramss) {
    //     $client = new Clients();
    //     $client->setUser($paramss);
        // $client->setId($idClient);
        // $client->setUsername($test['username']) ;
        // $client->setEmail($test['email']) ;
        // $client->setPassword($test['password']) ;
        // $client->setNomClient($test['nomClient']);
        // $client->setPrenomClient($test['prenomClient']);
        // $client->setTelephone($test['telephone']); // Accéder à l'attribut ID
    //     return $client;
    // }


    public function findClient($user_id) // retourne un objet client
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT * 
            FROM `clients` as c
            INNER JOIN `user` as u
            ON u.user_id=  c.user_id 
            WHERE u.user_id = :user_id
            ';
        $params = ['user_id' => $user_id]; // recupère la valeur de l'url
        $resultSet = $conn->executeQuery($sql, $params);

        $user = $resultSet->fetchAssociative();// returns un tableau de tableau SANS objet

        // $client= $client->renvoieObjetClient($user);
        $nom = $user['nom'];
        $prenom = $user['prenom'];
        $telephone = $user['telephone'];

        $client = new Clients(); // instancie un nouveaux client

        // attribue les donné récupéré en tableau au donné qui correspondent
        $client->setNom($nom); 
        $client->setPrenom($prenom);
        $client->setTelephone($telephone); 

        // $client->setNom($user['nom']);
       
        return $client; // retourne un objet d instance clients
    }

    public function findAllWithUser()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT *
            FROM clients c
            INNER JOIN user u 
            ON u.user_id = c.user_id
            ';

        $resultSet = $conn->executeQuery($sql);

        return $resultSet->fetchAllAssociative();// returns un tableau de tableau SANS objet
    }

    // exécute la maj des éléments sans recharger les infos client 
    //     --> evite les doublons/erreur de foreignKey
    public function UpdateInfos(Clients $client, int $user_id){
        // Obtient la connexion à la base de données
        $connection = $this->getEntityManager()->getConnection();

        // Exécute la requête SQL -> $statement représente une action de requête préparé
        $statement = $connection->prepare('UPDATE clients as c
            SET nom = :nom,
                prenom = :prenom,
                telephone = :telephone
            WHERE c.user_id = :user_id
            ');
        // insérer les données en bdd tous en évitant les injections avec bindValue
        $statement->bindValue('nom', $client->getNom()); 
        $statement->bindValue('prenom', $client->getPrenom());
        $statement->bindValue('telephone', $client->getTelephone());
        $statement->bindValue('user_id', $user_id);

        $statement->executeStatement();
    }


    // public function findAdresses (Clients $client, int $user_id){

    //     $client= $this ->find($user_id);
    //     $client->getAdresses();

    //     return $client;
  
    // }



    // public function updateClient () {

    //     $conn = $this->getEntityManager()->getConnection();
    //     $sql = '';  
        
    //     // $params = ['user_id'=> $this->getUser()->getId()];
    // }



//    /**
//     * @return Clients[] Returns an array of Clients objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Clients
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
