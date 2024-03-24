<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240112193035 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adresse_facture (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, commande_id INT DEFAULT NULL, nom_complet VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, code_postal INT NOT NULL, ville VARCHAR(255) NOT NULL, pays VARCHAR(255) NOT NULL, INDEX IDX_5098DB8F19EB6921 (client_id), UNIQUE INDEX UNIQ_5098DB8F82EA2E54 (commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adresse_facture ADD CONSTRAINT FK_5098DB8F19EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE adresse_facture ADD CONSTRAINT FK_5098DB8F82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adresse_facture DROP FOREIGN KEY FK_5098DB8F19EB6921');
        $this->addSql('ALTER TABLE adresse_facture DROP FOREIGN KEY FK_5098DB8F82EA2E54');
        $this->addSql('DROP TABLE adresse_facture');
    }
}
