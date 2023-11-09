<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231109135143 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commandes_plantes (commandes_id INT NOT NULL, plantes_id INT NOT NULL, INDEX IDX_354AE9018BF5C2E6 (commandes_id), INDEX IDX_354AE90149B7AC79 (plantes_id), PRIMARY KEY(commandes_id, plantes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE details_commande (id INT AUTO_INCREMENT NOT NULL, plante_id INT DEFAULT NULL, commande_id INT DEFAULT NULL, quantite INT NOT NULL, prix_unitaire DOUBLE PRECISION NOT NULL, prix DOUBLE PRECISION NOT NULL, sous_total DOUBLE PRECISION NOT NULL, INDEX IDX_4BCD5F6177B16E8 (plante_id), INDEX IDX_4BCD5F682EA2E54 (commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commandes_plantes ADD CONSTRAINT FK_354AE9018BF5C2E6 FOREIGN KEY (commandes_id) REFERENCES commandes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commandes_plantes ADD CONSTRAINT FK_354AE90149B7AC79 FOREIGN KEY (plantes_id) REFERENCES plantes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE details_commande ADD CONSTRAINT FK_4BCD5F6177B16E8 FOREIGN KEY (plante_id) REFERENCES plantes (id)');
        $this->addSql('ALTER TABLE details_commande ADD CONSTRAINT FK_4BCD5F682EA2E54 FOREIGN KEY (commande_id) REFERENCES commandes (id)');
        $this->addSql('DROP TABLE panier');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, prix_unitaire DOUBLE PRECISION NOT NULL, quantite INT NOT NULL, prix_total DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commandes_plantes DROP FOREIGN KEY FK_354AE9018BF5C2E6');
        $this->addSql('ALTER TABLE commandes_plantes DROP FOREIGN KEY FK_354AE90149B7AC79');
        $this->addSql('ALTER TABLE details_commande DROP FOREIGN KEY FK_4BCD5F6177B16E8');
        $this->addSql('ALTER TABLE details_commande DROP FOREIGN KEY FK_4BCD5F682EA2E54');
        $this->addSql('DROP TABLE commandes_plantes');
        $this->addSql('DROP TABLE details_commande');
    }
}
