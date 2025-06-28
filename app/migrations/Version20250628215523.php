<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250628215523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE associer (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, tableau_id INT NOT NULL, fonction VARCHAR(20) NOT NULL, INDEX IDX_FA230DB9FB88E14F (utilisateur_id), INDEX IDX_FA230DB9B062D5BC (tableau_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE users_tables (users_id INT NOT NULL, tables_id INT NOT NULL, INDEX IDX_8BA08CB167B3B43D (users_id), INDEX IDX_8BA08CB185405FD2 (tables_id), PRIMARY KEY(users_id, tables_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE associer ADD CONSTRAINT FK_FA230DB9FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE associer ADD CONSTRAINT FK_FA230DB9B062D5BC FOREIGN KEY (tableau_id) REFERENCES tables (id)
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
            ALTER TABLE associer DROP FOREIGN KEY FK_FA230DB9FB88E14F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE associer DROP FOREIGN KEY FK_FA230DB9B062D5BC
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users_tables DROP FOREIGN KEY FK_8BA08CB167B3B43D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users_tables DROP FOREIGN KEY FK_8BA08CB185405FD2
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE associer
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE users_tables
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tables DROP members
        SQL);
    }
}
