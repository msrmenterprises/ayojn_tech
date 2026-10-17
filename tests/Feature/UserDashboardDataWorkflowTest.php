<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class UserDashboardDataWorkflowTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesTestUsers;

    public function test_dashboard_map_and_top_destination_data_use_active_synthetic_accounts()
    {
        $viewer = User::findOrFail($this->createUser('dashboard-data-viewer@example.test', 1, '000000', [
            'sponsor_country' => 'ZZ',
        ]));
        $this->createUser('dashboard-data-receiver@example.test', 2, '000000', [
            'country' => 'ZZ',
            'sponsor_for' => 'Event',
            'sponsor_budget' => 'Less than 2000',
            'userstatus' => 1,
        ]);

        $this->actingAs($viewer, 'web');

        $this->postJson('/mapdata', ['countryId' => 'ZZ'])
            ->assertOk()
            ->assertSee('Average Price of Event Sponsorship')
            ->assertSee('Less than USD 2000');

        $this->postJson('/topcountriesdata')
            ->assertOk()
            ->assertSee('Synthetic Test Country');

        $this->postJson('/topdestinationdata')
            ->assertOk()
            ->assertSee('Synthetic Test Country');

        $this->getJson('/getUserCount/' . $viewer->id)
            ->assertOk()
            ->assertJsonFragment([
                'id' => $viewer->id,
                'email' => 'dashboard-data-viewer@example.test',
            ]);
    }

    public function test_dashboard_map_does_not_count_inactive_or_other_country_users()
    {
        $viewer = User::findOrFail($this->createUser('dashboard-map-filter-viewer@example.test', 1));
        $this->createUser('dashboard-map-inactive@example.test', 2, '000000', [
            'country' => 'ZZ',
            'sponsor_for' => 'Event',
            'sponsor_budget' => 'Less than 2000',
            'userstatus' => 0,
        ]);
        $this->createUser('dashboard-map-other-country@example.test', 2, '000000', [
            'country' => 'YY',
            'sponsor_for' => 'Event',
            'sponsor_budget' => 'Less than 2000',
            'userstatus' => 1,
        ]);

        $this->actingAs($viewer, 'web')
            ->postJson('/mapdata', ['countryId' => 'ZZ'])
            ->assertOk()
            ->assertSee('Based on 0 inputs');
    }
}
