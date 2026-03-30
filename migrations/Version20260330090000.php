<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260330090000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Sandwicherie V1 foundation entities and extend product model';
    }

    public function up(Schema $schema): void
    {
        $siteSettings = $schema->createTable('site_settings');
        $siteSettings->addColumn('id', 'integer', ['autoincrement' => true]);
        $siteSettings->addColumn('business_name', 'string', ['length' => 255]);
        $siteSettings->addColumn('slogan', 'string', ['length' => 255, 'notnull' => false]);
        $siteSettings->addColumn('welcome_text', 'text', ['notnull' => false]);
        $siteSettings->addColumn('presentation_text', 'text', ['notnull' => false]);
        $siteSettings->addColumn('phone', 'string', ['length' => 50, 'notnull' => false]);
        $siteSettings->addColumn('email', 'string', ['length' => 180, 'notnull' => false]);
        $siteSettings->addColumn('whatsapp', 'string', ['length' => 50, 'notnull' => false]);
        $siteSettings->addColumn('full_address', 'text', ['notnull' => false]);
        $siteSettings->addColumn('short_address', 'string', ['length' => 255, 'notnull' => false]);
        $siteSettings->addColumn('latitude', 'decimal', ['precision' => 10, 'scale' => 7, 'notnull' => false]);
        $siteSettings->addColumn('longitude', 'decimal', ['precision' => 10, 'scale' => 7, 'notnull' => false]);
        $siteSettings->addColumn('logo', 'string', ['length' => 255, 'notnull' => false]);
        $siteSettings->addColumn('hero_image', 'string', ['length' => 255, 'notnull' => false]);
        $siteSettings->addColumn('notification_email', 'string', ['length' => 180, 'notnull' => false]);
        $siteSettings->addColumn('email_notifications_enabled', 'boolean');
        $siteSettings->addColumn('general_notes', 'text', ['notnull' => false]);
        $siteSettings->addColumn('created_at', 'datetime_immutable');
        $siteSettings->addColumn('updated_at', 'datetime_immutable');
        $siteSettings->setPrimaryKey(['id']);

        $page = $schema->createTable('site_page');
        $page->addColumn('id', 'integer', ['autoincrement' => true]);
        $page->addColumn('site_settings_id', 'integer');
        $page->addColumn('code', 'string', ['length' => 50]);
        $page->addColumn('title', 'string', ['length' => 255]);
        $page->addColumn('slug', 'string', ['length' => 255]);
        $page->addColumn('seo_title', 'string', ['length' => 255, 'notnull' => false]);
        $page->addColumn('meta_description', 'string', ['length' => 500, 'notnull' => false]);
        $page->addColumn('introduction', 'text', ['notnull' => false]);
        $page->addColumn('content', 'text', ['notnull' => false]);
        $page->addColumn('is_active', 'boolean');
        $page->addColumn('created_at', 'datetime_immutable');
        $page->addColumn('updated_at', 'datetime_immutable');
        $page->setPrimaryKey(['id']);
        $page->addUniqueIndex(['code'], 'uniq_site_page_code');
        $page->addUniqueIndex(['slug'], 'uniq_site_page_slug');
        $page->addIndex(['site_settings_id'], 'idx_site_page_site_settings');
        $page->addForeignKeyConstraint('site_settings', ['site_settings_id'], ['id'], ['onDelete' => 'CASCADE']);

        $category = $schema->createTable('category');
        $category->addColumn('id', 'integer', ['autoincrement' => true]);
        $category->addColumn('name', 'string', ['length' => 255]);
        $category->addColumn('description', 'text', ['notnull' => false]);
        $category->addColumn('image', 'string', ['length' => 255, 'notnull' => false]);
        $category->addColumn('position', 'integer');
        $category->addColumn('is_active', 'boolean');
        $category->addColumn('slug', 'string', ['length' => 255]);
        $category->addColumn('seo_title', 'string', ['length' => 255, 'notnull' => false]);
        $category->addColumn('meta_description', 'string', ['length' => 500, 'notnull' => false]);
        $category->addColumn('created_at', 'datetime_immutable');
        $category->addColumn('updated_at', 'datetime_immutable');
        $category->setPrimaryKey(['id']);
        $category->addUniqueIndex(['slug'], 'uniq_category_slug');

        $allergen = $schema->createTable('allergen');
        $allergen->addColumn('id', 'integer', ['autoincrement' => true]);
        $allergen->addColumn('code', 'string', ['length' => 50]);
        $allergen->addColumn('name', 'string', ['length' => 255]);
        $allergen->addColumn('position', 'integer');
        $allergen->addColumn('is_active', 'boolean');
        $allergen->setPrimaryKey(['id']);
        $allergen->addUniqueIndex(['code'], 'uniq_allergen_code');

        $product = $schema->getTable('product');
        $product->addColumn('position', 'integer', ['default' => 0]);
        $product->addColumn('image', 'string', ['length' => 255, 'notnull' => false]);
        $product->addColumn('category_id', 'integer', ['notnull' => false]);
        $product->addColumn('created_at', 'datetime_immutable');
        $product->addColumn('updated_at', 'datetime_immutable');
        $product->addIndex(['category_id'], 'idx_product_category');
        $product->addForeignKeyConstraint('category', ['category_id'], ['id'], ['onDelete' => 'SET NULL'], 'fk_product_category');

        $productAllergen = $schema->createTable('product_allergen');
        $productAllergen->addColumn('product_id', 'integer');
        $productAllergen->addColumn('allergen_id', 'integer');
        $productAllergen->setPrimaryKey(['product_id', 'allergen_id']);
        $productAllergen->addIndex(['product_id'], 'idx_product_allergen_product');
        $productAllergen->addIndex(['allergen_id'], 'idx_product_allergen_allergen');
        $productAllergen->addForeignKeyConstraint('product', ['product_id'], ['id'], ['onDelete' => 'CASCADE']);
        $productAllergen->addForeignKeyConstraint('allergen', ['allergen_id'], ['id'], ['onDelete' => 'CASCADE']);

        $openingHour = $schema->createTable('opening_hour');
        $openingHour->addColumn('id', 'integer', ['autoincrement' => true]);
        $openingHour->addColumn('site_settings_id', 'integer');
        $openingHour->addColumn('day_of_week', 'string', ['length' => 20]);
        $openingHour->addColumn('is_closed', 'boolean');
        $openingHour->addColumn('first_opening_at', 'time_immutable', ['notnull' => false]);
        $openingHour->addColumn('first_closing_at', 'time_immutable', ['notnull' => false]);
        $openingHour->addColumn('second_opening_at', 'time_immutable', ['notnull' => false]);
        $openingHour->addColumn('second_closing_at', 'time_immutable', ['notnull' => false]);
        $openingHour->addColumn('position', 'integer');
        $openingHour->setPrimaryKey(['id']);
        $openingHour->addUniqueIndex(['site_settings_id', 'day_of_week'], 'uniq_opening_hour_site_day');
        $openingHour->addForeignKeyConstraint('site_settings', ['site_settings_id'], ['id'], ['onDelete' => 'CASCADE']);

        $socialLink = $schema->createTable('social_link');
        $socialLink->addColumn('id', 'integer', ['autoincrement' => true]);
        $socialLink->addColumn('site_settings_id', 'integer');
        $socialLink->addColumn('type', 'string', ['length' => 20]);
        $socialLink->addColumn('label', 'string', ['length' => 255, 'notnull' => false]);
        $socialLink->addColumn('url', 'string', ['length' => 500]);
        $socialLink->addColumn('position', 'integer');
        $socialLink->addColumn('is_active', 'boolean');
        $socialLink->addColumn('created_at', 'datetime_immutable');
        $socialLink->addColumn('updated_at', 'datetime_immutable');
        $socialLink->setPrimaryKey(['id']);
        $socialLink->addIndex(['site_settings_id'], 'idx_social_link_site_settings');
        $socialLink->addForeignKeyConstraint('site_settings', ['site_settings_id'], ['id'], ['onDelete' => 'CASCADE']);

        $galleryImage = $schema->createTable('gallery_image');
        $galleryImage->addColumn('id', 'integer', ['autoincrement' => true]);
        $galleryImage->addColumn('site_settings_id', 'integer');
        $galleryImage->addColumn('image', 'string', ['length' => 255]);
        $galleryImage->addColumn('alt_text', 'string', ['length' => 255, 'notnull' => false]);
        $galleryImage->addColumn('position', 'integer');
        $galleryImage->addColumn('is_active', 'boolean');
        $galleryImage->addColumn('created_at', 'datetime_immutable');
        $galleryImage->addColumn('updated_at', 'datetime_immutable');
        $galleryImage->setPrimaryKey(['id']);
        $galleryImage->addIndex(['site_settings_id'], 'idx_gallery_image_site_settings');
        $galleryImage->addForeignKeyConstraint('site_settings', ['site_settings_id'], ['id'], ['onDelete' => 'CASCADE']);

        $review = $schema->createTable('review');
        $review->addColumn('id', 'integer', ['autoincrement' => true]);
        $review->addColumn('site_settings_id', 'integer');
        $review->addColumn('first_name', 'string', ['length' => 100]);
        $review->addColumn('author_initial', 'string', ['length' => 5]);
        $review->addColumn('text', 'text');
        $review->addColumn('rating', 'integer');
        $review->addColumn('source', 'string', ['length' => 100, 'notnull' => false]);
        $review->addColumn('link', 'string', ['length' => 500, 'notnull' => false]);
        $review->addColumn('position', 'integer');
        $review->addColumn('is_visible', 'boolean');
        $review->addColumn('created_at', 'datetime_immutable');
        $review->addColumn('updated_at', 'datetime_immutable');
        $review->setPrimaryKey(['id']);
        $review->addIndex(['site_settings_id'], 'idx_review_site_settings');
        $review->addForeignKeyConstraint('site_settings', ['site_settings_id'], ['id'], ['onDelete' => 'CASCADE']);

        $contactMessage = $schema->createTable('contact_message');
        $contactMessage->addColumn('id', 'integer', ['autoincrement' => true]);
        $contactMessage->addColumn('site_settings_id', 'integer', ['notnull' => false]);
        $contactMessage->addColumn('name', 'string', ['length' => 255]);
        $contactMessage->addColumn('email', 'string', ['length' => 180]);
        $contactMessage->addColumn('phone', 'string', ['length' => 50, 'notnull' => false]);
        $contactMessage->addColumn('message', 'text');
        $contactMessage->addColumn('status', 'string', ['length' => 20]);
        $contactMessage->addColumn('created_at', 'datetime_immutable');
        $contactMessage->addColumn('updated_at', 'datetime_immutable');
        $contactMessage->setPrimaryKey(['id']);
        $contactMessage->addIndex(['site_settings_id'], 'idx_contact_message_site_settings');
        $contactMessage->addForeignKeyConstraint('site_settings', ['site_settings_id'], ['id'], ['onDelete' => 'SET NULL']);

        $featuredItem = $schema->createTable('featured_item');
        $featuredItem->addColumn('id', 'integer', ['autoincrement' => true]);
        $featuredItem->addColumn('site_settings_id', 'integer');
        $featuredItem->addColumn('type', 'string', ['length' => 20]);
        $featuredItem->addColumn('position', 'integer');
        $featuredItem->addColumn('custom_title', 'string', ['length' => 255, 'notnull' => false]);
        $featuredItem->addColumn('short_text', 'text', ['notnull' => false]);
        $featuredItem->addColumn('show_price', 'boolean');
        $featuredItem->addColumn('is_active', 'boolean');
        $featuredItem->addColumn('product_id', 'integer', ['notnull' => false]);
        $featuredItem->addColumn('category_id', 'integer', ['notnull' => false]);
        $featuredItem->addColumn('created_at', 'datetime_immutable');
        $featuredItem->addColumn('updated_at', 'datetime_immutable');
        $featuredItem->setPrimaryKey(['id']);
        $featuredItem->addIndex(['site_settings_id'], 'idx_featured_item_site_settings');
        $featuredItem->addIndex(['product_id'], 'idx_featured_item_product');
        $featuredItem->addIndex(['category_id'], 'idx_featured_item_category');
        $featuredItem->addForeignKeyConstraint('site_settings', ['site_settings_id'], ['id'], ['onDelete' => 'CASCADE']);
        $featuredItem->addForeignKeyConstraint('product', ['product_id'], ['id'], ['onDelete' => 'SET NULL']);
        $featuredItem->addForeignKeyConstraint('category', ['category_id'], ['id'], ['onDelete' => 'SET NULL']);

        $aboutHighlight = $schema->createTable('about_highlight');
        $aboutHighlight->addColumn('id', 'integer', ['autoincrement' => true]);
        $aboutHighlight->addColumn('site_settings_id', 'integer');
        $aboutHighlight->addColumn('title', 'string', ['length' => 255]);
        $aboutHighlight->addColumn('short_text', 'text');
        $aboutHighlight->addColumn('position', 'integer');
        $aboutHighlight->addColumn('is_active', 'boolean');
        $aboutHighlight->addColumn('created_at', 'datetime_immutable');
        $aboutHighlight->addColumn('updated_at', 'datetime_immutable');
        $aboutHighlight->setPrimaryKey(['id']);
        $aboutHighlight->addIndex(['site_settings_id'], 'idx_about_highlight_site_settings');
        $aboutHighlight->addForeignKeyConstraint('site_settings', ['site_settings_id'], ['id'], ['onDelete' => 'CASCADE']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('about_highlight');
        $schema->dropTable('featured_item');
        $schema->dropTable('contact_message');
        $schema->dropTable('review');
        $schema->dropTable('gallery_image');
        $schema->dropTable('social_link');
        $schema->dropTable('opening_hour');
        $schema->dropTable('product_allergen');

        $product = $schema->getTable('product');
        $product->removeForeignKey('fk_product_category');
        $product->dropIndex('idx_product_category');
        $product->dropColumn('position');
        $product->dropColumn('image');
        $product->dropColumn('category_id');
        $product->dropColumn('created_at');
        $product->dropColumn('updated_at');

        $schema->dropTable('allergen');
        $schema->dropTable('category');
        $schema->dropTable('site_page');
        $schema->dropTable('site_settings');
    }
}
