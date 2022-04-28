<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220428165035 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE currency (id INT AUTO_INCREMENT NOT NULL, currency VARCHAR(3) NOT NULL, currency_full_name VARCHAR(20) NOT NULL, symbol VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(30) NOT NULL, last_name VARCHAR(30) NOT NULL, phone_number VARCHAR(30) NOT NULL, email VARCHAR(30) NOT NULL, address LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', UNIQUE INDEX UNIQ_81398E096B01BC5B (phone_number), UNIQUE INDEX UNIQ_81398E09E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE digital_purchase (id INT AUTO_INCREMENT NOT NULL, store_id INT DEFAULT NULL, consumer_id INT DEFAULT NULL, date DATE NOT NULL, price INT NOT NULL, expires_on DATE NOT NULL, quantity SMALLINT NOT NULL, credentials LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_81E2BAFEB092A811 (store_id), INDEX IDX_81E2BAFE37FDBD6D (consumer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inventory (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, sku VARCHAR(20) NOT NULL, discount SMALLINT DEFAULT NULL, guarantee DATE DEFAULT NULL, properties LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', digital TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_upload (product_id INT NOT NULL, upload_id INT NOT NULL, INDEX IDX_75C1A7BB4584665A (product_id), INDEX IDX_75C1A7BBCCCFBA31 (upload_id), PRIMARY KEY(product_id, upload_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_price (id INT AUTO_INCREMENT NOT NULL, currency_id INT DEFAULT NULL, product_id INT DEFAULT NULL, price INT NOT NULL, from_day DATE NOT NULL, INDEX IDX_6B94598538248176 (currency_id), INDEX IDX_6B9459854584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE store (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(20) NOT NULL, address LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE upload (id INT AUTO_INCREMENT NOT NULL, original_name VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(10) NOT NULL, mime_type VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE digital_purchase ADD CONSTRAINT FK_81E2BAFEB092A811 FOREIGN KEY (store_id) REFERENCES store (id)');
        $this->addSql('ALTER TABLE digital_purchase ADD CONSTRAINT FK_81E2BAFE37FDBD6D FOREIGN KEY (consumer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE product_upload ADD CONSTRAINT FK_75C1A7BB4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_upload ADD CONSTRAINT FK_75C1A7BBCCCFBA31 FOREIGN KEY (upload_id) REFERENCES upload (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_price ADD CONSTRAINT FK_6B94598538248176 FOREIGN KEY (currency_id) REFERENCES currency (id)');
        $this->addSql('ALTER TABLE product_price ADD CONSTRAINT FK_6B9459854584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE user ADD digital_purchase_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64989355E0D FOREIGN KEY (digital_purchase_id) REFERENCES digital_purchase (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64989355E0D ON user (digital_purchase_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_price DROP FOREIGN KEY FK_6B94598538248176');
        $this->addSql('ALTER TABLE digital_purchase DROP FOREIGN KEY FK_81E2BAFE37FDBD6D');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64989355E0D');
        $this->addSql('ALTER TABLE product_upload DROP FOREIGN KEY FK_75C1A7BB4584665A');
        $this->addSql('ALTER TABLE product_price DROP FOREIGN KEY FK_6B9459854584665A');
        $this->addSql('ALTER TABLE digital_purchase DROP FOREIGN KEY FK_81E2BAFEB092A811');
        $this->addSql('ALTER TABLE product_upload DROP FOREIGN KEY FK_75C1A7BBCCCFBA31');
        $this->addSql('DROP TABLE currency');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE digital_purchase');
        $this->addSql('DROP TABLE inventory');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_upload');
        $this->addSql('DROP TABLE product_price');
        $this->addSql('DROP TABLE store');
        $this->addSql('DROP TABLE upload');
        $this->addSql('DROP INDEX IDX_8D93D64989355E0D ON user');
        $this->addSql('ALTER TABLE user DROP digital_purchase_id');
    }
}
