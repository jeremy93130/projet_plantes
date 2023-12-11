<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231211120851 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE quantites (id INT AUTO_INCREMENT NOT NULL, commande_id INT NOT NULL, quantite INT NOT NULL, INDEX IDX_325CF4D982EA2E54 (commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE quantites ADD CONSTRAINT FK_325CF4D982EA2E54 FOREIGN KEY (commande_id) REFERENCES details_commandes (id)');
        $this->addSql('ALTER TABLE details_commandes CHANGE etat_commande etat_commande ENUM(\'En Attente\',\'Confirmée\', \'En Préparation\', \'Expédiée\')');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quantites DROP FOREIGN KEY FK_325CF4D982EA2E54');
        $this->addSql('DROP TABLE quantites');
        $this->addSql('ALTER TABLE details_commandes CHANGE etat_commande etat_commande LONGTEXT DEFAULT \'En Attente\'');
    }
}
