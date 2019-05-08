<?php declare(strict_types=1);

namespace FroshApiLogger\Migrations;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Schema\Comparator;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;
use Shopware\Components\Migrations\AbstractPluginMigration;

class Migration2 extends AbstractPluginMigration
{
    public function up($modus): void
    {
        $sqls = (new Comparator())
            ->compare(new Schema(), $this->getSchema())
            ->toSql(new MySqlPlatform())
        ;
        array_walk($sqls, [$this, 'addSql']);
    }

    public function down(bool $keepUserData): void
    {
        if ($keepUserData) {
            return;
        }

        $sqls = (new Comparator())
            ->compare($this->getSchema(), new Schema())
            ->toSql(new MySqlPlatform())
        ;
        array_walk($sqls, [$this, 'addSql']);
    }

    private function getSchema(): Schema
    {
        $schema = new Schema();
        $migration = $schema->createTable('frosh_api_logger_rules');
        $migration->addColumn('id', Type::INTEGER, ['autoincrement' => true, 'notnull' => true]);
        $migration->addColumn('active', Type::BOOLEAN, ['notnull' => true]);
        $migration->addColumn('type_id', Type::STRING, ['notnull' => true]);
        $migration->addColumn('configuration', Type::STRING, ['notnull' => true]);
        $migration->setPrimaryKey(['id']);
        $migration->addIndex(['type_id']);
        return $schema;
    }
}
