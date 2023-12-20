<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231220083958 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adresse ADD CONSTRAINT FK_C35F081682EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C35F081682EA2E54 ON adresse (commande_id)');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D19EB6921');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D19EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE details_commande DROP FOREIGN KEY FK_4BCD5F6177B16E8');
        $this->addSql('ALTER TABLE details_commande DROP FOREIGN KEY FK_4BCD5F682EA2E54');
        $this->addSql('ALTER TABLE details_commande ADD CONSTRAINT FK_4BCD5F6177B16E8 FOREIGN KEY (plante_id) REFERENCES plantes (id)');
        $this->addSql('ALTER TABLE details_commande ADD CONSTRAINT FK_4BCD5F682EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adresse DROP FOREIGN KEY FK_C35F081682EA2E54');
        $this->addSql('DROP INDEX UNIQ_C35F081682EA2E54 ON adresse');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D19EB6921');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D19EB6921 FOREIGN KEY (client_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE details_commande DROP FOREIGN KEY FK_4BCD5F6177B16E8');
        $this->addSql('ALTER TABLE details_commande DROP FOREIGN KEY FK_4BCD5F682EA2E54');
        $this->addSql('ALTER TABLE details_commande ADD CONSTRAINT FK_4BCD5F6177B16E8 FOREIGN KEY (plante_id) REFERENCES plantes (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE details_commande ADD CONSTRAINT FK_4BCD5F682EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
