<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240315083028 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adresse_facture DROP FOREIGN KEY FK_5098DB8F19EB6921');
        $this->addSql('ALTER TABLE adresse_facture DROP FOREIGN KEY FK_5098DB8F82EA2E54');
        $this->addSql('DROP TABLE adresse_facture');
        $this->addSql('ALTER TABLE adresse ADD type VARCHAR(255) NOT NULL, CHANGE telephone telephone VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE telephone telephone VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adresse_facture (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, commande_id INT DEFAULT NULL, nom_complet VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, adresse VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, code_postal INT NOT NULL, ville VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, pays VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, telephone INT NOT NULL, UNIQUE INDEX UNIQ_5098DB8F82EA2E54 (commande_id), INDEX IDX_5098DB8F19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE adresse_facture ADD CONSTRAINT FK_5098DB8F19EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE adresse_facture ADD CONSTRAINT FK_5098DB8F82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE adresse DROP type, CHANGE telephone telephone INT NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE telephone telephone INT NOT NULL');
    }
}
