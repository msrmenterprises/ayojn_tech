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
            '/controlpanel/review-list-data',
        ] as $path) {
            $this->postJson($path, [])
                ->assertStatus(200)
                ->assertJsonStructure(['data']);
        }
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
