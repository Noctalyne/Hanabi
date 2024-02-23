<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240219211625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adresses (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, num_adrss VARCHAR(4) NOT NULL, rue_adrss VARCHAR(50) NOT NULL, complement_adrss VARCHAR(30) DEFAULT NULL, ville_adrss VARCHAR(30) NOT NULL, code_postal_adrss VARCHAR(8) NOT NULL, pays_adrss VARCHAR(30) NOT NULL, INDEX IDX_EF192552A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE banniere (id INT AUTO_INCREMENT NOT NULL, nom_banniere VARCHAR(100) NOT NULL, premiere_image VARCHAR(100) NOT NULL, deuxieme_image VARCHAR(100) NOT NULL, troisieme_image VARCHAR(100) NOT NULL, activated TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commandes (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, date_commande DATETIME NOT NULL, liste_produits JSON NOT NULL, prix_total NUMERIC(6, 2) NOT NULL, transporteur VARCHAR(255) NOT NULL, prix_transport NUMERIC(4, 2) NOT NULL, succes_paiement TINYINT(1) NOT NULL, id_transaction VARCHAR(255) NOT NULL, INDEX IDX_35D4282CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formulaire_demande_produit (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, type_produit VARCHAR(20) NOT NULL, autres_types VARCHAR(30) DEFAULT NULL, description_produit VARCHAR(300) NOT NULL, date_envoie_form DATETIME NOT NULL, date_reponse_form DATETIME DEFAULT NULL, reponse_demande VARCHAR(10) DEFAULT NULL, INDEX IDX_1862BEBCA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, prix_total NUMERIC(6, 2) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier_produits (panier_id INT NOT NULL, produits_id INT NOT NULL, INDEX IDX_2468D6FEF77D927C (panier_id), INDEX IDX_2468D6FECD11A2CF (produits_id), PRIMARY KEY(panier_id, produits_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produits (id INT AUTO_INCREMENT NOT NULL, nom_produit VARCHAR(50) NOT NULL, description_produit LONGTEXT NOT NULL, img_produit VARCHAR(100) NOT NULL, prix_produit NUMERIC(6, 2) NOT NULL, quant_stock INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, panier_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, username VARCHAR(30) NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(30) DEFAULT NULL, prenom VARCHAR(30) DEFAULT NULL, telephone VARCHAR(10) DEFAULT NULL, account_activate TINYINT(1) NOT NULL, client_activate TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649F77D927C (panier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adresses ADD CONSTRAINT FK_EF192552A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE formulaire_demande_produit ADD CONSTRAINT FK_1862BEBCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE panier_produits ADD CONSTRAINT FK_2468D6FEF77D927C FOREIGN KEY (panier_id) REFERENCES panier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier_produits ADD CONSTRAINT FK_2468D6FECD11A2CF FOREIGN KEY (produits_id) REFERENCES produits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649F77D927C FOREIGN KEY (panier_id) REFERENCES panier (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adresses DROP FOREIGN KEY FK_EF192552A76ED395');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282CA76ED395');
        $this->addSql('ALTER TABLE formulaire_demande_produit DROP FOREIGN KEY FK_1862BEBCA76ED395');
        $this->addSql('ALTER TABLE panier_produits DROP FOREIGN KEY FK_2468D6FEF77D927C');
        $this->addSql('ALTER TABLE panier_produits DROP FOREIGN KEY FK_2468D6FECD11A2CF');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649F77D927C');
        $this->addSql('DROP TABLE adresses');
        $this->addSql('DROP TABLE banniere');
        $this->addSql('DROP TABLE commandes');
        $this->addSql('DROP TABLE formulaire_demande_produit');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE panier_produits');
        $this->addSql('DROP TABLE produits');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
