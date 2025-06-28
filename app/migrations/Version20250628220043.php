<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250628220043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE users_tables (users_id INT NOT NULL, tables_id INT NOT NULL, INDEX IDX_8BA08CB167B3B43D (users_id), INDEX IDX_8BA08CB185405FD2 (tables_id), PRIMARY KEY(users_id, tables_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users_tables ADD CONSTRAINT FK_8BA08CB167B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users_tables ADD CONSTRAINT FK_8BA08CB185405FD2 FOREIGN KEY (tables_id) REFERENCES tables (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tables_users DROP FOREIGN KEY FK_C8741F5E67B3B43D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tables_users DROP FOREIGN KEY FK_C8741F5E85405FD2
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tables_users
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tables ADD members VARCHAR(100) DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE tables_users (tables_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_C8741F5E67B3B43D (users_id), INDEX IDX_C8741F5E85405FD2 (tables_id), PRIMARY KEY(tables_id, users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tables_users ADD CONSTRAINT FK_C8741F5E67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tables_users ADD CONSTRAINT FK_C8741F5E85405FD2 FOREIGN KEY (tables_id) REFERENCES tables (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users_tables DROP FOREIGN KEY FK_8BA08CB167B3B43D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users_tables DROP FOREIGN KEY FK_8BA08CB185405FD2
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE users_tables
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tables DROP members
        SQL);
    }
}
