<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230118181928 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename "slug" field to "url"';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
        CREATE TABLE docs_dg_tmp (
            id UUID NOT NULL PRIMARY KEY,
            category_id UUID DEFAULT NULL CONSTRAINT FK_51572BB712469DE2 REFERENCES categories,
            title VARCHAR(255) NOT NULL,
            url VARCHAR(255) NOT NULL,
            filename VARCHAR(255) DEFAULT '' NOT NULL,
            priority SMALLINT UNSIGNED DEFAULT 0 NOT NULL,
            content_source CLOB NOT NULL,
            content_rendered CLOB DEFAULT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
            updated_at DATETIME DEFAULT NULL,
            deleted_at DATETIME DEFAULT NULL
        )
        SQL);

        $this->addSql(<<<'SQL'
        INSERT INTO docs_dg_tmp
            (id, category_id, title, url, priority, created_at, updated_at, deleted_at, content_source, content_rendered, filename)
        SELECT 
            id, category_id, title, slug, priority, created_at, updated_at, deleted_at, content_source, content_rendered, filename
        FROM docs
        SQL);

        $this->addSql(<<<'SQL'
        DROP TABLE docs
        SQL);

        $this->addSql(<<<'SQL'
        ALTER TABLE docs_dg_tmp RENAME TO docs;
        SQL);

        $this->addSql(<<<'SQL'
        CREATE INDEX IDX_51572BB712469DE2 ON docs (category_id);
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
        CREATE TABLE docs_dg_tmp (
            id UUID NOT NULL PRIMARY KEY,
            category_id UUID DEFAULT NULL CONSTRAINT FK_51572BB712469DE2 REFERENCES categories,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL,
            filename VARCHAR(255) DEFAULT '' NOT NULL,
            priority SMALLINT UNSIGNED DEFAULT 0 NOT NULL,
            content_source CLOB NOT NULL,
            content_rendered CLOB DEFAULT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
            updated_at DATETIME DEFAULT NULL,
            deleted_at DATETIME DEFAULT NULL
        )
        SQL);

        $this->addSql(<<<'SQL'
        INSERT INTO docs_dg_tmp
            (id, category_id, title, slug, priority, created_at, updated_at, deleted_at, content_source, content_rendered, filename)
        SELECT 
            id, category_id, title, url, priority, created_at, updated_at, deleted_at, content_source, content_rendered, filename
        FROM docs
        SQL);

        $this->addSql(<<<'SQL'
        DROP TABLE docs
        SQL);

        $this->addSql(<<<'SQL'
        ALTER TABLE docs_dg_tmp RENAME TO docs;
        SQL);

        $this->addSql(<<<'SQL'
        CREATE INDEX IDX_51572BB712469DE2 ON docs (category_id);
        SQL);
    }
}
