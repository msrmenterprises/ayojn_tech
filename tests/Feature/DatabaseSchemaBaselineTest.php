<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseSchemaBaselineTest extends TestCase
{
    public function test_phpunit_uses_the_isolated_mysql_database_and_legacy_schema()
    {
        $connection = DB::connection();

        $this->assertSame('mysql', $connection->getDriverName());
        $this->assertSame(getenv('TEST_DB_DATABASE'), $connection->getDatabaseName());
        $this->assertSame(61, (int) DB::selectOne(
            'SELECT COUNT(*) AS table_count FROM information_schema.tables WHERE table_schema = DATABASE()'
        )->table_count);

        foreach ([
            'buttons_setting',
            'countries',
            'industries',
            'mfa_demo_settings',
            'mfa_demo_users',
            'password_resets',
            'user_type',
            'users',
        ] as $table) {
            $this->assertTrue(Schema::hasTable($table), 'Expected table [' . $table . '] in the test schema.');
        }

        $this->assertSame(81, DB::table('buttons_setting')->count());
        foreach ([
            'home_page',
            'header',
            'notification',
            'offer_markerplace',
            'beneficiary_edit',
            'directpay',
            'directpay_add',
            'beneficiary_list',
            'Partner',
            'offer_bid',
            'recieve_collaboration',
            'unpaid_bid',
            'Partner_offer_details',
            'offer_opportunity',
            'recieve_my_bid',
            'offer_bid_response',
            'recieve_build_opportunity',
            'recieve_opportunity',
            'recieve_bid',
            'recommend',
        ] as $pageId) {
            $this->assertGreaterThan(
                0,
                DB::table('buttons_setting')->where('page_id', $pageId)->count(),
                'Expected synthetic button fixture for page [' . $pageId . '].'
            );
        }
        $this->assertSame(0, DB::table('users')->count());
        $this->assertSame(0, DB::table('mfa_demo_settings')->count());
        $this->assertSame(0, DB::table('mfa_demo_users')->count());
        $this->assertSame(1, DB::table('user_type')->count());
        $this->assertSame(10, DB::table('configuration')->count());
    }

    public function test_demo_mfa_migration_creates_expected_schema_and_constraints()
    {
        $settingsColumns = Schema::getColumnListing('mfa_demo_settings');
        sort($settingsColumns);
        $this->assertSame([
            'created_at',
            'id',
            'recipient_email',
            'updated_at',
        ], $settingsColumns);

        $mappingColumns = Schema::getColumnListing('mfa_demo_users');
        sort($mappingColumns);
        $this->assertSame([
            'created_at',
            'id',
            'is_active',
            'updated_at',
            'user_id',
        ], $mappingColumns);

        $uniqueIndex = DB::selectOne(
            'SELECT non_unique FROM information_schema.statistics
             WHERE table_schema = DATABASE()
               AND table_name = ?
               AND index_name = ?
               AND column_name = ?',
            ['mfa_demo_users', 'mfa_demo_users_user_id_unique', 'user_id']
        );
        $this->assertNotNull($uniqueIndex, 'Expected a unique index on the mapped user id.');
        $this->assertSame(0, (int) $uniqueIndex->non_unique);

        $foreignKey = DB::selectOne(
            'SELECT referenced_table_name, referenced_column_name
             FROM information_schema.key_column_usage
             WHERE table_schema = DATABASE()
               AND table_name = ?
               AND column_name = ?
               AND constraint_name = ?',
            ['mfa_demo_users', 'user_id', 'mfa_demo_users_user_id_foreign']
        );
        $this->assertNotNull($foreignKey, 'Expected a foreign key from demo mappings to users.');
        $this->assertSame('users', $foreignKey->referenced_table_name);
        $this->assertSame('id', $foreignKey->referenced_column_name);

        $deleteRule = DB::selectOne(
            'SELECT delete_rule FROM information_schema.referential_constraints
             WHERE constraint_schema = DATABASE()
               AND table_name = ?
               AND constraint_name = ?',
            ['mfa_demo_users', 'mfa_demo_users_user_id_foreign']
        );
        $this->assertNotNull($deleteRule, 'Expected a referential delete rule for demo mappings.');
        $this->assertSame('CASCADE', strtoupper($deleteRule->delete_rule));
    }
}
