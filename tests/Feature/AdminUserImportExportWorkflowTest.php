<?php

namespace Tests\Feature;

use App\ReferAFriend;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class AdminUserImportExportWorkflowTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesTestUsers;

    public function test_admin_can_export_user_rows_with_headings_and_expected_values()
    {
        $admin = User::findOrFail($this->createUser('admin-export-users@example.test', 0, '000000', [
            'role_id' => 1,
        ]));
        $user = User::findOrFail($this->createUser('export-target@example.test', 1, '000000', [
            'sponsor_for' => 'Synthetic Event',
            'sponsor_budget' => 'Less than 2000',
        ]));
        Excel::fake();

        $this->actingAs($admin, 'controlpanel')
            ->post('/controlpanel/exportUser')
            ->assertStatus(200);

        Excel::assertDownloaded('User List.xlsx', function ($export) use ($user) {
            $headings = $export->headings();
            $rows = $export->array();
            $emailIndex = array_search('Email', $headings, true);
            $typeIndex = array_search('Sponsor Type', $headings, true);
            $eventIndex = array_search('Sponsor For', $headings, true);

            if ($emailIndex === false || $typeIndex === false || $eventIndex === false) {
                return false;
            }

            foreach ($rows as $row) {
                if ($row[$emailIndex] === $user->email) {
                    return $row[$typeIndex] === 'Offer Sponsorship'
                        && $row[$eventIndex] === 'Synthetic Event';
                }
            }

            return false;
        });
    }

    public function test_admin_can_export_referrals_and_sample_data()
    {
        $admin = User::findOrFail($this->createUser('admin-export-referrals@example.test', 0, '000000', [
            'role_id' => 1,
        ]));
        $user = User::findOrFail($this->createUser('referral-export-owner@example.test', 1));
        ReferAFriend::create([
            'user_id' => $user->id,
            'email_address' => 'referral-export-target@example.test',
        ]);
        DB::table('sponsorr_specify_lists')->insert([
            'specify_name' => 'Synthetic sample platform',
            'sponsorr_type' => 'Synthetic Event',
        ]);
        Excel::fake();

        $this->actingAs($admin, 'controlpanel')
            ->post('/controlpanel/exportReferUser')
            ->assertStatus(200);

        Excel::assertDownloaded('User Refer List.xlsx', function ($export) use ($user) {
            $headings = $export->headings();
            $rows = $export->array();
            $ownerEmailIndex = array_search('User Email', $headings, true);
            $referralEmailIndex = array_search('Email', $headings, true);

            if ($ownerEmailIndex === false || $referralEmailIndex === false) {
                return false;
            }

            foreach ($rows as $row) {
                if ($row[$ownerEmailIndex] === $user->email) {
                    return $row[$referralEmailIndex] === 'referral-export-target@example.test';
                }
            }

            return false;
        });

        $this->get('/controlpanel/sampleData')->assertStatus(200);
        Excel::assertDownloaded('Sample Data List.xlsx', function ($export) {
            return $export->headings() === ['Spex Platform', 'Sponsor For']
                && in_array(['Synthetic sample platform', 'Synthetic Event'], $export->array(), true);
        });
    }

    public function test_admin_can_import_a_valid_csv_user_row()
    {
        $this->withoutExceptionHandling();
        $admin = User::findOrFail($this->createUser('admin-import-user@example.test', 0, '000000', [
            'role_id' => 1,
        ]));
        $this->createUser('country-seed@example.test', 1);
        $defaultUserType = DB::table('user_type')->orderBy('id')->value('id');
        $industryId = DB::table('industries')->insertGetId([
            'name' => 'Synthetic Import Industry',
        ]);
        $platform = 'Synthetic Import Platform';
        $specificationId = DB::table('sponsorr_specify_lists')->insertGetId([
            'specify_name' => $platform,
            'sponsorr_type' => 'Synthetic Event',
        ]);
        $email = 'imported-user@example.test';
        $upload = $this->csvUpload(
            "sponsorr_type,email,country_of_origin,country_directed_to,sponsors_industry,sponsor_for,sponsor_deal_size,spex_platform\n" .
            "Offer Sponsorship,{$email},Synthetic Test Country,Synthetic Test Country,Synthetic Import Industry,Synthetic Event,Less than 2000,{$platform}\n"
        );

        try {
            $this->actingAs($admin, 'controlpanel')
                ->from('/controlpanel/import-users')
                ->post('/controlpanel/import-excel', ['lead_file' => $upload])
                ->assertRedirect('/controlpanel/import-users')
                ->assertSessionHas('success', 'Users Upload successfully!');
        } finally {
            unlink($upload->getPathname());
        }

        $importedUser = User::where('email', $email)->firstOrFail();
        $this->assertSame(1, (int) $importedUser->sponsor_type);
        $this->assertSame('Synthetic Event', $importedUser->sponsor_for);
        $this->assertSame('Less than 2000', $importedUser->sponsor_budget);
        $this->assertSame('ZZ', $importedUser->country);
        $this->assertSame('ZZ', $importedUser->sponsor_country);
        $this->assertSame($industryId, (int) $importedUser->sponsor_industry);
        $this->assertSame((int) $defaultUserType, (int) $importedUser->user_type);
        $this->assertSame(1, (int) $importedUser->userstatus);
        $this->assertSame(1, (int) $importedUser->email_verified);
        $this->assertDatabaseHas('sponsorr_specifies', [
            'user_id' => $importedUser->id,
            'specify_name' => $specificationId,
        ]);
    }

    public function test_admin_import_reports_duplicate_and_unknown_country_rows()
    {
        $admin = User::findOrFail($this->createUser('admin-import-errors@example.test', 0, '000000', [
            'role_id' => 1,
        ]));
        $existingEmail = 'already-imported@example.test';
        $this->createUser($existingEmail, 1);
        $upload = $this->csvUpload(
            "sponsorr_type,email,country_of_origin,country_directed_to,sponsors_industry,sponsor_for,sponsor_deal_size,spex_platform\n" .
            "Offer Sponsorship,{$existingEmail},Synthetic Test Country,Synthetic Test Country,Unknown Industry,Synthetic Event,Less than 2000,Unknown Platform\n" .
            "Offer Sponsorship,unknown-country@example.test,Unknown Country,Synthetic Test Country,Unknown Industry,Synthetic Event,Less than 2000,Unknown Platform\n"
        );

        try {
            $this->actingAs($admin, 'controlpanel')
                ->from('/controlpanel/import-users')
                ->post('/controlpanel/import-excel', ['lead_file' => $upload])
                ->assertRedirect('/controlpanel/import-users')
                ->assertSessionHas('error', ' Line No 2 : User already exists Line No 3 : Country not Found');
        } finally {
            unlink($upload->getPathname());
        }

        $this->assertDatabaseMissing('users', ['email' => 'unknown-country@example.test']);
        $this->assertSame(1, DB::table('users')->where('email', $existingEmail)->count());
    }

    public function test_import_requires_a_supported_file()
    {
        $admin = User::findOrFail($this->createUser('admin-import-validation@example.test', 0, '000000', [
            'role_id' => 1,
        ]));
        $this->actingAs($admin, 'controlpanel')
            ->from('/controlpanel/import-users')
            ->post('/controlpanel/import-excel')
            ->assertRedirect('/controlpanel/import-users')
            ->assertSessionHasErrors('lead_file');

        $this->actingAs($admin, 'controlpanel')
            ->from('/controlpanel/import-users')
            ->post('/controlpanel/import-excel', [
                'lead_file' => UploadedFile::fake()->create('invalid.pdf', 1, 'application/pdf'),
            ])
            ->assertRedirect('/controlpanel/import-users')
            ->assertSessionHasErrors('lead_file');
    }

    private function csvUpload($contents)
    {
        $path = tempnam(sys_get_temp_dir(), 'admin-user-import-');
        file_put_contents($path, $contents);

        return new UploadedFile($path, 'synthetic-users.csv', 'text/csv', null, true);
    }
}
