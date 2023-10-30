<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231028154817 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vendeurs DROP FOREIGN KEY FK_2180DE3A76ED395');
        $this->addSql('DROP INDEX UNIQ_2180DE3A76ED395 ON vendeurs');
        $this->addSql('ALTER TABLE vendeurs CHANGE user_id userVendeur INT NOT NULL');
        $this->addSql('ALTER TABLE vendeurs ADD CONSTRAINT FK_2180DE329285CB4 FOREIGN KEY (userVendeur) REFERENCES user (user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2180DE329285CB4 ON vendeurs (userVendeur)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vendeurs DROP FOREIGN KEY FK_2180DE329285CB4');
        $this->addSql('DROP INDEX UNIQ_2180DE329285CB4 ON vendeurs');
        $this->addSql('ALTER TABLE vendeurs CHANGE userVendeur user_id INT NOT NULL');
        $this->addSql('ALTER TABLE vendeurs ADD CONSTRAINT FK_2180DE3A76ED395 FOREIGN KEY (user_id) REFERENCES user (user_id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2180DE3A76ED395 ON vendeurs (user_id)');
    }
}
