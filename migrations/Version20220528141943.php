<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220528141943 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE schedule (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE schedule_work_event (schedule_id INT NOT NULL, work_event_id INT NOT NULL, INDEX IDX_439F06CAA40BC2D5 (schedule_id), INDEX IDX_439F06CAA44262D7 (work_event_id), PRIMARY KEY(schedule_id, work_event_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_event (id INT AUTO_INCREMENT NOT NULL, from_date DATE NOT NULL, to_date DATE NOT NULL, type VARCHAR(30) NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_session (id INT AUTO_INCREMENT NOT NULL, schedule_id INT DEFAULT NULL, from_time TIME NOT NULL, to_time TIME NOT NULL, days LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_58F6DF6A40BC2D5 (schedule_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_session_user (work_session_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_499084897A5C410C (work_session_id), INDEX IDX_49908489A76ED395 (user_id), PRIMARY KEY(work_session_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE schedule_work_event ADD CONSTRAINT FK_439F06CAA40BC2D5 FOREIGN KEY (schedule_id) REFERENCES schedule (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE schedule_work_event ADD CONSTRAINT FK_439F06CAA44262D7 FOREIGN KEY (work_event_id) REFERENCES work_event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work_session ADD CONSTRAINT FK_58F6DF6A40BC2D5 FOREIGN KEY (schedule_id) REFERENCES schedule (id)');
        $this->addSql('ALTER TABLE work_session_user ADD CONSTRAINT FK_499084897A5C410C FOREIGN KEY (work_session_id) REFERENCES work_session (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work_session_user ADD CONSTRAINT FK_49908489A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE store ADD schedule_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE store ADD CONSTRAINT FK_FF575877A40BC2D5 FOREIGN KEY (schedule_id) REFERENCES schedule (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FF575877A40BC2D5 ON store (schedule_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE schedule_work_event DROP FOREIGN KEY FK_439F06CAA40BC2D5');
        $this->addSql('ALTER TABLE store DROP FOREIGN KEY FK_FF575877A40BC2D5');
        $this->addSql('ALTER TABLE work_session DROP FOREIGN KEY FK_58F6DF6A40BC2D5');
        $this->addSql('ALTER TABLE schedule_work_event DROP FOREIGN KEY FK_439F06CAA44262D7');
        $this->addSql('ALTER TABLE work_session_user DROP FOREIGN KEY FK_499084897A5C410C');
        $this->addSql('DROP TABLE schedule');
        $this->addSql('DROP TABLE schedule_work_event');
        $this->addSql('DROP TABLE work_event');
        $this->addSql('DROP TABLE work_session');
        $this->addSql('DROP TABLE work_session_user');
        $this->addSql('DROP INDEX UNIQ_FF575877A40BC2D5 ON store');
        $this->addSql('ALTER TABLE store DROP schedule_id');
    }
}
