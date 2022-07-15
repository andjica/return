<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220714121322 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE country ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE payments ADD category_id INT NOT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME DEFAULT NULL, CHANGE public_key public_key VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE payments ADD CONSTRAINT FK_65D29B3212469DE2 FOREIGN KEY (category_id) REFERENCES pay_category (id)');
        $this->addSql('CREATE INDEX IDX_65D29B3212469DE2 ON payments (category_id)');
        $this->addSql('ALTER TABLE reason_settings CHANGE created_at created_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE reseller_shipment_items CHANGE sku sku VARCHAR(255) DEFAULT NULL, CHANGE title title VARCHAR(255) DEFAULT NULL, CHANGE location location VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE reseller_shipments CHANGE created_date created_date DATETIME DEFAULT NULL, CHANGE distributor_reseller_data_id distributor_reseller_data_id INT NOT NULL');
        $this->addSql('ALTER TABLE return_images CHANGE url url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE return_status CHANGE created_at created_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE returns DROP FOREIGN KEY FK_8B164CA5AEBAE514');
        $this->addSql('DROP INDEX IDX_8B164CA5AEBAE514 ON returns');
        $this->addSql('ALTER TABLE returns ADD country_id INT NOT NULL, DROP countries_id, CHANGE client_name client_name VARCHAR(200) NOT NULL, CHANGE company_name company_name VARCHAR(200) DEFAULT NULL');
        $this->addSql('ALTER TABLE returns ADD CONSTRAINT FK_8B164CA5F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('CREATE INDEX IDX_8B164CA5F92F3E70 ON returns (country_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE country DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE payments DROP FOREIGN KEY FK_65D29B3212469DE2');
        $this->addSql('DROP INDEX IDX_65D29B3212469DE2 ON payments');
        $this->addSql('ALTER TABLE payments DROP category_id, DROP created_at, DROP updated_at, CHANGE public_key public_key VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE reason_settings CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE reseller_shipment_items CHANGE sku sku VARCHAR(255) NOT NULL, CHANGE title title VARCHAR(255) NOT NULL, CHANGE location location VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE reseller_shipments CHANGE created_date created_date DATETIME NOT NULL, CHANGE distributor_reseller_data_id distributor_reseller_data_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE return_images CHANGE url url VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE return_status CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE returns DROP FOREIGN KEY FK_8B164CA5F92F3E70');
        $this->addSql('DROP INDEX IDX_8B164CA5F92F3E70 ON returns');
        $this->addSql('ALTER TABLE returns ADD countries_id INT DEFAULT NULL, DROP country_id, CHANGE client_name client_name VARCHAR(255) NOT NULL, CHANGE company_name company_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE returns ADD CONSTRAINT FK_8B164CA5AEBAE514 FOREIGN KEY (countries_id) REFERENCES country (id)');
        $this->addSql('CREATE INDEX IDX_8B164CA5AEBAE514 ON returns (countries_id)');
    }
}
