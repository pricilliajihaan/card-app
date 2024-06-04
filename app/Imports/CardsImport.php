<?php

namespace App\Imports;

use App\Models\Card;
use Maatwebsite\Excel\Concerns\ToModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CardsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        Log::info('Row data: ' . json_encode($row));

        try {
            // Parse start date
            $startDate = null;
            if (is_numeric($row['tahun_masuk'])) {
                // Numeric date format (e.g., Excel date)
                $startDate = Carbon::parse(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tahun_masuk']));
            } else {
                // Date format (dd/mm/yyyy)
                $startDate = Carbon::createFromFormat('d/m/Y', $row['tahun_masuk']);
            }
        } catch (\Exception $e) {
            Log::error('Error parsing start_date: ' . $row['tahun_masuk'] . ' - ' . $e->getMessage());
        }

        // Check if a record with the same email, phone number, and start date exists
        $existingRecord = Card::where('email', $row['email'])
                            ->where('phone_number', $row['nomor_hp_whatsapp'])
                            ->where('start_date', $startDate)
                            ->first();

        if (!$existingRecord) {
            return new Card([
                'name' => $row['nama_lengkap'],
                'email' => $row['email'],
                'phone_number' => $row['nomor_hp_whatsapp'],
                'start_date' => $startDate,
                'years_of_service' => $startDate ? $startDate->diffInYears(Carbon::now()) : 0,
                'gender' => $row['jenis_kelamin'],
            ]);
        }

        return null;
    }
}
