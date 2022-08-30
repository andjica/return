<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220706161000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payments ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE payments ADD CONSTRAINT FK_65D29B3212469DE2 FOREIGN KEY (category_id) REFERENCES pay_category (id)');
        $this->addSql('CREATE INDEX IDX_65D29B3212469DE2 ON payments (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payments DROP FOREIGN KEY FK_65D29B3212469DE2');
        $this->addSql('DROP INDEX IDX_65D29B3212469DE2 ON payments');
        $this->addSql('ALTER TABLE payments DROP created_at, DROP updated_at');
    }
}
