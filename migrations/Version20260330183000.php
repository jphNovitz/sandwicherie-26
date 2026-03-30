<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260330183000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Align review model with v1 customer review management';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('PRAGMA foreign_keys = OFF');

        $this->addSql('CREATE TEMPORARY TABLE __temp__review AS SELECT id, site_settings_id, first_name, author_initial, text, rating, source, link, position, is_visible, created_at, updated_at FROM review');
        $this->addSql('DROP TABLE review');
        $this->addSql('CREATE TABLE review (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, site_settings_id INTEGER NOT NULL, first_name VARCHAR(100) NOT NULL, last_initial VARCHAR(5) NOT NULL, content CLOB NOT NULL, rating INTEGER NOT NULL, source_type VARCHAR(20) NOT NULL, source_label VARCHAR(100) DEFAULT NULL, source_url VARCHAR(500) DEFAULT NULL, position INTEGER NOT NULL, is_visible BOOLEAN NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_794381C6F9D783E5 FOREIGN KEY (site_settings_id) REFERENCES site_settings (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql("INSERT INTO review (id, site_settings_id, first_name, last_initial, content, rating, source_type, source_label, source_url, position, is_visible, created_at, updated_at)
            SELECT
                id,
                site_settings_id,
                first_name,
                author_initial,
                text,
                rating,
                CASE
                    WHEN lower(COALESCE(source, '')) = 'google' THEN 'google'
                    WHEN lower(COALESCE(source, '')) = 'facebook' THEN 'facebook'
                    WHEN lower(COALESCE(source, '')) IN ('direct', 'avis direct') THEN 'direct'
                    WHEN source IS NULL OR trim(source) = '' THEN 'direct'
                    ELSE 'other'
                END,
                CASE
                    WHEN lower(COALESCE(source, '')) IN ('google', 'facebook', 'direct', 'avis direct', '') THEN NULL
                    ELSE source
                END,
                link,
                position,
                is_visible,
                created_at,
                updated_at
            FROM __temp__review");
        $this->addSql('DROP TABLE __temp__review');
        $this->addSql('CREATE INDEX IDX_794381C6F9D783E5 ON review (site_settings_id)');

        $this->addSql('PRAGMA foreign_keys = ON');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('PRAGMA foreign_keys = OFF');

        $this->addSql('CREATE TEMPORARY TABLE __temp__review AS SELECT id, site_settings_id, first_name, last_initial, content, rating, source_type, source_label, source_url, position, is_visible, created_at, updated_at FROM review');
        $this->addSql('DROP TABLE review');
        $this->addSql('CREATE TABLE review (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, site_settings_id INTEGER NOT NULL, first_name VARCHAR(100) NOT NULL, author_initial VARCHAR(5) NOT NULL, text CLOB NOT NULL, rating INTEGER NOT NULL, source VARCHAR(100) DEFAULT NULL, link VARCHAR(500) DEFAULT NULL, position INTEGER NOT NULL, is_visible BOOLEAN NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_794381C6F9D783E5 FOREIGN KEY (site_settings_id) REFERENCES site_settings (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql("INSERT INTO review (id, site_settings_id, first_name, author_initial, text, rating, source, link, position, is_visible, created_at, updated_at)
            SELECT
                id,
                site_settings_id,
                first_name,
                last_initial,
                content,
                rating,
                COALESCE(source_label, source_type),
                source_url,
                position,
                is_visible,
                created_at,
                updated_at
            FROM __temp__review");
        $this->addSql('DROP TABLE __temp__review');
        $this->addSql('CREATE INDEX IDX_794381C6F9D783E5 ON review (site_settings_id)');

        $this->addSql('PRAGMA foreign_keys = ON');
    }
}
