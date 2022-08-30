<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220725155120 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reason_settings CHANGE active active INT NOT NULL');
        $this->addSql('ALTER TABLE return_status ADD returns_id INT NOT NULL');
        $this->addSql('ALTER TABLE return_status ADD CONSTRAINT FK_3FDDFFC311EAEF68 FOREIGN KEY (returns_id) REFERENCES returns (id)');
        $this->addSql('CREATE INDEX IDX_3FDDFFC311EAEF68 ON return_status (returns_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reason_settings CHANGE active active INT DEFAULT NULL');
        $this->addSql('ALTER TABLE return_status DROP FOREIGN KEY FK_3FDDFFC311EAEF68');
        $this->addSql('DROP INDEX IDX_3FDDFFC311EAEF68 ON return_status');
        $this->addSql('ALTER TABLE return_status DROP returns_id');
    }
}
