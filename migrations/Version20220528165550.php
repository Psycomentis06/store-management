<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220528165550 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inventory DROP FOREIGN KEY FK_B12D4A36B092A811');
        $this->addSql('DROP INDEX UNIQ_B12D4A36B092A811 ON inventory');
        $this->addSql('ALTER TABLE inventory DROP store_id');
        $this->addSql('ALTER TABLE store ADD inventory_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE store ADD CONSTRAINT FK_FF5758779EEA759 FOREIGN KEY (inventory_id) REFERENCES inventory (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FF5758779EEA759 ON store (inventory_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inventory ADD store_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE inventory ADD CONSTRAINT FK_B12D4A36B092A811 FOREIGN KEY (store_id) REFERENCES store (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B12D4A36B092A811 ON inventory (store_id)');
        $this->addSql('ALTER TABLE store DROP FOREIGN KEY FK_FF5758779EEA759');
        $this->addSql('DROP INDEX UNIQ_FF5758779EEA759 ON store');
        $this->addSql('ALTER TABLE store DROP inventory_id');
    }
}
