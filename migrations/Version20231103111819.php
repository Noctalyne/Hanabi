<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231103111819 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE banniere (id INT AUTO_INCREMENT NOT NULL, nom_banniere VARCHAR(100) NOT NULL, premiere_image VARCHAR(100) NOT NULL, deuxieme_image VARCHAR(100) NOT NULL, troisieme_image VARCHAR(100) NOT NULL, activated TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clients (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, nom VARCHAR(30) DEFAULT NULL, prenom VARCHAR(30) DEFAULT NULL, telephone VARCHAR(10) DEFAULT NULL, UNIQUE INDEX UNIQ_C82E74A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formulaire_demande_produit (id INT AUTO_INCREMENT NOT NULL, ref_client_id INT NOT NULL, type_produit VARCHAR(20) NOT NULL, autres_types VARCHAR(30) DEFAULT NULL, description_produit VARCHAR(300) NOT NULL, date_envoie_form DATETIME NOT NULL, date_reponse_form DATETIME DEFAULT NULL, reponse_demande VARCHAR(10) DEFAULT NULL, INDEX IDX_1862BEBC6AB16864 (ref_client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produits (id INT AUTO_INCREMENT NOT NULL, nom_produit VARCHAR(50) NOT NULL, description_produit LONGTEXT NOT NULL, img_produit VARCHAR(100) NOT NULL, prix_produit NUMERIC(6, 2) NOT NULL, quant_stock INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (user_id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, username VARCHAR(30) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vendeurs (id INT AUTO_INCREMENT NOT NULL, vendeur_user_id INT NOT NULL, nom VARCHAR(30) NOT NULL, prenom VARCHAR(30) NOT NULL, UNIQUE INDEX UNIQ_2180DE37DF81F10 (vendeur_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE clients ADD CONSTRAINT FK_C82E74A76ED395 FOREIGN KEY (user_id) REFERENCES user (user_id)');
        $this->addSql('ALTER TABLE formulaire_demande_produit ADD CONSTRAINT FK_1862BEBC6AB16864 FOREIGN KEY (ref_client_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE vendeurs ADD CONSTRAINT FK_2180DE37DF81F10 FOREIGN KEY (vendeur_user_id) REFERENCES user (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clients DROP FOREIGN KEY FK_C82E74A76ED395');
        $this->addSql('ALTER TABLE formulaire_demande_produit DROP FOREIGN KEY FK_1862BEBC6AB16864');
        $this->addSql('ALTER TABLE vendeurs DROP FOREIGN KEY FK_2180DE37DF81F10');
        $this->addSql('DROP TABLE banniere');
        $this->addSql('DROP TABLE clients');
        $this->addSql('DROP TABLE formulaire_demande_produit');
        $this->addSql('DROP TABLE produits');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE vendeurs');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
