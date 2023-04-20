<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230420031319 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE repo_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE users_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE repo (id INT NOT NULL, github_user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, repo_object JSON NOT NULL, full_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5C5CBBFFA8674B11 ON repo (github_user_id)');
        $this->addSql('CREATE TABLE users (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE repo ADD CONSTRAINT FK_5C5CBBFFA8674B11 FOREIGN KEY (github_user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE repo_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE users_id_seq CASCADE');
        $this->addSql('ALTER TABLE repo DROP CONSTRAINT FK_5C5CBBFFA8674B11');
        $this->addSql('DROP TABLE repo');
        $this->addSql('DROP TABLE users');
    }
}
