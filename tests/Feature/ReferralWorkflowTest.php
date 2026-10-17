<?php

namespace Tests\Feature;

use App\ReferAFriend;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class ReferralWorkflowTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesTestUsers;

    public function test_referral_request_requires_at_least_one_email_address()
    {
        $user = User::findOrFail($this->createUser('referral-empty@example.test', 1));
        Mail::shouldReceive('send')->never();

        $this->actingAs($user, 'web')
            ->postJson('/referAFriend', [])
            ->assertStatus(200)
            ->assertJson([
                'status' => '0',
                'msg' => 'Atleast Enter One Email Address',
            ]);

        $this->assertSame(0, DB::table('refer_a_friends')->where('user_id', $user->id)->count());
    }

    public function test_referral_request_records_up_to_ten_recommendations_and_approves_three_referrals()
    {
        $user = User::findOrFail($this->createUser('referral-owner@example.test', 1));
        Mail::shouldReceive('send')->times(10);

        $this->actingAs($user, 'web')
            ->postJson('/referAFriend', [
                'emailone' => 'referral-01@example.test',
                'emailtwo' => 'referral-02@example.test',
                'emailthree' => 'referral-03@example.test',
                'emailfour' => 'referral-04@example.test',
                'emailfive' => 'referral-05@example.test',
                'emailsix' => 'referral-06@example.test',
                'emailseven' => 'referral-07@example.test',
                'emaileight' => 'referral-08@example.test',
                'emailnine' => 'referral-09@example.test',
                'emailten' => 'referral-10@example.test',
            ])
            ->assertStatus(200)
            ->assertJson([
                'status' => '1',
                'msg' => 'Recommendations Recorded.',
            ]);

        $this->assertSame(10, DB::table('refer_a_friends')->where('user_id', $user->id)->count());
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_3refer' => 1,
        ]);
        $this->assertNotNull(DB::table('users')->where('id', $user->id)->value('disapprove_time'));
    }

    public function test_duplicate_referral_is_not_resent_or_recorded_twice()
    {
        $user = User::findOrFail($this->createUser('referral-duplicate-owner@example.test', 1));
        ReferAFriend::create([
            'user_id' => $user->id,
            'email_address' => 'already-referred@example.test',
        ]);
        Mail::shouldReceive('send')->never();

        $this->actingAs($user, 'web')
            ->postJson('/referAFriend', [
                'emailone' => 'already-referred@example.test',
            ])
            ->assertStatus(200)
            ->assertJson([
                'status' => 0,
                'msg' => 'Recommended Email already exists! You can recommend someone else instead.',
            ]);

        $this->assertSame(1, DB::table('refer_a_friends')
            ->where('user_id', $user->id)
            ->where('email_address', 'already-referred@example.test')
            ->count());
    }
}
