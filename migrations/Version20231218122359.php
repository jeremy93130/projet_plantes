<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231218122359 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE images (id INT AUTO_INCREMENT NOT NULL, plante_id INT DEFAULT NULL, image_name VARCHAR(255) NOT NULL, INDEX IDX_E01FBE6A177B16E8 (plante_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A177B16E8 FOREIGN KEY (plante_id) REFERENCES plantes (id)');
        $this->addSql('ALTER TABLE adresse ADD client_id INT NOT NULL, ADD nom_complet VARCHAR(255) NOT NULL, DROP nom, DROP prenom, CHANGE instruction_livraison instruction_livraison LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE adresse ADD CONSTRAINT FK_C35F081619EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_C35F081619EB6921 ON adresse (client_id)');
        $this->addSql('ALTER TABLE commande DROP adresse_livraison, DROP ville, DROP code_postal, DROP pays');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A177B16E8');
        $this->addSql('DROP TABLE images');
        $this->addSql('ALTER TABLE adresse DROP FOREIGN KEY FK_C35F081619EB6921');
        $this->addSql('DROP INDEX IDX_C35F081619EB6921 ON adresse');
        $this->addSql('ALTER TABLE adresse ADD prenom VARCHAR(255) NOT NULL, DROP client_id, CHANGE instruction_livraison instruction_livraison LONGTEXT DEFAULT NULL, CHANGE nom_complet nom VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE commande ADD adresse_livraison VARCHAR(255) NOT NULL, ADD ville VARCHAR(255) NOT NULL, ADD code_postal INT NOT NULL, ADD pays VARCHAR(255) NOT NULL');
    }
}
