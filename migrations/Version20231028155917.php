<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231028155917 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vendeurs DROP FOREIGN KEY FK_2180DE330EF60A');
        $this->addSql('DROP INDEX UNIQ_2180DE330EF60A ON vendeurs');
        $this->addSql('ALTER TABLE vendeurs CHANGE user_id_vendeur vendeur_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE vendeurs ADD CONSTRAINT FK_2180DE37DF81F10 FOREIGN KEY (vendeur_user_id) REFERENCES user (user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2180DE37DF81F10 ON vendeurs (vendeur_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vendeurs DROP FOREIGN KEY FK_2180DE37DF81F10');
        $this->addSql('DROP INDEX UNIQ_2180DE37DF81F10 ON vendeurs');
        $this->addSql('ALTER TABLE vendeurs CHANGE vendeur_user_id user_id_vendeur INT NOT NULL');
        $this->addSql('ALTER TABLE vendeurs ADD CONSTRAINT FK_2180DE330EF60A FOREIGN KEY (user_id_vendeur) REFERENCES user (user_id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2180DE330EF60A ON vendeurs (user_id_vendeur)');
    }
}
