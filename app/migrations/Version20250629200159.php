<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250629200159 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE cards (id INT AUTO_INCREMENT NOT NULL, columns_id INT DEFAULT NULL, card_title VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL, comment LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', start_date DATE DEFAULT NULL, members VARCHAR(50) DEFAULT NULL, notification VARCHAR(255) DEFAULT NULL, attachment VARCHAR(100) DEFAULT NULL, deadline DATE DEFAULT NULL, INDEX IDX_4C258FD117615F0 (columns_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE columns (id INT AUTO_INCREMENT NOT NULL, tables_id INT DEFAULT NULL, column_title VARCHAR(50) NOT NULL, ranking INT DEFAULT NULL, INDEX IDX_ACCEC0B785405FD2 (tables_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, users_id INT DEFAULT NULL, confirmation_date DATE NOT NULL, order_number VARCHAR(255) NOT NULL, status VARCHAR(50) NOT NULL, total_price VARCHAR(100) NOT NULL, INDEX IDX_E52FFDEE67B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cards ADD CONSTRAINT FK_4C258FD117615F0 FOREIGN KEY (columns_id) REFERENCES columns (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE columns ADD CONSTRAINT FK_ACCEC0B785405FD2 FOREIGN KEY (tables_id) REFERENCES tables (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE67B3B43D FOREIGN KEY (users_id) REFERENCES users (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE cards DROP FOREIGN KEY FK_4C258FD117615F0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE columns DROP FOREIGN KEY FK_ACCEC0B785405FD2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE67B3B43D
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE cards
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE columns
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE orders
        SQL);
    }
}
