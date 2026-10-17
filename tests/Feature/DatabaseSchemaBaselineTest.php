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
        $this->assertSame(59, (int) DB::selectOne(
            'SELECT COUNT(*) AS table_count FROM information_schema.tables WHERE table_schema = DATABASE()'
        )->table_count);

        foreach (['buttons_setting', 'countries', 'industries', 'password_resets', 'user_type', 'users'] as $table) {
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
        $this->assertSame(1, DB::table('user_type')->count());
        $this->assertSame(10, DB::table('configuration')->count());
    }
}
