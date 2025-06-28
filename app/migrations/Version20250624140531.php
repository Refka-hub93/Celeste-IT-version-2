<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250624140531 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE cards (id INT AUTO_INCREMENT NOT NULL, columns_id INT DEFAULT NULL, card_title VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', start_date DATE NOT NULL, members VARCHAR(255) DEFAULT NULL, notification VARCHAR(255) DEFAULT NULL, attachement VARCHAR(100) DEFAULT NULL, deadline DATE DEFAULT NULL, INDEX IDX_4C258FD117615F0 (columns_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE columns (id INT AUTO_INCREMENT NOT NULL, tables_id INT DEFAULT NULL, column_title VARCHAR(50) NOT NULL, `rank` INT DEFAULT NULL, INDEX IDX_ACCEC0B785405FD2 (tables_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, users_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', order_number VARCHAR(100) NOT NULL, status VARCHAR(50) NOT NULL, order_total VARCHAR(100) NOT NULL, INDEX IDX_E52FFDEE67B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE tables (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(50) NOT NULL, members VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, lastname VARCHAR(50) NOT NULL, firstname VARCHAR(50) NOT NULL, role VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE users_tables (users_id INT NOT NULL, tables_id INT NOT NULL, INDEX IDX_8BA08CB167B3B43D (users_id), INDEX IDX_8BA08CB185405FD2 (tables_id), PRIMARY KEY(users_id, tables_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
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
        $this->addSql(<<<'SQL'
            ALTER TABLE users_tables ADD CONSTRAINT FK_8BA08CB167B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users_tables ADD CONSTRAINT FK_8BA08CB185405FD2 FOREIGN KEY (tables_id) REFERENCES tables (id) ON DELETE CASCADE
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
            ALTER TABLE users_tables DROP FOREIGN KEY FK_8BA08CB167B3B43D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users_tables DROP FOREIGN KEY FK_8BA08CB185405FD2
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
        $this->addSql(<<<'SQL'
            DROP TABLE tables
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE users
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE users_tables
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
