<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240828135418 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A33182F1BAF4');
        $this->addSql('DROP INDEX IDX_CBE5A33182F1BAF4 ON book');
        $this->addSql('ALTER TABLE book DROP language_id');
        $this->addSql('ALTER TABLE book_copy ADD language_id INT NOT NULL');
        $this->addSql('ALTER TABLE book_copy ADD CONSTRAINT FK_5427F08A82F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('CREATE INDEX IDX_5427F08A82F1BAF4 ON book_copy (language_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book ADD language_id INT NOT NULL');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A33182F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_CBE5A33182F1BAF4 ON book (language_id)');
        $this->addSql('ALTER TABLE book_copy DROP FOREIGN KEY FK_5427F08A82F1BAF4');
        $this->addSql('DROP INDEX IDX_5427F08A82F1BAF4 ON book_copy');
        $this->addSql('ALTER TABLE book_copy DROP language_id');
    }
}
