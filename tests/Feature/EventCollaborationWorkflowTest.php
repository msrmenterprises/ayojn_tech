<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class EventCollaborationWorkflowTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesTestUsers;

    public function test_authenticated_user_can_view_events()
    {
        $user = User::findOrFail($this->createUser('events@example.test', 1));

        $this->actingAs($user, 'web')
            ->get('/events')
            ->assertStatus(200);
    }

    public function test_authenticated_user_can_view_collaborations()
    {
        $user = User::findOrFail($this->createUser('collaborations@example.test', 1));

        $this->actingAs($user, 'web')
            ->get('/collaborate')
            ->assertStatus(200);
    }

    public function test_event_creation_returns_specific_validation_errors()
    {
        $user = User::findOrFail($this->createUser('event-validation@example.test', 1));
        $this->actingAs($user, 'web');

        $this->postJson('/add-event', [])
            ->assertStatus(200)
            ->assertJson(['status' => 0, 'message' => 'Please enter event title']);

        $baseEvent = [
            'event_title' => 'Synthetic test event',
            'event_date' => '2030-01-01',
            'timezone' => 'UTC',
            'event_type' => 'Physical',
            'event_free_paid' => 'Free',
        ];

        $this->postJson('/add-event', $baseEvent)
            ->assertStatus(200)
            ->assertJson(['status' => 0, 'message' => 'Please enter location']);

        $paidEvent = array_merge($baseEvent, [
            'event_type' => 'Virtual',
            'event_free_paid' => 'Paid',
        ]);

        $this->postJson('/add-event', $paidEvent)
            ->assertStatus(200)
            ->assertJson(['status' => 0, 'message' => 'Please enter fee']);
    }

    public function test_event_can_be_created_attended_and_cancelled()
    {
        $organizer = User::findOrFail($this->createUser('event-organizer@example.test', 1));
        $this->actingAs($organizer, 'web')
            ->postJson('/add-event', [
                'event_title' => 'Synthetic regression event',
                'event_date' => '2030-01-01',
                'timezone' => 'UTC',
                'event_type' => 'Virtual',
                'event_free_paid' => 'Free',
            ])
            ->assertJson([
                'status' => 1,
                'message' => 'This event is pending for approval.',
            ]);

        $event = DB::table('events')->where('user_id', $organizer->id)->first();
        $this->assertNotNull($event);
        $this->assertNotEmpty($event->share_id);
        $this->assertSame(0, (int) $event->status);

        $attendee = User::findOrFail($this->createUser('event-attendee@example.test', 2));
        $this->actingAs($attendee, 'web')
            ->post('/attend-event/' . $event->id)
            ->assertStatus(200);

        $attendeeRecord = DB::table('attendees')
            ->where('event_id', $event->id)
            ->where('user_id', $attendee->id)
            ->first();
        $this->assertNotNull($attendeeRecord);
        $this->assertSame(0, (int) $attendeeRecord->status);

        $this->post('/cancel-event/' . $attendeeRecord->id)
            ->assertStatus(200);

        $this->assertSame(2, (int) DB::table('attendees')
            ->where('id', $attendeeRecord->id)
            ->value('status'));
    }

    public function test_collaboration_can_be_created_and_receive_a_response()
    {
        $organizer = User::findOrFail($this->createUser('collaboration-organizer@example.test', 1));
        $this->actingAs($organizer, 'web')
            ->postJson('/add-collborations', [
                'collaborate_for' => 'Synthetic collaboration',
                'sub' => ['Synthetic focus'],
                'remote_opportunity' => 'No',
                'geo_focus' => 'ZZ',
                'industry_focus' => 1,
                'with_local_focus' => 'No',
                'collaborate_with' => 'Synthetic partner',
                'budget' => '1000',
                'objective' => 'Synthetic objective',
                'expiry_date' => '2030-01-01',
            ])
            ->assertStatus(200);

        $collaboration = DB::table('collaborates')->where('user_id', $organizer->id)->first();
        $this->assertNotNull($collaboration);
        $this->assertSame('Synthetic focus', $collaboration->sub);
        $this->assertSame(1, (int) $collaboration->status);
        $this->assertNotEmpty($collaboration->share_id);

        $respondent = User::findOrFail($this->createUser('collaboration-respondent@example.test', 2));
        $this->actingAs($respondent, 'web')
            ->postJson('/add-collaboration-response', [
                'col_id' => $collaboration->id,
                'description' => 'Synthetic collaboration response',
                'portfolio_link' => 'https://example.test/collaboration',
            ])
            ->assertJson([
                'status' => true,
                'message' => 'Collaboration response Has been Added.',
            ]);

        $this->assertDatabaseHas('col_responses', [
            'col_id' => $collaboration->id,
            'respond_user_id' => $respondent->id,
            'description' => 'Synthetic collaboration response',
            'status' => 0,
        ]);
    }
}
