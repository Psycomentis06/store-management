<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220524193640 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE inventory_item (id INT AUTO_INCREMENT NOT NULL, inventory_id INT DEFAULT NULL, stock INT NOT NULL, INDEX IDX_55BDEA309EEA759 (inventory_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inventory_item_product (inventory_item_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_D3B80FE4536BF4A2 (inventory_item_id), INDEX IDX_D3B80FE44584665A (product_id), PRIMARY KEY(inventory_item_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE inventory_item ADD CONSTRAINT FK_55BDEA309EEA759 FOREIGN KEY (inventory_id) REFERENCES inventory (id)');
        $this->addSql('ALTER TABLE inventory_item_product ADD CONSTRAINT FK_D3B80FE4536BF4A2 FOREIGN KEY (inventory_item_id) REFERENCES inventory_item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inventory_item_product ADD CONSTRAINT FK_D3B80FE44584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inventory ADD store_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE inventory ADD CONSTRAINT FK_B12D4A36B092A811 FOREIGN KEY (store_id) REFERENCES store (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B12D4A36B092A811 ON inventory (store_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inventory_item_product DROP FOREIGN KEY FK_D3B80FE4536BF4A2');
        $this->addSql('DROP TABLE inventory_item');
        $this->addSql('DROP TABLE inventory_item_product');
        $this->addSql('ALTER TABLE inventory DROP FOREIGN KEY FK_B12D4A36B092A811');
        $this->addSql('DROP INDEX UNIQ_B12D4A36B092A811 ON inventory');
        $this->addSql('ALTER TABLE inventory DROP store_id');
    }
}
