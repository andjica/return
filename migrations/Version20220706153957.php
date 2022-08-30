<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220706153957 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, INDEX IDX_6D28840D12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D12469DE2 FOREIGN KEY (category_id) REFERENCES category_payment (id)');
        $this->addSql('ALTER TABLE returns CHANGE countries_id countries_id INT NOT NULL, CHANGE client_name client_name VARCHAR(200) NOT NULL, CHANGE company_name company_name VARCHAR(200) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE payment');
        $this->addSql('ALTER TABLE returns CHANGE countries_id countries_id INT DEFAULT NULL, CHANGE client_name client_name VARCHAR(255) NOT NULL, CHANGE company_name company_name VARCHAR(255) DEFAULT NULL');
    }
}
