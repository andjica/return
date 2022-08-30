<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220725155840 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE email_template (id INT AUTO_INCREMENT NOT NULL, status_id INT NOT NULL, subject VARCHAR(150) DEFAULT NULL, body LONGTEXT NOT NULL, background_color VARCHAR(10) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, template_shablon VARCHAR(60) DEFAULT NULL, INDEX IDX_9C0600CA6BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pay_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payments (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, public_key VARCHAR(255) DEFAULT NULL, secret_key VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_65D29B3212469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reason_settings (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(200) DEFAULT NULL, active INT NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reseller_address (id INT AUTO_INCREMENT NOT NULL, country_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, company_name VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(255) DEFAULT NULL, house_number VARCHAR(255) DEFAULT NULL, house_number_addition VARCHAR(255) DEFAULT NULL, street VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, customer_id VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reseller_shipment_items (id INT AUTO_INCREMENT NOT NULL, shipment_id INT NOT NULL, sku VARCHAR(255) DEFAULT NULL, qty SMALLINT NOT NULL, title VARCHAR(255) DEFAULT NULL, location VARCHAR(255) DEFAULT NULL, weight DOUBLE PRECISION NOT NULL, length DOUBLE PRECISION NOT NULL, width DOUBLE PRECISION NOT NULL, height DOUBLE PRECISION NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reseller_shipments (id INT AUTO_INCREMENT NOT NULL, external_id INT NOT NULL, sender_address_id INT NOT NULL, return_address_id INT NOT NULL, delivery_address_id INT NOT NULL, shipping_option_id INT DEFAULT NULL, domain_id INT NOT NULL, customer_id INT NOT NULL, webshop_order_id VARCHAR(255) DEFAULT NULL, weight DOUBLE PRECISION NOT NULL, labels_num INT NOT NULL, created_date DATETIME DEFAULT NULL, reference INT NOT NULL, removed TINYINT(1) NOT NULL, has_labels TINYINT(1) NOT NULL, is_shipped TINYINT(1) NOT NULL, has_errors TINYINT(1) NOT NULL, shop_type SMALLINT NOT NULL, route_name VARCHAR(255) NOT NULL, product_items_qty SMALLINT NOT NULL, distributor_reseller_data_id INT NOT NULL, last_shipped_status_date DATETIME DEFAULT NULL, manual_price DOUBLE PRECISION NOT NULL, is_manual_price INT NOT NULL, is_network TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE return_images (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(255) DEFAULT NULL, return_id INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE return_settings (id INT AUTO_INCREMENT NOT NULL, image_logo VARCHAR(255) NOT NULL, image_background VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, return_period INT NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE return_status (id INT AUTO_INCREMENT NOT NULL, status_id INT NOT NULL, returns_id INT NOT NULL, return_id INT NOT NULL, created_at DATETIME DEFAULT NULL, INDEX IDX_3FDDFFC36BF700BD (status_id), INDEX IDX_3FDDFFC311EAEF68 (returns_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE return_videos (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(255) NOT NULL, return_id INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE returns (id INT AUTO_INCREMENT NOT NULL, status_id INT NOT NULL, country_id INT NOT NULL, reference INT NOT NULL, webshop_order_id VARCHAR(255) NOT NULL, user_email VARCHAR(200) NOT NULL, client_email VARCHAR(200) NOT NULL, client_name VARCHAR(200) NOT NULL, company_name VARCHAR(200) DEFAULT NULL, action VARCHAR(155) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, return_quantity INT DEFAULT NULL, reason VARCHAR(255) NOT NULL, street VARCHAR(255) DEFAULT NULL, items_id INT DEFAULT NULL, post_code VARCHAR(15) DEFAULT NULL, INDEX IDX_8B164CA56BF700BD (status_id), INDEX IDX_8B164CA5F92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(155) NOT NULL, key_name VARCHAR(65) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, created_date DATETIME DEFAULT NULL, access_token VARCHAR(255) DEFAULT NULL, access_token_exp DATETIME DEFAULT NULL, update_session TINYINT(1) DEFAULT NULL, roles LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE email_template ADD CONSTRAINT FK_9C0600CA6BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE payments ADD CONSTRAINT FK_65D29B3212469DE2 FOREIGN KEY (category_id) REFERENCES pay_category (id)');
        $this->addSql('ALTER TABLE return_status ADD CONSTRAINT FK_3FDDFFC36BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE return_status ADD CONSTRAINT FK_3FDDFFC311EAEF68 FOREIGN KEY (returns_id) REFERENCES returns (id)');
        $this->addSql('ALTER TABLE returns ADD CONSTRAINT FK_8B164CA56BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE returns ADD CONSTRAINT FK_8B164CA5F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('DROP TABLE category_payment');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE returns DROP FOREIGN KEY FK_8B164CA5F92F3E70');
        $this->addSql('ALTER TABLE payments DROP FOREIGN KEY FK_65D29B3212469DE2');
        $this->addSql('ALTER TABLE return_status DROP FOREIGN KEY FK_3FDDFFC311EAEF68');
        $this->addSql('ALTER TABLE email_template DROP FOREIGN KEY FK_9C0600CA6BF700BD');
        $this->addSql('ALTER TABLE return_status DROP FOREIGN KEY FK_3FDDFFC36BF700BD');
        $this->addSql('ALTER TABLE returns DROP FOREIGN KEY FK_8B164CA56BF700BD');
        $this->addSql('CREATE TABLE category_payment (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(150) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE email_template');
        $this->addSql('DROP TABLE pay_category');
        $this->addSql('DROP TABLE payments');
        $this->addSql('DROP TABLE reason_settings');
        $this->addSql('DROP TABLE reseller_address');
        $this->addSql('DROP TABLE reseller_shipment_items');
        $this->addSql('DROP TABLE reseller_shipments');
        $this->addSql('DROP TABLE return_images');
        $this->addSql('DROP TABLE return_settings');
        $this->addSql('DROP TABLE return_status');
        $this->addSql('DROP TABLE return_videos');
        $this->addSql('DROP TABLE returns');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE users');
    }
}
