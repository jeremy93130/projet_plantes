<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231207140042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE details_commandes (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, date_commande DATE NOT NULL, etat_commande ENUM(\'En Attente\',\'Confirmée\', \'En Préparation\', \'Expédiée\'), adresse_livraison VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, code_postal INT NOT NULL, pays VARCHAR(255) NOT NULL, quantite INT NOT NULL, prix_unitaire INT NOT NULL, total INT NOT NULL, INDEX IDX_4FD424F719EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE details_commandes_plantes (details_commandes_id INT NOT NULL, plantes_id INT NOT NULL, INDEX IDX_1AFF1151A69C5741 (details_commandes_id), INDEX IDX_1AFF115149B7AC79 (plantes_id), PRIMARY KEY(details_commandes_id, plantes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE details_commandes ADD CONSTRAINT FK_4FD424F719EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE details_commandes_plantes ADD CONSTRAINT FK_1AFF1151A69C5741 FOREIGN KEY (details_commandes_id) REFERENCES details_commandes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE details_commandes_plantes ADD CONSTRAINT FK_1AFF115149B7AC79 FOREIGN KEY (plantes_id) REFERENCES plantes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282CDC2902E0');
        $this->addSql('ALTER TABLE commandes_plantes DROP FOREIGN KEY FK_354AE90149B7AC79');
        $this->addSql('ALTER TABLE commandes_plantes DROP FOREIGN KEY FK_354AE9018BF5C2E6');
        $this->addSql('ALTER TABLE details_commande DROP FOREIGN KEY FK_4BCD5F6177B16E8');
        $this->addSql('ALTER TABLE details_commande DROP FOREIGN KEY FK_4BCD5F682EA2E54');
        $this->addSql('DROP TABLE commandes');
        $this->addSql('DROP TABLE commandes_plantes');
        $this->addSql('DROP TABLE details_commande');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commandes (id INT AUTO_INCREMENT NOT NULL, client_id_id INT NOT NULL, date_commande DATE NOT NULL, etat_commande JSON NOT NULL COMMENT \'(DC2Type:json)\', adresse_livraison VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ville VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, code_postal INT NOT NULL, pays VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_35D4282CDC2902E0 (client_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE commandes_plantes (commandes_id INT NOT NULL, plantes_id INT NOT NULL, INDEX IDX_354AE9018BF5C2E6 (commandes_id), INDEX IDX_354AE90149B7AC79 (plantes_id), PRIMARY KEY(commandes_id, plantes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE details_commande (id INT AUTO_INCREMENT NOT NULL, plante_id INT DEFAULT NULL, commande_id INT DEFAULT NULL, quantite INT NOT NULL, prix_unitaire DOUBLE PRECISION NOT NULL, sous_total DOUBLE PRECISION NOT NULL, total DOUBLE PRECISION NOT NULL, INDEX IDX_4BCD5F6177B16E8 (plante_id), INDEX IDX_4BCD5F682EA2E54 (commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282CDC2902E0 FOREIGN KEY (client_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commandes_plantes ADD CONSTRAINT FK_354AE90149B7AC79 FOREIGN KEY (plantes_id) REFERENCES plantes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commandes_plantes ADD CONSTRAINT FK_354AE9018BF5C2E6 FOREIGN KEY (commandes_id) REFERENCES commandes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE details_commande ADD CONSTRAINT FK_4BCD5F6177B16E8 FOREIGN KEY (plante_id) REFERENCES plantes (id)');
        $this->addSql('ALTER TABLE details_commande ADD CONSTRAINT FK_4BCD5F682EA2E54 FOREIGN KEY (commande_id) REFERENCES commandes (id)');
        $this->addSql('ALTER TABLE details_commandes DROP FOREIGN KEY FK_4FD424F719EB6921');
        $this->addSql('ALTER TABLE details_commandes_plantes DROP FOREIGN KEY FK_1AFF1151A69C5741');
        $this->addSql('ALTER TABLE details_commandes_plantes DROP FOREIGN KEY FK_1AFF115149B7AC79');
        $this->addSql('DROP TABLE details_commandes');
        $this->addSql('DROP TABLE details_commandes_plantes');
    }
}
