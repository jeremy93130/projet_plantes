<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231211121819 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE details_commandes CHANGE etat_commande etat_commande ENUM(\'En Attente\',\'Confirmée\', \'En Préparation\', \'Expédiée\')');
        $this->addSql('ALTER TABLE quantites ADD plante_id INT NOT NULL');
        $this->addSql('ALTER TABLE quantites ADD CONSTRAINT FK_325CF4D9177B16E8 FOREIGN KEY (plante_id) REFERENCES plantes (id)');
        $this->addSql('CREATE INDEX IDX_325CF4D9177B16E8 ON quantites (plante_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE details_commandes CHANGE etat_commande etat_commande LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE quantites DROP FOREIGN KEY FK_325CF4D9177B16E8');
        $this->addSql('DROP INDEX IDX_325CF4D9177B16E8 ON quantites');
        $this->addSql('ALTER TABLE quantites DROP plante_id');
    }
}
