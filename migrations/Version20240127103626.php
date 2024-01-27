<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240127103626 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contacts (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, member_id INTEGER NOT NULL, type SMALLINT NOT NULL, value VARCHAR(255) NOT NULL, CONSTRAINT FK_334015737597D3FE FOREIGN KEY (member_id) REFERENCES members_of_european_parliament (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_334015737597D3FE ON contacts (member_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE contacts');
    }
}
