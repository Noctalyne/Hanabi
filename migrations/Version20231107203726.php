<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231107203726 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adresses DROP FOREIGN KEY FK_EF192552788D0B2');
        $this->addSql('DROP INDEX IDX_EF192552788D0B2 ON adresses');
        $this->addSql('ALTER TABLE adresses ADD idClientAdresse INT NOT NULL, DROP id_client_adresses_id');
        $this->addSql('ALTER TABLE adresses ADD CONSTRAINT FK_EF1925526A93C194 FOREIGN KEY (idClientAdresse) REFERENCES clients (id)');
        $this->addSql('CREATE INDEX IDX_EF1925526A93C194 ON adresses (idClientAdresse)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adresses DROP FOREIGN KEY FK_EF1925526A93C194');
        $this->addSql('DROP INDEX IDX_EF1925526A93C194 ON adresses');
        $this->addSql('ALTER TABLE adresses ADD id_client_adresses_id INT DEFAULT NULL, DROP idClientAdresse');
        $this->addSql('ALTER TABLE adresses ADD CONSTRAINT FK_EF192552788D0B2 FOREIGN KEY (id_client_adresses_id) REFERENCES clients (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_EF192552788D0B2 ON adresses (id_client_adresses_id)');
    }
}
