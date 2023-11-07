<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231107202923 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adresses (id INT AUTO_INCREMENT NOT NULL, id_client_adresses_id INT DEFAULT NULL, num_adrss VARCHAR(4) NOT NULL, rue_adrss VARCHAR(50) NOT NULL, complement_adrss VARCHAR(30) DEFAULT NULL, ville_adrss VARCHAR(30) NOT NULL, code_postal_adrss VARCHAR(8) NOT NULL, pays_adrss VARCHAR(30) NOT NULL, INDEX IDX_EF192552788D0B2 (id_client_adresses_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier_produits (panier_id INT NOT NULL, produits_id INT NOT NULL, INDEX IDX_2468D6FEF77D927C (panier_id), INDEX IDX_2468D6FECD11A2CF (produits_id), PRIMARY KEY(panier_id, produits_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adresses ADD CONSTRAINT FK_EF192552788D0B2 FOREIGN KEY (id_client_adresses_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE panier_produits ADD CONSTRAINT FK_2468D6FEF77D927C FOREIGN KEY (panier_id) REFERENCES panier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier_produits ADD CONSTRAINT FK_2468D6FECD11A2CF FOREIGN KEY (produits_id) REFERENCES produits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier DROP liste_produits');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adresses DROP FOREIGN KEY FK_EF192552788D0B2');
        $this->addSql('ALTER TABLE panier_produits DROP FOREIGN KEY FK_2468D6FEF77D927C');
        $this->addSql('ALTER TABLE panier_produits DROP FOREIGN KEY FK_2468D6FECD11A2CF');
        $this->addSql('DROP TABLE adresses');
        $this->addSql('DROP TABLE panier_produits');
        $this->addSql('ALTER TABLE panier ADD liste_produits JSON DEFAULT NULL');
    }
}
