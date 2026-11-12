<?php

namespace App\Imports;

use App\Enums\CitizenshipList;
use App\Enums\EducationList;
use App\Enums\GenderList;
use App\Enums\MaritalStatusList;
use App\Enums\OccupationList;
use App\Enums\RelationshipList;
use App\Enums\ReligionList;
use App\Enums\RtList;
use App\Enums\RwList;
use App\Models\Resident;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Throwable;

class ResidentImport implements ToCollection, WithHeadingRow
{
    protected array $rows = [];

    protected array $errors = [];

    protected array $seenNik = [];

   public function collection(Collection $collection): void
    {
        foreach ($collection as $index => $row) {
            $rowNumber = (int) $index + 2;

            // Normalisasi nama header Excel.
            $row = $this->normalizeRow($row->toArray());

            // Lewati baris yang benar-benar kosong.
            if ($this->isEmptyRow($row)) {
                continue;
            }

            // Ambil identitas utama penduduk.
            $namaLengkap = $this->getValue($row, 'nama_lengkap');
            $nik = $this->getValue($row, 'nik');

            // Lewati baris jika nama dan NIK sama-sama kosong.
            if ($namaLengkap === null && $nik === null) {
                continue;
            }

            $errorCountBefore = count($this->errors);

            $data = [];

            // A. Identitas Penduduk
            $this->validateField(
                $data,
                $row,
                'nama_lengkap',
                'nama_lengkap',
                $rowNumber
            );

            $this->validateField(
                $data,
                $row,
                'nik',
                'nik',
                $rowNumber,
                fn ($value) => $this->validateNumberLength($value, 16, 'NIK')
            );

            $this->validateField(
                $data,
                $row,
                'kk',
                'kk',
                $rowNumber,
                fn ($value) => $this->validateNumberLength($value, 16, 'KK')
            );

            $this->validateEnum(
                $data,
                $row,
                'jenis_kelamin',
                'jenis_kelamin',
                GenderList::class,
                $rowNumber
            );

            $this->validateEnum(
                $data,
                $row,
                'status_perkawinan',
                'status_perkawinan',
                MaritalStatusList::class,
                $rowNumber
            );

            $this->validateField(
                $data,
                $row,
                'tempat_lahir',
                'tempat_lahir',
                $rowNumber
            );

            $this->validateDate($data, $row, $rowNumber);

            // B. Data Kependudukan
            $this->validateEnum(
                $data,
                $row,
                'agama',
                'agama',
                ReligionList::class,
                $rowNumber
            );

            $this->validateEnum(
                $data,
                $row,
                'pendidikan',
                'pendidikan_terakhir',
                EducationList::class,
                $rowNumber
            );

            $this->validateEnum(
                $data,
                $row,
                'pekerjaan',
                'pekerjaan',
                OccupationList::class,
                $rowNumber
            );

            $this->validateBoolean($data, $row, $rowNumber);

            $this->validateEnum(
                $data,
                $row,
                'kewarganegaraan',
                'kewarganegaraan',
                CitizenshipList::class,
                $rowNumber
            );

            $this->validateField(
                $data,
                $row,
                'kebangsaan',
                'kebangsaan',
                $rowNumber
            );

            $this->validateEnum(
                $data,
                $row,
                'status_dalam_keluarga',
                'kedudukan_dlm_keluarga',
                RelationshipList::class,
                $rowNumber
            );

            // C. Alamat Tempat Tinggal
            $this->validateEnum(
                $data,
                $row,
                'rt',
                'rt',
                RtList::class,
                $rowNumber
            );

            $this->validateEnum(
                $data,
                $row,
                'rw',
                'rw',
                RwList::class,
                $rowNumber
            );

            // D. Keterangan (opsional).
            $data['catatan'] = $this->getValue($row, 'ket');

            // Periksa NIK duplikat.
            if ($nik !== null) {
                if (isset($this->seenNik[$nik])) {
                    $this->addError(
                        $rowNumber,
                        'NIK',
                        "NIK {$nik} duplikat dengan baris {$this->seenNik[$nik]}."
                    );
                } else {
                    $this->seenNik[$nik] = $rowNumber;
                }

                // Periksa NIK di database, termasuk soft deleted.
                if (Resident::withTrashed()->where('nik', $nik)->exists()) {
                    $this->addError(
                        $rowNumber,
                        'NIK',
                        "NIK {$nik} sudah terdaftar di database."
                    );
                }
            }

            // Simpan hanya baris yang lolos seluruh validasi.
            if (count($this->errors) === $errorCountBefore) {
                $this->rows[] = $data;
            }
        }
    }

    /**
     * Normalisasi nama header Excel.
     */
    protected function normalizeRow(array $row): array
    {
        $normalized = [];

        foreach ($row as $heading => $value) {
            $heading = preg_replace(
                '/^\xEF\xBB\xBF/',
                '',
                (string) $heading
            );

            $heading = mb_strtolower(trim($heading));

            $heading = preg_replace('/[^a-z0-9]+/u', '_', $heading);

            $heading = trim($heading, '_');

            if ($heading !== '') {
                $normalized[$heading] = $value;
            }
        }

        // Alias nama kolom untuk mengakomodasi variasi header Excel.
        $aliases = [
            'status_perkawinan' => [
                'status_kawin',
                'status_pernikahan',
            ],
            'pendidikan_terakhir' => [
                'pendidikan',
            ],
            'kedudukan_dlm_keluarga' => [
                'kedudukan_dalam_keluarga',
                'status_dalam_keluarga',
                'hubungan_dalam_keluarga',
            ],
            'tanggal_lahir' => [
                'tgl_lahir',
            ],
            'dapat_membaca_huruf' => [
                'dapat_membaca',
            ],
            'ket' => [
                'catatan',
                'keterangan',
            ],
        ];

        foreach ($aliases as $canonical => $alternatives) {
            if (!array_key_exists($canonical, $normalized)) {
                foreach ($alternatives as $alternative) {
                    if (array_key_exists($alternative, $normalized)) {
                        $normalized[$canonical] = $normalized[$alternative];
                        break;
                    }
                }
            }
        }

        return $normalized;
    }

    /**
     * Mengambil nilai berdasarkan nama kolom.
     */
    protected function getValue(array $row, string $heading): ?string
    {
        return $this->clean($row[$heading] ?? null);
    }

    protected function validateField(
        array &$data,
        array $row,
        string $column,
        string $heading,
        int $rowNumber,
        ?callable $callback = null
    ): void {
        $value = $this->getValue($row, $heading);

        if ($value === null) {
            $this->addError(
                $rowNumber,
                strtoupper($heading),
                'Kolom ini wajib diisi.'
            );

            return;
        }

        if ($callback) {
            try {
                $callback($value);
            } catch (Throwable $e) {
                $this->addError(
                    $rowNumber,
                    strtoupper($heading),
                    $e->getMessage()
                );

                return;
            }
        }

        $data[$column] = $value;
    }

    protected function validateEnum(
        array &$data,
        array $row,
        string $column,
        string $heading,
        string $enum,
        int $rowNumber
    ): void {
        $value = $this->getValue($row, $heading);

        if ($value === null) {
            $this->addError(
                $rowNumber,
                strtoupper($heading),
                'Kolom ini wajib diisi.'
            );

            return;
        }

        $normalized = $this->normalizeText($value);

        // Normalisasi jenis kelamin.
        if ($enum === GenderList::class) {
            $normalized = match ($normalized) {
                'laki laki', 'laki', 'pria' => 'l',
                'perempuan', 'wanita' => 'p',
                default => $normalized,
            };
        }

        // Normalisasi kewarganegaraan.
        if ($enum === CitizenshipList::class) {
            $normalized = match ($normalized) {
                'warga negara indonesia', 'indonesia' => 'wni',
                'warga negara asing', 'asing' => 'wna',
                default => $normalized,
            };
        }

        $matched = null;

        foreach ($enum::cases() as $case) {
            $caseValue = $this->normalizeText((string) $case->value);
            $caseLabel = $this->normalizeText((string) $case->label());

            if (
                $normalized === $caseValue ||
                $normalized === $caseLabel
            ) {
                $matched = $case->value;
                break;
            }
        }

        if ($matched === null) {
            $this->addError(
                $rowNumber,
                strtoupper($heading),
                "Nilai '{$value}' tidak sesuai pilihan yang tersedia."
            );

            return;
        }

        $data[$column] = $matched;
    }

    protected function validateDate(
        array &$data,
        array $row,
        int $rowNumber
    ): void {
        $value = $row['tanggal_lahir'] ?? null;

        if ($value === null || trim((string) $value) === '') {
            $this->addError(
                $rowNumber,
                'TANGGAL_LAHIR',
                'Tanggal lahir wajib diisi.'
            );

            return;
        }

        try {
            if (is_numeric($value)) {
                $date = Carbon::instance(
                    ExcelDate::excelToDateTimeObject((float) $value)
                )->startOfDay();
            } else {
                $value = trim((string) $value);
                $date = null;

                foreach (['d/m/Y', 'd-m-Y', 'Y-m-d'] as $format) {
                    try {
                        $parsed = Carbon::createFromFormat(
                            '!' . $format,
                            $value
                        );

                        if ($parsed && $parsed->format($format) === $value) {
                            $date = $parsed;
                            break;
                        }
                    } catch (Throwable) {
                        // Coba format berikutnya.
                    }
                }

                if (!$date) {
                    throw new \RuntimeException(
                        'Format tanggal harus DD/MM/YYYY.'
                    );
                }
            }

            if ($date->isFuture()) {
                throw new \RuntimeException(
                    'Tanggal lahir tidak boleh melebihi hari ini.'
                );
            }

            $data['tgl_lahir'] = $date->format('Y-m-d');
        } catch (Throwable $e) {
            $this->addError(
                $rowNumber,
                'TANGGAL_LAHIR',
                $e->getMessage() ?: 'Format tanggal lahir tidak valid.'
            );
        }
    }

    protected function validateBoolean(
        array &$data,
        array $row,
        int $rowNumber
    ): void {
        $value = $this->getValue($row, 'dapat_membaca_huruf');

        if ($value === null) {
            $this->addError(
                $rowNumber,
                'DAPAT_MEMBACA_HURUF',
                'Kolom ini wajib diisi.'
            );

            return;
        }

        $normalized = $this->normalizeText($value);

        $trueValues = [
            '1',
            'ya',
            'bisa',
            'dapat',
            'mampu',
            'true',
            'yes',
        ];

        $falseValues = [
            '0',
            'tidak',
            'tidak bisa',
            'false',
            'no',
        ];

        if (in_array($normalized, $trueValues, true)) {
            $data['dapat_membaca'] = true;
        } elseif (in_array($normalized, $falseValues, true)) {
            $data['dapat_membaca'] = false;
        } else {
            $this->addError(
                $rowNumber,
                'DAPAT_MEMBACA_HURUF',
                "Nilai '{$value}' tidak valid. Isi dengan Ya atau Tidak."
            );
        }
    }

    protected function validateNumberLength(
        string $value,
        int $length,
        string $label
    ): void {
        if (!preg_match('/^\d{' . $length . '}$/', $value)) {
            throw new \RuntimeException(
                "{$label} harus terdiri dari {$length} digit angka."
            );
        }
    }

    protected function clean(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    protected function normalizeText(mixed $value): string
    {
        return mb_strtolower(
            preg_replace('/\s+/', ' ', trim((string) $value))
        );
    }

    protected function isEmptyRow(array $row): bool
    {
        foreach ($row as $value) {
            if ($this->clean($value) !== null) {
                return false;
            }
        }

        return true;
    }

    protected function addError(
        int $row,
        string $column,
        string $message
    ): void {
        $this->errors[] = [
            'row' => $row,
            'column' => strtoupper($column),
            'message' => $message,
        ];
    }

    public function getValidRows(): array
    {
        return $this->rows;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
