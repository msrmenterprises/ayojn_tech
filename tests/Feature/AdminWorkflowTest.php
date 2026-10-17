<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class AdminWorkflowTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesTestUsers;

    public function test_controlpanel_admin_can_open_the_opportunity_list()
    {
        $admin = User::findOrFail($this->createUser('admin-opportunities@example.test', 0, '000000', [
            'role_id' => 1,
        ]));

        $this->actingAs($admin, 'controlpanel')
            ->get('/controlpanel/opportunity-list')
            ->assertStatus(200);
    }

    public function test_controlpanel_admin_can_open_user_and_voucher_pages()
    {
        $admin = User::findOrFail($this->createUser('admin-pages@example.test', 0, '000000', [
            'role_id' => 1,
        ]));
        $this->actingAs($admin, 'controlpanel');

        foreach ([
            '/controlpanel/user-list',
            '/controlpanel/review-list',
            '/controlpanel/bid-list',
            '/controlpanel/response',
            '/controlpanel/codes',
            '/controlpanel/redeem-request',
            '/controlpanel/vouch-create',
            '/controlpanel/vouchlist/999999',
        ] as $path) {
            $this->get($path)->assertStatus(200);
        }
    }

    public function test_controlpanel_admin_can_load_bid_opportunity_and_review_tables()
    {
        $admin = User::findOrFail($this->createUser('admin-data-tables@example.test', 0, '000000', [
            'role_id' => 1,
        ]));
        $this->actingAs($admin, 'controlpanel');

        foreach ([
            '/controlpanel/list-bid',
            '/controlpanel/list-opportunity',
            '/controlpanel/list-vouch/999999',
            '/controlpanel/review-list-data',
        ] as $path) {
            $this->postJson($path, [])
                ->assertStatus(200)
                ->assertJsonStructure(['data']);
        }
    }

    public function test_controlpanel_admin_can_toggle_and_delete_an_opportunity()
    {
        $admin = User::findOrFail($this->createUser('admin-opportunity-status@example.test', 0, '000000', [
            'role_id' => 1,
        ]));
        $receiver = User::findOrFail($this->createUser('admin-opportunity-owner@example.test', 2));
        $opportunityId = DB::table('opportunities')->insertGetId([
            'receiver_id' => $receiver->id,
            'hashtag' => '#admin-status-test',
            'country_code' => 'ZZ',
            'industry_id' => 999,
            'status' => 1,
        ]);

        $this->actingAs($admin, 'controlpanel');
        $deactivate = $this->post('/controlpanel/opportunity-change-status', [
            'opportunityId' => $opportunityId,
        ]);
        $this->assertSame(0, json_decode($deactivate->getContent(), true)['status']);
        $this->assertDatabaseHas('opportunities', [
            'id' => $opportunityId,
            'status' => 0,
        ]);

        $activate = $this->post('/controlpanel/opportunity-change-status', [
            'opportunityId' => $opportunityId,
        ]);
        $this->assertSame(1, json_decode($activate->getContent(), true)['status']);

        $deleted = $this->postJson('/controlpanel/delete-opportunity', ['user_id' => $opportunityId]);
        $deleted->assertJson([
            'bid_id' => $opportunityId,
            'msg' => 'Opportunity has been deleted successfully',
        ]);
        $this->assertNotNull(DB::table('opportunities')->where('id', $opportunityId)->value('deleted_at'));
    }

    public function test_controlpanel_admin_can_review_opportunity_flag_activity()
    {
        $admin = User::findOrFail($this->createUser('admin-opportunity-flags@example.test', 0, '000000', [
            'role_id' => 1,
        ]));
        $flaggedUser = User::findOrFail($this->createUser('flagged-opportunity-user@example.test', 2));
        $opportunityId = DB::table('opportunities')->insertGetId([
            'receiver_id' => $flaggedUser->id,
            'hashtag' => '#admin-flag-test',
            'country_code' => 'ZZ',
            'industry_id' => 999,
            'status' => 1,
        ]);
        $this->actingAs($admin, 'controlpanel');

        $this->postJson('/controlpanel/flags-activity-admin-opportunity', ['id' => $opportunityId])
            ->assertJson([
                'status' => false,
                'msg' => 'Flag activity not found',
            ]);

        DB::table('flag_activity')->insert([
            'user_id' => $flaggedUser->id,
            'reference_id' => $opportunityId,
            'flag_type' => 'opportunities',
            'reason' => 'Synthetic review reason',
            'created_at' => '2026-01-01 00:00:00',
        ]);

        $this->postJson('/controlpanel/flags-activity-admin-opportunity', ['id' => $opportunityId])
            ->assertJson([
                'status' => true,
                'msg' => 'Flag activity found',
            ])
            ->assertSee('Synthetic review reason')
            ->assertSee('flagged-opportunity-user@example.test');
    }

    public function test_controlpanel_admin_can_request_opportunity_transactions()
    {
        $admin = User::findOrFail($this->createUser('admin-opportunity-transactions@example.test', 0, '000000', [
            'role_id' => 1,
        ]));
        $this->actingAs($admin, 'controlpanel');

        $response = $this->post('/controlpanel/opportunity-transaction', [
            'id' => 987654,
            'user_id' => 987654,
        ]);

        $response->assertStatus(200);
        $this->assertSame('', $response->getContent());
    }

    public function test_controlpanel_admin_can_create_voucher_codes_and_duplicate_codes_are_rejected()
    {
        $admin = User::findOrFail($this->createUser('admin-vouchers@example.test', 0, '000000', [
            'role_id' => 1,
        ]));
        $this->actingAs($admin, 'controlpanel');

        $this->from('/controlpanel/vouch-create')
            ->post('/controlpanel/vouch-add', [])
            ->assertRedirect('/controlpanel/vouch-create')
            ->assertSessionHasErrors();

        $this->post('/controlpanel/vouch-add', [
            'vouch_code' => 'SYNTHETIC-TEST-VOUCHER',
            'vouch_amount' => 25,
        ])->assertRedirect(url('controlpanel/codes'))
            ->assertSessionHas('success', 'Vouch code added Successfully');

        $voucher = DB::table('vouch_codes')
            ->where('vouch_code', 'SYNTHETIC-TEST-VOUCHER')
            ->first();
        $this->assertNotNull($voucher);
        $this->assertSame(25, (int) $voucher->vouch_amount);
        $this->assertNotEmpty($voucher->expeiry_date);

        $this->post('/controlpanel/vouch-add', [
            'vouch_code' => 'SYNTHETIC-TEST-VOUCHER',
            'vouch_amount' => 25,
        ])->assertRedirect(url('controlpanel/codes'))
            ->assertSessionHas('error', 'Vouch code already exist');

        $this->assertSame(1, DB::table('vouch_codes')
            ->where('vouch_code', 'SYNTHETIC-TEST-VOUCHER')
            ->count());

        $this->get('/controlpanel/edit-code/' . $voucher->id)
            ->assertStatus(200);

        $this->post('/controlpanel/vouch-update', [
            'code_id' => $voucher->id,
            'vouch_code' => 'SYNTHETIC-UPDATED-VOUCHER',
            'vouch_amount' => 40,
        ])->assertRedirect(url('controlpanel/codes'))
            ->assertSessionHas('success', 'Vouch code updated Successfully');

        $this->assertDatabaseHas('vouch_codes', [
            'id' => $voucher->id,
            'vouch_code' => 'SYNTHETIC-UPDATED-VOUCHER',
            'vouch_amount' => 40,
        ]);
    }
}
