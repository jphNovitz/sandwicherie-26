<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260330170000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Harden deletion rules for categories, allergens and featured items';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('PRAGMA foreign_keys = OFF');

        $this->addSql('CREATE TEMPORARY TABLE __temp__product AS SELECT id, name, description, price, is_available, position, image, category_id, created_at, updated_at FROM product');
        $this->addSql('DROP TABLE product');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, price NUMERIC(10, 2) NOT NULL, position INTEGER NOT NULL, image VARCHAR(255) DEFAULT NULL, is_available BOOLEAN NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE RESTRICT NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO product (id, category_id, name, description, price, position, image, is_available, created_at, updated_at) SELECT id, category_id, name, description, price, position, image, is_available, created_at, updated_at FROM __temp__product');
        $this->addSql('DROP TABLE __temp__product');
        $this->addSql('CREATE INDEX IDX_D34A04AD12469DE2 ON product (category_id)');

        $this->addSql('CREATE TEMPORARY TABLE __temp__product_allergen AS SELECT product_id, allergen_id FROM product_allergen');
        $this->addSql('DROP TABLE product_allergen');
        $this->addSql('CREATE TABLE product_allergen (product_id INTEGER NOT NULL, allergen_id INTEGER NOT NULL, PRIMARY KEY(product_id, allergen_id), CONSTRAINT FK_5A0B5A51FD5E248 FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_5A0B5A51B18AF8D8 FOREIGN KEY (allergen_id) REFERENCES allergen (id) ON DELETE RESTRICT NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO product_allergen (product_id, allergen_id) SELECT product_id, allergen_id FROM __temp__product_allergen');
        $this->addSql('DROP TABLE __temp__product_allergen');
        $this->addSql('CREATE INDEX IDX_5A0B5A51FD5E248 ON product_allergen (product_id)');
        $this->addSql('CREATE INDEX IDX_5A0B5A51B18AF8D8 ON product_allergen (allergen_id)');

        $this->addSql('CREATE TEMPORARY TABLE __temp__featured_item AS SELECT id, site_settings_id, type, position, custom_title, short_text, show_price, is_active, product_id, category_id, created_at, updated_at FROM featured_item');
        $this->addSql('DROP TABLE featured_item');
        $this->addSql('CREATE TABLE featured_item (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, site_settings_id INTEGER NOT NULL, product_id INTEGER DEFAULT NULL, category_id INTEGER DEFAULT NULL, type VARCHAR(20) NOT NULL, position INTEGER NOT NULL, custom_title VARCHAR(255) DEFAULT NULL, short_text CLOB DEFAULT NULL, show_price BOOLEAN NOT NULL, is_active BOOLEAN NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_D5255E76F9D783E5 FOREIGN KEY (site_settings_id) REFERENCES site_settings (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D5255E764584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D5255E7612469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO featured_item (id, site_settings_id, type, position, custom_title, short_text, show_price, is_active, product_id, category_id, created_at, updated_at) SELECT id, site_settings_id, type, position, custom_title, short_text, show_price, is_active, product_id, category_id, created_at, updated_at FROM __temp__featured_item');
        $this->addSql('DROP TABLE __temp__featured_item');
        $this->addSql('CREATE INDEX IDX_D5255E76F9D783E5 ON featured_item (site_settings_id)');
        $this->addSql('CREATE INDEX IDX_D5255E764584665A ON featured_item (product_id)');
        $this->addSql('CREATE INDEX IDX_D5255E7612469DE2 ON featured_item (category_id)');

        $this->addSql('PRAGMA foreign_keys = ON');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('PRAGMA foreign_keys = OFF');

        $this->addSql('CREATE TEMPORARY TABLE __temp__product AS SELECT id, category_id, name, description, price, position, image, is_available, created_at, updated_at FROM product');
        $this->addSql('DROP TABLE product');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, price NUMERIC(10, 2) NOT NULL, position INTEGER NOT NULL, image VARCHAR(255) DEFAULT NULL, is_available BOOLEAN NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO product (id, category_id, name, description, price, position, image, is_available, created_at, updated_at) SELECT id, category_id, name, description, price, position, image, is_available, created_at, updated_at FROM __temp__product');
        $this->addSql('DROP TABLE __temp__product');
        $this->addSql('CREATE INDEX IDX_D34A04AD12469DE2 ON product (category_id)');

        $this->addSql('CREATE TEMPORARY TABLE __temp__product_allergen AS SELECT product_id, allergen_id FROM product_allergen');
        $this->addSql('DROP TABLE product_allergen');
        $this->addSql('CREATE TABLE product_allergen (product_id INTEGER NOT NULL, allergen_id INTEGER NOT NULL, PRIMARY KEY(product_id, allergen_id), CONSTRAINT FK_5A0B5A51FD5E248 FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_5A0B5A51B18AF8D8 FOREIGN KEY (allergen_id) REFERENCES allergen (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO product_allergen (product_id, allergen_id) SELECT product_id, allergen_id FROM __temp__product_allergen');
        $this->addSql('DROP TABLE __temp__product_allergen');
        $this->addSql('CREATE INDEX IDX_5A0B5A51FD5E248 ON product_allergen (product_id)');
        $this->addSql('CREATE INDEX IDX_5A0B5A51B18AF8D8 ON product_allergen (allergen_id)');

        $this->addSql('CREATE TEMPORARY TABLE __temp__featured_item AS SELECT id, site_settings_id, type, position, custom_title, short_text, show_price, is_active, product_id, category_id, created_at, updated_at FROM featured_item');
        $this->addSql('DROP TABLE featured_item');
        $this->addSql('CREATE TABLE featured_item (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, site_settings_id INTEGER NOT NULL, product_id INTEGER DEFAULT NULL, category_id INTEGER DEFAULT NULL, type VARCHAR(20) NOT NULL, position INTEGER NOT NULL, custom_title VARCHAR(255) DEFAULT NULL, short_text CLOB DEFAULT NULL, show_price BOOLEAN NOT NULL, is_active BOOLEAN NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_D5255E76F9D783E5 FOREIGN KEY (site_settings_id) REFERENCES site_settings (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D5255E764584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D5255E7612469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO featured_item (id, site_settings_id, type, position, custom_title, short_text, show_price, is_active, product_id, category_id, created_at, updated_at) SELECT id, site_settings_id, type, position, custom_title, short_text, show_price, is_active, product_id, category_id, created_at, updated_at FROM __temp__featured_item');
        $this->addSql('DROP TABLE __temp__featured_item');
        $this->addSql('CREATE INDEX IDX_D5255E76F9D783E5 ON featured_item (site_settings_id)');
        $this->addSql('CREATE INDEX IDX_D5255E764584665A ON featured_item (product_id)');
        $this->addSql('CREATE INDEX IDX_D5255E7612469DE2 ON featured_item (category_id)');

        $this->addSql('PRAGMA foreign_keys = ON');
    }
}
