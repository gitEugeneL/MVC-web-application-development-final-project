<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230604122632 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE doctor_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE manager_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE medical_record_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE office_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE patient_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE specialization_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE visit_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE doctor (id INT NOT NULL, specializations_id INT DEFAULT NULL, auth_user_id INT DEFAULT NULL, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(100) NOT NULL, phone VARCHAR(12) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1FC0F36A6458BC80 ON doctor (specializations_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1FC0F36AE94AF366 ON doctor (auth_user_id)');
        $this->addSql('CREATE TABLE manager (id INT NOT NULL, auth_user_id INT DEFAULT NULL, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(100) NOT NULL, phone VARCHAR(12) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FA2425B9E94AF366 ON manager (auth_user_id)');
        $this->addSql('CREATE TABLE medical_record (id INT NOT NULL, doctor_id INT DEFAULT NULL, patient_id INT DEFAULT NULL, visit_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F06A283E87F4FB17 ON medical_record (doctor_id)');
        $this->addSql('CREATE INDEX IDX_F06A283E6B899279 ON medical_record (patient_id)');
        $this->addSql('CREATE INDEX IDX_F06A283E75FA0FF2 ON medical_record (visit_id)');
        $this->addSql('CREATE TABLE office (id INT NOT NULL, name VARCHAR(100) NOT NULL, number INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE patient (id INT NOT NULL, auth_user_id INT DEFAULT NULL, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(100) NOT NULL, date_of_birth DATE NOT NULL, pesel INT NOT NULL, phone VARCHAR(12) NOT NULL, insurance VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1ADAD7EBE94AF366 ON patient (auth_user_id)');
        $this->addSql('COMMENT ON COLUMN patient.date_of_birth IS \'(DC2Type:date_immutable)\'');
        $this->addSql('CREATE TABLE specialization (id INT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE TABLE visit (id INT NOT NULL, patient_id INT DEFAULT NULL, doctor_id INT DEFAULT NULL, date DATE NOT NULL, time TIME(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_437EE9396B899279 ON visit (patient_id)');
        $this->addSql('CREATE INDEX IDX_437EE93987F4FB17 ON visit (doctor_id)');
        $this->addSql('ALTER TABLE doctor ADD CONSTRAINT FK_1FC0F36A6458BC80 FOREIGN KEY (specializations_id) REFERENCES specialization (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE doctor ADD CONSTRAINT FK_1FC0F36AE94AF366 FOREIGN KEY (auth_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE manager ADD CONSTRAINT FK_FA2425B9E94AF366 FOREIGN KEY (auth_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE medical_record ADD CONSTRAINT FK_F06A283E87F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctor (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE medical_record ADD CONSTRAINT FK_F06A283E6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE medical_record ADD CONSTRAINT FK_F06A283E75FA0FF2 FOREIGN KEY (visit_id) REFERENCES visit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EBE94AF366 FOREIGN KEY (auth_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE visit ADD CONSTRAINT FK_437EE9396B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE visit ADD CONSTRAINT FK_437EE93987F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctor (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE doctor_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE manager_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE medical_record_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE office_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE patient_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE specialization_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE visit_id_seq CASCADE');
        $this->addSql('ALTER TABLE doctor DROP CONSTRAINT FK_1FC0F36A6458BC80');
        $this->addSql('ALTER TABLE doctor DROP CONSTRAINT FK_1FC0F36AE94AF366');
        $this->addSql('ALTER TABLE manager DROP CONSTRAINT FK_FA2425B9E94AF366');
        $this->addSql('ALTER TABLE medical_record DROP CONSTRAINT FK_F06A283E87F4FB17');
        $this->addSql('ALTER TABLE medical_record DROP CONSTRAINT FK_F06A283E6B899279');
        $this->addSql('ALTER TABLE medical_record DROP CONSTRAINT FK_F06A283E75FA0FF2');
        $this->addSql('ALTER TABLE patient DROP CONSTRAINT FK_1ADAD7EBE94AF366');
        $this->addSql('ALTER TABLE visit DROP CONSTRAINT FK_437EE9396B899279');
        $this->addSql('ALTER TABLE visit DROP CONSTRAINT FK_437EE93987F4FB17');
        $this->addSql('DROP TABLE doctor');
        $this->addSql('DROP TABLE manager');
        $this->addSql('DROP TABLE medical_record');
        $this->addSql('DROP TABLE office');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE specialization');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE visit');
    }
}
