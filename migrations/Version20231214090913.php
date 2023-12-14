<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231214090913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande_plantes DROP FOREIGN KEY FK_55C13F149B7AC79');
        $this->addSql('ALTER TABLE commande_plantes DROP FOREIGN KEY FK_55C13F182EA2E54');
        $this->addSql('DROP TABLE commande_plantes');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande_plantes (commande_id INT NOT NULL, plantes_id INT NOT NULL, INDEX IDX_55C13F182EA2E54 (commande_id), INDEX IDX_55C13F149B7AC79 (plantes_id), PRIMARY KEY(commande_id, plantes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commande_plantes ADD CONSTRAINT FK_55C13F149B7AC79 FOREIGN KEY (plantes_id) REFERENCES plantes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande_plantes ADD CONSTRAINT FK_55C13F182EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) ON DELETE CASCADE');
    }
}
