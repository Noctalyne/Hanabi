<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231025100119 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formulaire_demande_produit ADD ref_client_id INT NOT NULL');
        $this->addSql('ALTER TABLE formulaire_demande_produit ADD CONSTRAINT FK_1862BEBC6AB16864 FOREIGN KEY (ref_client_id) REFERENCES clients (id)');
        $this->addSql('CREATE INDEX IDX_1862BEBC6AB16864 ON formulaire_demande_produit (ref_client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formulaire_demande_produit DROP FOREIGN KEY FK_1862BEBC6AB16864');
        $this->addSql('DROP INDEX IDX_1862BEBC6AB16864 ON formulaire_demande_produit');
        $this->addSql('ALTER TABLE formulaire_demande_produit DROP ref_client_id');
    }
}
