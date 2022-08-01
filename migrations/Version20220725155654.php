<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220725155654 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE category_payment');
        $this->addSql('ALTER TABLE reason_settings CHANGE active active INT NOT NULL');
        $this->addSql('ALTER TABLE return_status ADD returns_id INT NOT NULL');
        $this->addSql('ALTER TABLE return_status ADD CONSTRAINT FK_3FDDFFC311EAEF68 FOREIGN KEY (returns_id) REFERENCES returns (id)');
        $this->addSql('CREATE INDEX IDX_3FDDFFC311EAEF68 ON return_status (returns_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_payment (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(150) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE reason_settings CHANGE active active INT DEFAULT NULL');
        $this->addSql('ALTER TABLE return_status DROP FOREIGN KEY FK_3FDDFFC311EAEF68');
        $this->addSql('DROP INDEX IDX_3FDDFFC311EAEF68 ON return_status');
        $this->addSql('ALTER TABLE return_status DROP returns_id');
    }
}
