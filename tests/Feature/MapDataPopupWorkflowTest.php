<?php

namespace Tests\Feature;

use App\SponsorrSpecify;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class MapDataPopupWorkflowTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesTestUsers;

    public function test_map_popup_reports_sponsorship_categories_budgets_and_specifications()
    {
        $offerRequester = User::findOrFail($this->createUser('map-popup-offer-requester@example.test', 1));
        $receiveRequester = User::findOrFail($this->createUser('map-popup-receive-requester@example.test', 2));
        DB::table('users')->whereIn('id', [$offerRequester->id, $receiveRequester->id])->update(['userstatus' => 0]);

        $categories = [
            'Event',
            'Campaign',
            'Content',
            'Sports Team',
            'Other',
            'Venue',
            'Not for Profit',
            'Performing Arts',
            'Think Tank',
            'Knowledge Pool',
            'Online Events',
            'Research',
        ];
        $budgets = [
            'Less than 2000',
            'Between 2000-5000',
            'Between 5000-20000',
            'Above 20000',
        ];
        $usersByCategoryAndBudget = [];
        $allUserIds = [];

        foreach ($categories as $categoryIndex => $category) {
            foreach ($budgets as $budgetIndex => $budget) {
                $userId = $this->createUser(
                    'map-popup-' . $categoryIndex . '-' . $budgetIndex . '@example.test',
                    2,
                    '000000',
                    [
                        'sponsor_for' => $category,
                        'sponsor_budget' => $budget,
                    ]
                );

                $usersByCategoryAndBudget[$category][$budget] = $userId;
                $allUserIds[] = $userId;
            }
        }

        foreach (range(1, 103) as $specification) {
            SponsorrSpecify::create([
                'user_id' => $usersByCategoryAndBudget['Event'][$budgets[0]],
                'specify_name' => $specification,
            ]);
        }

        $this->setActiveMapUsers($allUserIds, $allUserIds);
        $html = $this->renderMapPopup($offerRequester);
        $dashboardHtml = $this->renderDashboardMap($offerRequester);
        $this->get('/map')->assertOk();
        $this->get('/redeem-request')->assertOk();
        $this->postJson('/topindustriesdata')->assertOk();

        $this->assertStringContainsString('Average Price of Event Sponsorship', $html);
        $this->assertStringContainsString('Average Price of Event Sponsorship', $dashboardHtml);
        $this->assertStringContainsString('Conference', $html);
        $this->assertStringContainsString('Conference', $dashboardHtml);
        $this->assertStringContainsString('Cricket', $html);
        $this->assertStringContainsString('Not Decided yet', $html);
        $this->assertStringContainsString('Above USD 20000', $html);
        $this->assertStringContainsString('Based on 4 inputs', $html);

        foreach ($budgets as $budget) {
            $activeIds = [];
            foreach ($categories as $category) {
                $activeIds[] = $usersByCategoryAndBudget[$category][$budget];
            }

            $this->setActiveMapUsers($allUserIds, $activeIds);
            $html = $this->renderMapPopup($offerRequester);
            $dashboardHtml = $this->renderDashboardMap($offerRequester);
            $this->assertStringContainsString($this->budgetMessage($budget), $html);
            $this->assertStringContainsString($this->budgetMessage($budget), $dashboardHtml);
        }

        $this->setActiveMapUsers($allUserIds, []);
        $html = $this->renderMapPopup($offerRequester);
        $dashboardHtml = $this->renderDashboardMap($offerRequester);
        $this->assertStringContainsString('Based on 0 inputs', $html);
        $this->assertStringContainsString('Based on 0 inputs', $dashboardHtml);

        DB::table('users')->whereIn('id', $allUserIds)->update(['sponsor_type' => 1]);
        $thinkTankId = $usersByCategoryAndBudget['Think Tank'][$budgets[0]];
        $this->setActiveMapUsers($allUserIds, [$thinkTankId]);
        $html = $this->renderMapPopup($receiveRequester);
        $dashboardHtml = $this->renderDashboardMap($receiveRequester);

        $this->assertStringContainsString('Average Sponsorship Offered for Events', $html);
        $this->assertStringContainsString('Average Sponsorship Offered for Events', $dashboardHtml);
        $this->assertSectionContains($html, 'Average Sponsorship Offered for Think Tank', 'Based on 1 inputs');

        $knowledgePoolId = $usersByCategoryAndBudget['Knowledge Pool'][$budgets[0]];
        $this->setActiveMapUsers($allUserIds, [$knowledgePoolId]);
        $html = $this->renderMapPopup($receiveRequester);
        $this->assertSectionContains($html, 'Average Sponsorship Offered for Knowledge Pool', 'aria-valuenow="0.1"');
    }

    private function renderDashboardMap(User $requester)
    {
        $response = $this->actingAs($requester, 'web')
            ->postJson('/mapdata', ['countryId' => 'ZZ'])
            ->assertOk();
        $html = $response->getData(true);

        $this->assertIsString($html);

        return $html;
    }

    private function setActiveMapUsers(array $allUserIds, array $activeUserIds)
    {
        DB::table('users')->whereIn('id', $allUserIds)->update(['userstatus' => 0]);

        if ($activeUserIds) {
            DB::table('users')->whereIn('id', $activeUserIds)->update(['userstatus' => 1]);
        }
    }

    private function renderMapPopup(User $requester)
    {
        $response = $this->actingAs($requester, 'web')
            ->post('/mapdata2', ['countryId' => 'ZZ'])
            ->assertOk();
        $html = json_decode($response->getContent(), true);

        $this->assertIsString($html);

        return $html;
    }

    private function assertSectionContains($html, $heading, $expected)
    {
        $sectionStart = strpos($html, $heading);

        $this->assertNotFalse($sectionStart, 'The expected map section heading was not rendered.');
        $section = substr($html, $sectionStart, 2000);

        $this->assertStringContainsString($expected, $section);
    }

    private function budgetMessage($budget)
    {
        $messages = [
            'Less than 2000' => 'Less than USD 2000',
            'Between 2000-5000' => 'Between USD 2000-USD 5000',
            'Between 5000-20000' => 'Between USD 5000-USD 20000',
            'Above 20000' => 'Above USD 20000',
        ];

        return $messages[$budget];
    }
}
