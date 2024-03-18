<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240315124836 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_adress_commande (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, adresse_id INT NOT NULL, commande_id INT NOT NULL, INDEX IDX_1D7FDE5CA76ED395 (user_id), INDEX IDX_1D7FDE5C4DE7DC5C (adresse_id), INDEX IDX_1D7FDE5C82EA2E54 (commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_adress_commande ADD CONSTRAINT FK_1D7FDE5CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_adress_commande ADD CONSTRAINT FK_1D7FDE5C4DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adresse (id)');
        $this->addSql('ALTER TABLE user_adress_commande ADD CONSTRAINT FK_1D7FDE5C82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_adress_commande DROP FOREIGN KEY FK_1D7FDE5CA76ED395');
        $this->addSql('ALTER TABLE user_adress_commande DROP FOREIGN KEY FK_1D7FDE5C4DE7DC5C');
        $this->addSql('ALTER TABLE user_adress_commande DROP FOREIGN KEY FK_1D7FDE5C82EA2E54');
        $this->addSql('DROP TABLE user_adress_commande');
    }
}
