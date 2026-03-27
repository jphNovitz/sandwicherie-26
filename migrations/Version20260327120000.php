<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260327120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create product table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('product');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['length' => 255]);
        $table->addColumn('description', 'text', ['notnull' => false]);
        $table->addColumn('price', 'decimal', ['precision' => 10, 'scale' => 2]);
        $table->addColumn('is_available', 'boolean');
        $table->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('product');
    }
}
