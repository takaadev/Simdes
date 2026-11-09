<?php

namespace App\Enums;

enum LandClassList: string
{
    // Kelompok Sawah (Lahan Basah)
    case S_I = 's_i';
    case S_II = 's_ii';
    case S_III = 's_iii';
    case S_IV = 's_iv';
    case S_V = 's_v';
    case S_VI = 's_vi';

        // Kelompok Darat / Tegal / Pekarangan (Lahan Kering)
    case D_I = 'd_i';
    case D_II = 'd_ii';
    case D_III = 'd_iii';
    case D_IV = 'd_iv';
    case D_V = 'd_v';
    case D_VI = 'd_vi';

        // Kelompok Tambak / Perikanan
    case T_I = 't_i';
    case T_II = 't_ii';
    case T_III = 't_iii';
    case T_IV = 't_iv';

        // Kelompok Gaga (Padi Lahan Kering)
    case GG_I = 'gg_i';
    case GG_II = 'gg_ii';
    case GG_III = 'gg_iii';

        // Kelompok Khusus
    case RAWA = 'rawa';
    case HUTAN = 'hutan';

    public function code(): string
    {
        return match ($this) {
            self::S_I => 'S.I',
            self::S_II => 'S.II',
            self::S_III => 'S.III',
            self::S_IV => 'S.IV',
            self::S_V => 'S.V',
            self::S_VI => 'S.VI',

            self::D_I => 'D.I',
            self::D_II => 'D.II',
            self::D_III => 'D.III',
            self::D_IV => 'D.IV',
            self::D_V => 'D.V',
            self::D_VI => 'D.VI',

            self::T_I => 'T.I',
            self::T_II => 'T.II',
            self::T_III => 'T.III',
            self::T_IV => 'T.IV',

            self::GG_I => 'GG.I',
            self::GG_II => 'GG.II',
            self::GG_III => 'GG.III',

            self::RAWA => 'R',
            self::HUTAN => 'H',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::S_I => 'Sawah Kelas Satu (Sangat Subur/Irigasi Teknis)',
            self::S_II => 'Sawah Kelas Dua (Subur/Irigasi Setengah Teknis)',
            self::S_III => 'Sawah Kelas Tiga (Sedang/Tadah Hujan)',
            self::S_IV => 'Sawah Kelas Empat (Kurang Subur)',
            self::S_V => 'Sawah Kelas Lima (Kurang Produktif/Rawa)',
            self::S_VI => 'Sawah Kelas Enam (Kualitas Terendah)',

            self::D_I => 'Darat Kelas Satu (Sangat Subur/Pekarangan Strategis)',
            self::D_II => 'Darat Kelas Dua (Subur/Kebun Produktif)',
            self::D_III => 'Darat Kelas Tiga (Sedang/Tegalan Palawija)',
            self::D_IV => 'Darat Kelas Empat (Kurang Subur/Tandus)',
            self::D_V => 'Darat Kelas Lima (Kritis/Berbatu/Lereng)',
            self::D_VI => 'Darat Kelas Enam (Sangat Kritis/Tidak Produktif)',

            self::T_I => 'Tambak Kelas Satu (Produktivitas Tinggi)',
            self::T_II => 'Tambak Kelas Dua (Produktivitas Sedang)',
            self::T_III => 'Tambak Kelas Tiga (Tradisional/Hasil Rendah)',
            self::T_IV => 'Tambak Kelas Empat (Marjinal/Pendangkalan)',

            self::GG_I => 'Gaga Kelas Satu (Padi Lahan Kering/Gogo)',
            self::GG_II => 'Gaga Kelas Dua',
            self::GG_III => 'Gaga Kelas Tiga',

            self::RAWA => 'Rawa (Lahan Genangan Alami)',
            self::HUTAN => 'Hutan / Semak Belukar Milik Desa',
        };
    }

    public function group(): string
    {
        return match ($this) {
            self::S_I,
            self::S_II,
            self::S_III,
            self::S_IV,
            self::S_V,
            self::S_VI => 'Sawah (Lahan Basah)',

            self::D_I,
            self::D_II,
            self::D_III,
            self::D_IV,
            self::D_V,
            self::D_VI => 'Darat / Tegal / Pekarangan (Lahan Kering)',

            self::T_I,
            self::T_II,
            self::T_III,
            self::T_IV => 'Tambak / Perikanan',

            self::GG_I,
            self::GG_II,
            self::GG_III => 'Gaga (Padi Lahan Kering)',

            self::RAWA,
            self::HUTAN => 'Kelompok Khusus',
        };
    }

    public static function options(): array
    {
        return array_column(
            array_map(
                fn(self $case) => [
                    'value' => $case->value,
                    'label' => $case->code() . ' - ' . $case->label(),
                ],
                self::cases()
            ),
            'label',
            'value'
        );
    }

    public static function groupedOptions(): array
    {
        $options = [];

        foreach (self::cases() as $case) {
            $options[$case->group()][$case->value] =
                $case->code() . ' - ' . $case->label();
        }

        return $options;
    }
}
