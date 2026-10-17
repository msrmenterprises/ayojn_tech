<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class OpportunityWorkflowTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesTestUsers;

    public function test_opportunity_sponsor_can_view_launched_opportunities()
    {
        $user = User::findOrFail($this->createUser('opportunity-sponsor@example.test', 1));

        $this->actingAs($user, 'web')
            ->get('/opportunity')
            ->assertStatus(200)
            ->assertSee('Launched Opportunities');
    }

    public function test_opportunity_receiver_can_view_built_opportunities()
    {
        $user = User::findOrFail($this->createUser('opportunity-receiver@example.test', 2));

        $this->actingAs($user, 'web')
            ->get('/opportunity')
            ->assertStatus(200)
            ->assertSee('Built Opportunities');
    }

    public function test_opportunity_receiver_can_open_the_build_form()
    {
        $user = User::findOrFail($this->createUser('opportunity-builder@example.test', 2));

        $this->actingAs($user, 'web')
            ->get('/build-opportunity')
            ->assertStatus(200);
    }

    public function test_hashtag_search_returns_an_empty_value_when_no_opportunities_match()
    {
        $user = User::findOrFail($this->createUser('opportunity-hashtags@example.test', 2));

        $this->actingAs($user, 'web')
            ->get('/get-hashtags?countryId=ZZ&industryId=999&opportunity_city=999')
            ->assertJson(['hashTags' => '']);
    }

    public function test_opportunity_city_lookup_and_vouch_status_use_saved_records()
    {
        $user = User::findOrFail($this->createUser('opportunity-vouch@example.test', 1));
        $opportunityId = DB::table('opportunities')->insertGetId([
            'receiver_id' => $user->id,
            'hashtag' => '#synthetic',
            'country_code' => 'ZZ',
            'industry_id' => 999,
            'status' => 1,
        ]);
        $vouchId = DB::table('vouches')->insertGetId([
            'offer_id' => $user->id,
            'opportunity_id' => $opportunityId,
            'vouch_value' => 'Synthetic value',
            'is_active' => 'On',
        ]);

        $this->actingAs($user, 'web')
            ->get('/get-cities/' . $opportunityId)
            ->assertJsonFragment([
                'hashtag' => '#synthetic',
                'country_code' => 'ZZ',
            ]);

        $this->postJson('/change-opportunity-status', [
            'id' => $vouchId,
            'status' => 'On',
        ])->assertJson([
            'status' => true,
            'message' => 'Vouch status Has been Changed.',
        ]);

        $this->assertSame('Off', DB::table('vouches')->where('id', $vouchId)->value('is_active'));
    }

    public function test_user_can_view_their_vouches()
    {
        $user = User::findOrFail($this->createUser('my-vouches@example.test', 1));

        $this->actingAs($user, 'web')
            ->get('/my-vouches')
            ->assertStatus(200);
    }

    public function test_user_can_add_a_vouch_for_a_matching_opportunity()
    {
        $offerUser = User::findOrFail($this->createUser('vouch-offer@example.test', 1));
        $receiver = User::findOrFail($this->createUser('vouch-receiver@example.test', 2));
        $opportunityId = DB::table('opportunities')->insertGetId([
            'receiver_id' => $receiver->id,
            'hashtag' => '#vouch-test',
            'country_code' => 'ZZ',
            'opportunity_city' => 123,
            'industry_id' => 999,
            'status' => 1,
        ]);

        $this->actingAs($offerUser, 'web')
            ->postJson('/add-new-vouch', [
                'opportunity_id' => $opportunityId,
                'vouch_value' => 'Synthetic contribution',
                'vouch_identity' => 'Synthetic identity',
                'vouch_city' => 123,
                'vouch_country' => 'ZZ',
                'vouch_contacted' => 'Email',
                'type' => 'free',
            ])
            ->assertJson([
                'status' => 1,
                'message' => 'Vouche has been successfully added',
            ]);

        $this->assertDatabaseHas('vouches', [
            'offer_id' => $offerUser->id,
            'opportunity_id' => $opportunityId,
            'vouch_value' => 'Synthetic contribution',
            'status' => 1,
        ]);
        $this->assertSame(1, (int) DB::table('users')->where('id', $offerUser->id)->value('free_vouch'));
    }

    public function test_opportunity_and_vouch_creation_validate_required_fields_before_side_effects()
    {
        $user = User::findOrFail($this->createUser('opportunity-validation@example.test', 1));
        $this->actingAs($user, 'web');

        $this->postJson('/add-new-vouch', [])
            ->assertJson([
                'status' => 0,
                'message' => '"Vouch value is required"',
            ]);

        $this->from('/build-opportunity')
            ->post('/add-new-opportunity', [])
            ->assertRedirect('/build-opportunity')
            ->assertSessionHas('error_contact', 'Please fill all fields data');

        $this->assertSame(0, DB::table('opportunities')->count());
    }
}
