<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220526135421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE digital_purchase ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE digital_purchase ADD CONSTRAINT FK_81E2BAFEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_81E2BAFEA76ED395 ON digital_purchase (user_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64989355E0D');
        $this->addSql('DROP INDEX IDX_8D93D64989355E0D ON user');
        $this->addSql('ALTER TABLE user DROP digital_purchase_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE digital_purchase DROP FOREIGN KEY FK_81E2BAFEA76ED395');
        $this->addSql('DROP INDEX IDX_81E2BAFEA76ED395 ON digital_purchase');
        $this->addSql('ALTER TABLE digital_purchase DROP user_id');
        $this->addSql('ALTER TABLE user ADD digital_purchase_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64989355E0D FOREIGN KEY (digital_purchase_id) REFERENCES digital_purchase (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64989355E0D ON user (digital_purchase_id)');
    }
}
