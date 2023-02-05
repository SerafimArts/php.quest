<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230205050742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add "keywords" column';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE docs ADD keywords TEXT DEFAULT \'\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE docs_dg_tmp (
                id UUID NOT NULL PRIMARY KEY,
                category_id UUID DEFAULT NULL
                CONSTRAINT FK_51572BB712469DE2 REFERENCES categories,
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
            INSERT INTO docs_dg_tmp(
                id,
                category_id,
                title,
                url,
                filename,
                priority,
                content_source,
                content_rendered,
                created_at,
                updated_at,
                deleted_at
            ) SELECT
                id,
                category_id,
                title,
                url,
                filename,
                priority,
                content_source,
                content_rendered,
                created_at,
                updated_at,
                deleted_at
            FROM docs
        SQL);

        $this->addSql('DROP TABLE docs');
        $this->addSql('ALTER TABLE docs_dg_tmp RENAME TO docs');
        $this->addSql('CREATE INDEX IDX_51572BB712469DE2 ON docs (category_id)');
    }
}
