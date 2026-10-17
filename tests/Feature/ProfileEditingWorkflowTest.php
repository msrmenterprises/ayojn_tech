<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class ProfileEditingWorkflowTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesTestUsers;

    public function test_profile_edit_rejects_an_existing_email_and_marks_successful_edits_for_review()
    {
        $user = User::findOrFail($this->createUser('profile-owner@example.test', 2));
        $otherUser = User::findOrFail($this->createUser('existing-profile@example.test', 2));
        $this->actingAs($user, 'web');

        $this->postJson('/edituserprofile', [
            'useremail_edit' => $otherUser->email,
        ])->assertJson([
            'status' => 0,
            'msg' => 'Email Address is already in use',
        ]);

        $this->postJson('/edituserprofile', [
            'useremail_edit' => 'updated-profile@example.test',
            'userentity_edit' => 'Synthetic Entity',
            'username_edit' => 'synthetic-profile',
            'identity_edit' => 'Synthetic Identity',
            'phone_no_edit' => '5550100',
            'sq1_edit' => 'Synthetic answer',
        ])->assertJson([
            'status' => 1,
            'msg' => 'Profile Edited Successfully.',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'updated-profile@example.test',
            'entity' => 'Synthetic Entity',
            'username' => 'synthetic-profile',
            'identity' => 'Synthetic Identity',
            'phone_no' => '5550100',
            'sq1' => 'Synthetic answer',
            'userstatus' => 0,
            'is_edited' => 1,
            'edit_count' => 1,
        ]);
        $this->assertSame(1, DB::table('users')->where('email', $otherUser->email)->count());
    }
}
