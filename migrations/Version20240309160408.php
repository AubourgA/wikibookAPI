<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240309160408 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book_copy ADD status_id INT NOT NULL');
        $this->addSql('ALTER TABLE book_copy ADD CONSTRAINT FK_5427F08A6BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('CREATE INDEX IDX_5427F08A6BF700BD ON book_copy (status_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book_copy DROP FOREIGN KEY FK_5427F08A6BF700BD');
        $this->addSql('DROP INDEX IDX_5427F08A6BF700BD ON book_copy');
        $this->addSql('ALTER TABLE book_copy DROP status_id');
    }
}
