<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250702123850 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE notification ADD tables_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA85405FD2 FOREIGN KEY (tables_id) REFERENCES tables (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_BF5476CA85405FD2 ON notification (tables_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA85405FD2
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_BF5476CA85405FD2 ON notification
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification DROP tables_id
        SQL);
    }
}
