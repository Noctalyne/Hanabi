<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231107201643 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commandes (id INT AUTO_INCREMENT NOT NULL, date_commande DATETIME NOT NULL, liste_produits JSON NOT NULL, prix_total NUMERIC(6, 2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commandes_clients (commandes_id INT NOT NULL, clients_id INT NOT NULL, INDEX IDX_C665A6248BF5C2E6 (commandes_id), INDEX IDX_C665A624AB014612 (clients_id), PRIMARY KEY(commandes_id, clients_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, id_client_id INT DEFAULT NULL, liste_produits JSON DEFAULT NULL, prix_total NUMERIC(6, 2) DEFAULT NULL, UNIQUE INDEX UNIQ_24CC0DF299DED506 (id_client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commandes_clients ADD CONSTRAINT FK_C665A6248BF5C2E6 FOREIGN KEY (commandes_id) REFERENCES commandes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commandes_clients ADD CONSTRAINT FK_C665A624AB014612 FOREIGN KEY (clients_id) REFERENCES clients (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF299DED506 FOREIGN KEY (id_client_id) REFERENCES clients (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commandes_clients DROP FOREIGN KEY FK_C665A6248BF5C2E6');
        $this->addSql('ALTER TABLE commandes_clients DROP FOREIGN KEY FK_C665A624AB014612');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF299DED506');
        $this->addSql('DROP TABLE commandes');
        $this->addSql('DROP TABLE commandes_clients');
        $this->addSql('DROP TABLE panier');
    }
}
