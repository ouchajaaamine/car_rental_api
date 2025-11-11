<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251111152703 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE car (id SERIAL NOT NULL, model VARCHAR(100) NOT NULL, brand VARCHAR(100) NOT NULL, inventory INT NOT NULL, daily_fee NUMERIC(12, 2) NOT NULL, seats INT NOT NULL, transmission VARCHAR(255) NOT NULL, fuel_type VARCHAR(255) NOT NULL, is_deleted BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_773DE69DD79572D9 ON car (model)');
        $this->addSql('CREATE TABLE reservation (id SERIAL NOT NULL, car_id INT NOT NULL, user_id INT DEFAULT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, actual_return_date DATE DEFAULT NULL, customer_name VARCHAR(100) NOT NULL, customer_phone VARCHAR(20) NOT NULL, customer_email VARCHAR(100) DEFAULT NULL, driver_license_number VARCHAR(50) NOT NULL, daily_rate NUMERIC(10, 2) NOT NULL, total_days INT NOT NULL, total_price NUMERIC(10, 2) NOT NULL, late_fee NUMERIC(10, 2) DEFAULT NULL, status VARCHAR(255) NOT NULL, is_deleted BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_42C84955C3C6F69F ON reservation (car_id)');
        $this->addSql('CREATE INDEX IDX_42C84955A76ED395 ON reservation (user_id)');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, is_deleted BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955C3C6F69F FOREIGN KEY (car_id) REFERENCES car (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE reservation DROP CONSTRAINT FK_42C84955C3C6F69F');
        $this->addSql('ALTER TABLE reservation DROP CONSTRAINT FK_42C84955A76ED395');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE "user"');
    }
}
