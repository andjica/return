<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220706153051 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_payment (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(150) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE payments CHANGE public_key public_key VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE reason_settings CHANGE created_at created_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE reseller_shipment_items CHANGE sku sku VARCHAR(255) DEFAULT NULL, CHANGE title title VARCHAR(255) DEFAULT NULL, CHANGE location location VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE reseller_shipments CHANGE created_date created_date DATETIME DEFAULT NULL, CHANGE distributor_reseller_data_id distributor_reseller_data_id INT NOT NULL');
        $this->addSql('ALTER TABLE return_images CHANGE url url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE return_status CHANGE created_at created_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE returns CHANGE countries_id countries_id INT NOT NULL, CHANGE client_name client_name VARCHAR(200) NOT NULL, CHANGE company_name company_name VARCHAR(200) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, headers LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, queue_name VARCHAR(190) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E016BA31DB (delivered_at), INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE category_payment');
        $this->addSql('ALTER TABLE payments CHANGE public_key public_key VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE reason_settings CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE reseller_shipment_items CHANGE sku sku VARCHAR(255) NOT NULL, CHANGE title title VARCHAR(255) NOT NULL, CHANGE location location VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE reseller_shipments CHANGE created_date created_date DATETIME NOT NULL, CHANGE distributor_reseller_data_id distributor_reseller_data_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE return_images CHANGE url url VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE return_status CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE returns CHANGE countries_id countries_id INT DEFAULT NULL, CHANGE client_name client_name VARCHAR(255) NOT NULL, CHANGE company_name company_name VARCHAR(255) DEFAULT NULL');
    }
}
