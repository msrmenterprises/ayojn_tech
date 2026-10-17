<?php
namespace App\Imports;
use App\VouchCode; // Adjust this to your actual model

use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\Importable;

class VouchCodeImport implements ToCollection, WithHeadingRow
{
    /**
     * Convert each row from the CSV into a VouchCode model instance.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     * 
     */

    use Importable, SkipsFailures, SkipsErrors;

    public $duplicates = [];

    public function collection(Collection $rows)
    {

        foreach ($rows as $row) {
            // Clean input and make sure headers exist
            if (!isset($row['voucher_code']) || !isset($row['voucher_amount'])) {
                continue; // skip malformed rows
            }

            $voucherCode = trim($row['voucher_code']);

            // Check if voucher code already exists
            if (VouchCode::where('vouch_code', $voucherCode)->exists()) {
                $this->duplicates[] = $voucherCode;
                continue;
            }

            // Insert the new voucher code
            VouchCode::create([
                'vouch_code' => $voucherCode,
                'vouch_amount' => $row['voucher_amount'],
            ]);
        }
    }

    public function getDuplicates(): array
    {
        return $this->duplicates;
    }


    // public function model(array $row)
    // { 
     
    //     return new VouchCode([
    //         'vouch_code' => $row['voucher_code'], // CSV column must be 'code'
    //         'vouch_amount' => $row['voucher_amount'], // CSV column must be 'amount'
    //         // Add more fields as needed
    //     ]);
    // }
}
