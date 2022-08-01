<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220726190931 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE returns ADD CONSTRAINT FK_8B164CA5126F525E FOREIGN KEY (item_id) REFERENCES reseller_shipment_items (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8B164CA5126F525E ON returns (item_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE returns DROP FOREIGN KEY FK_8B164CA5126F525E');
        $this->addSql('DROP INDEX UNIQ_8B164CA5126F525E ON returns');
    }
}
