<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230607140955 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE doctor_specialization (doctor_id INT NOT NULL, specialization_id INT NOT NULL, PRIMARY KEY(doctor_id, specialization_id))');
        $this->addSql('CREATE INDEX IDX_1187285D87F4FB17 ON doctor_specialization (doctor_id)');
        $this->addSql('CREATE INDEX IDX_1187285DFA846217 ON doctor_specialization (specialization_id)');
        $this->addSql('ALTER TABLE doctor_specialization ADD CONSTRAINT FK_1187285D87F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE doctor_specialization ADD CONSTRAINT FK_1187285DFA846217 FOREIGN KEY (specialization_id) REFERENCES specialization (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE doctor DROP CONSTRAINT fk_1fc0f36a6458bc80');
        $this->addSql('DROP INDEX idx_1fc0f36a6458bc80');
        $this->addSql('ALTER TABLE doctor DROP specializations_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE doctor_specialization DROP CONSTRAINT FK_1187285D87F4FB17');
        $this->addSql('ALTER TABLE doctor_specialization DROP CONSTRAINT FK_1187285DFA846217');
        $this->addSql('DROP TABLE doctor_specialization');
        $this->addSql('ALTER TABLE doctor ADD specializations_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE doctor ADD CONSTRAINT fk_1fc0f36a6458bc80 FOREIGN KEY (specializations_id) REFERENCES specialization (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_1fc0f36a6458bc80 ON doctor (specializations_id)');
    }
}
