<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240309142820 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE author ADD nationality_id INT NOT NULL');
        $this->addSql('ALTER TABLE author ADD CONSTRAINT FK_BDAFD8C81C9DA55 FOREIGN KEY (nationality_id) REFERENCES nationality (id)');
        $this->addSql('CREATE INDEX IDX_BDAFD8C81C9DA55 ON author (nationality_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE author DROP FOREIGN KEY FK_BDAFD8C81C9DA55');
        $this->addSql('DROP INDEX IDX_BDAFD8C81C9DA55 ON author');
        $this->addSql('ALTER TABLE author DROP nationality_id');
    }
}
