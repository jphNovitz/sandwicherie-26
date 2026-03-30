<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260330193000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Move editorial page content responsibility out of site settings';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("UPDATE site_page
            SET introduction = CASE
                    WHEN (introduction IS NULL OR trim(introduction) = '')
                    THEN (SELECT ss.welcome_text FROM site_settings ss WHERE ss.id = site_page.site_settings_id)
                    ELSE introduction
                END
            WHERE code = 'home'");

        $this->addSql("UPDATE site_page
            SET content = CASE
                    WHEN (content IS NULL OR trim(content) = '')
                    THEN (SELECT ss.presentation_text FROM site_settings ss WHERE ss.id = site_page.site_settings_id)
                    ELSE content
                END
            WHERE code = 'about'");

        $this->addSql('PRAGMA foreign_keys = OFF');
        $this->addSql('CREATE TEMPORARY TABLE __temp__site_settings AS SELECT id, business_name, slogan, phone, email, whatsapp, full_address, short_address, latitude, longitude, logo, hero_image, notification_email, email_notifications_enabled, general_notes, created_at, updated_at FROM site_settings');
        $this->addSql('DROP TABLE site_settings');
        $this->addSql('CREATE TABLE site_settings (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, business_name VARCHAR(255) NOT NULL, slogan VARCHAR(255) DEFAULT NULL, phone VARCHAR(50) DEFAULT NULL, email VARCHAR(180) DEFAULT NULL, whatsapp VARCHAR(50) DEFAULT NULL, full_address CLOB DEFAULT NULL, short_address VARCHAR(255) DEFAULT NULL, latitude NUMERIC(10, 7) DEFAULT NULL, longitude NUMERIC(10, 7) DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, hero_image VARCHAR(255) DEFAULT NULL, notification_email VARCHAR(180) DEFAULT NULL, email_notifications_enabled BOOLEAN NOT NULL, general_notes CLOB DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO site_settings (id, business_name, slogan, phone, email, whatsapp, full_address, short_address, latitude, longitude, logo, hero_image, notification_email, email_notifications_enabled, general_notes, created_at, updated_at) SELECT id, business_name, slogan, phone, email, whatsapp, full_address, short_address, latitude, longitude, logo, hero_image, notification_email, email_notifications_enabled, general_notes, created_at, updated_at FROM __temp__site_settings');
        $this->addSql('DROP TABLE __temp__site_settings');
        $this->addSql('PRAGMA foreign_keys = ON');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('PRAGMA foreign_keys = OFF');
        $this->addSql('CREATE TEMPORARY TABLE __temp__site_settings AS SELECT id, business_name, slogan, phone, email, whatsapp, full_address, short_address, latitude, longitude, logo, hero_image, notification_email, email_notifications_enabled, general_notes, created_at, updated_at FROM site_settings');
        $this->addSql('DROP TABLE site_settings');
        $this->addSql('CREATE TABLE site_settings (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, business_name VARCHAR(255) NOT NULL, slogan VARCHAR(255) DEFAULT NULL, welcome_text CLOB DEFAULT NULL, presentation_text CLOB DEFAULT NULL, phone VARCHAR(50) DEFAULT NULL, email VARCHAR(180) DEFAULT NULL, whatsapp VARCHAR(50) DEFAULT NULL, full_address CLOB DEFAULT NULL, short_address VARCHAR(255) DEFAULT NULL, latitude NUMERIC(10, 7) DEFAULT NULL, longitude NUMERIC(10, 7) DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, hero_image VARCHAR(255) DEFAULT NULL, notification_email VARCHAR(180) DEFAULT NULL, email_notifications_enabled BOOLEAN NOT NULL, general_notes CLOB DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql("INSERT INTO site_settings (id, business_name, slogan, welcome_text, presentation_text, phone, email, whatsapp, full_address, short_address, latitude, longitude, logo, hero_image, notification_email, email_notifications_enabled, general_notes, created_at, updated_at)
            SELECT
                ss.id,
                ss.business_name,
                ss.slogan,
                (SELECT sp.introduction FROM site_page sp WHERE sp.site_settings_id = ss.id AND sp.code = 'home' LIMIT 1),
                (SELECT sp.content FROM site_page sp WHERE sp.site_settings_id = ss.id AND sp.code = 'about' LIMIT 1),
                ss.phone,
                ss.email,
                ss.whatsapp,
                ss.full_address,
                ss.short_address,
                ss.latitude,
                ss.longitude,
                ss.logo,
                ss.hero_image,
                ss.notification_email,
                ss.email_notifications_enabled,
                ss.general_notes,
                ss.created_at,
                ss.updated_at
            FROM __temp__site_settings ss");
        $this->addSql('DROP TABLE __temp__site_settings');
        $this->addSql('PRAGMA foreign_keys = ON');
    }
}
