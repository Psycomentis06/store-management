<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220409131409 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_metadata (id INT AUTO_INCREMENT NOT NULL, prefs LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', lang VARCHAR(20) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD metadata_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649DC9EE959 FOREIGN KEY (metadata_id) REFERENCES user_metadata (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649DC9EE959 ON user (metadata_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649DC9EE959');
        $this->addSql('DROP TABLE user_metadata');
        $this->addSql('DROP INDEX UNIQ_8D93D649DC9EE959 ON user');
        $this->addSql('ALTER TABLE user DROP metadata_id');
    }
}
