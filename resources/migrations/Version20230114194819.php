<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230114194819 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create "categories" and "docs" tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE categories (
                id UUID NOT NULL,
                title VARCHAR(255) NOT NULL,
                priority SMALLINT UNSIGNED NOT NULL DEFAULT 0,
                PRIMARY KEY(id)
            )
        SQL);

        $this->addSql(<<<'SQL'
            CREATE TABLE docs (
                id UUID NOT NULL, 
                category_id UUID DEFAULT NULL, 
                title VARCHAR(255) NOT NULL, 
                slug VARCHAR(255) NOT NULL, 
                priority SMALLINT UNSIGNED DEFAULT 0 NOT NULL, 
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL --(DC2Type:datetimetz_immutable)
                , 
                updated_at DATETIME DEFAULT NULL --(DC2Type:datetimetz_immutable)
                , 
                content_source CLOB NOT NULL, 
                content_rendered CLOB DEFAULT NULL, 
                PRIMARY KEY(id), 
                CONSTRAINT FK_51572BB712469DE2 FOREIGN KEY (category_id) 
                    REFERENCES categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        SQL);

        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_51572BB712469DE2 ON docs (category_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE docs');
    }
}
