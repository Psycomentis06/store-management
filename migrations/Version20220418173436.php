<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220418173436 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_2C42079E04992AA ON route');
        $this->addSql('ALTER TABLE route ADD permission_id INT DEFAULT NULL, DROP permission');
        $this->addSql('ALTER TABLE route ADD CONSTRAINT FK_2C42079FED90CCA FOREIGN KEY (permission_id) REFERENCES permission (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2C42079FED90CCA ON route (permission_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE route DROP FOREIGN KEY FK_2C42079FED90CCA');
        $this->addSql('DROP INDEX UNIQ_2C42079FED90CCA ON route');
        $this->addSql('ALTER TABLE route ADD permission VARCHAR(255) NOT NULL, DROP permission_id');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2C42079E04992AA ON route (permission)');
    }
}
