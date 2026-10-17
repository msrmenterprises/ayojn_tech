<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class UserOnboardingWorkflowTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesTestUsers;

    public function test_user_can_save_offer_preferences()
    {
        $user = User::findOrFail($this->createUser('offer-preferences@example.test', 1));

        $this->actingAs($user, 'web')
            ->postJson('/update-offer-data', [
                'offer_sponsorr_type' => 'Synthetic event',
                'offer_sponsorr_country' => 'ZZ',
                'offer_sponsorr_budget' => '1000',
                'offer_sponsorr_industry' => 999,
                'offer_sponsorr_like' => ['Branding', 'Leads'],
            ])
            ->assertJson([
                'status' => 1,
                'msg' => 'Detail updated Successfully.',
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'sponsor_for' => 'Synthetic event',
            'sponsor_country' => 'ZZ',
            'sponsor_budget' => '1000',
            'sponsor_industry' => '999',
            'likeSponsorr' => 'Branding,Leads',
        ]);

        $this->get('/index')->assertRedirect(url('opportunity'));
    }

    public function test_account_helpers_return_user_and_city_data()
    {
        $user = User::findOrFail($this->createUser('account-helper@example.test', 2));

        $this->actingAs($user, 'web')
            ->get('/getUserCount/' . $user->id)
            ->assertJsonFragment([
                'id' => $user->id,
                'email' => 'account-helper@example.test',
            ]);

        $this->postJson('/get-city', ['countryID' => 'ZZ'])
            ->assertExactJson([]);
    }
}
