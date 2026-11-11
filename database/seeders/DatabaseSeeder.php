<?php

namespace Database\Seeders;

use App\Models\AgendaEntry;
use App\Models\AppSetting;
use App\Models\Dusun;
use App\Models\ExpeditionEntry;
use App\Models\FamilyCard;
use App\Models\FamilyCardMember;
use App\Models\Recapt;
use App\Models\RecaptDetail;
use App\Models\Resident;
use App\Models\ResidentMutation;
use App\Models\TemporaryResident;
use App\Models\User;
use App\Models\VillageAsset;
use App\Models\VillageDecree;
use App\Models\VillageLand;
use App\Models\VillageOfficial;
use App\Models\VillageProfile;
use App\Models\VillagePublication;
use App\Models\VillageRegulation;
use App\Models\VillageTreasuryLand;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Master Data
        |--------------------------------------------------------------------------
        */

        // $this->call([
        //     VillagePositionSeeder::class,
        // ]);

        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */

        $admin = User::factory()->create([
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'admin@gmail.com',
        ]);

        User::factory(9)->create();

        /*
        |--------------------------------------------------------------------------
        | Village Profile & Settings
        |--------------------------------------------------------------------------
        */

        VillageProfile::factory()->create();

        AppSetting::factory(10)->create([
            'updated_by' => $admin->id,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Population
        |--------------------------------------------------------------------------
        */

        Dusun::factory(4)->create();

        Resident::factory(50)->create();

        FamilyCard::factory(15)->create();

        FamilyCardMember::factory(30)->create();

        ResidentMutation::factory(15)->create();

        TemporaryResident::factory(10)->create();

        /*
        |--------------------------------------------------------------------------
        | Population Recapitulation
        |--------------------------------------------------------------------------
        */

        Recapt::factory(3)
            ->create()
            ->each(function (Recapt $recapt) {
                RecaptDetail::factory(4)->create([
                    'recapt_id' => $recapt->id,
                ]);
            });

        /*
        |--------------------------------------------------------------------------
        | Village Administration
        |--------------------------------------------------------------------------
        */

        VillageOfficial::factory(15)->create();
        VillageRegulation::factory(10)->create();
        VillageDecree::factory(10)->create();
        // VillagePublication::factory(10)->create();
        VillageAsset::factory(15)->create();
        VillageLand::factory(10)->create();
        VillageTreasuryLand::factory(10)->create();

        /*
        |--------------------------------------------------------------------------
        | Correspondence
        |--------------------------------------------------------------------------
        */

        AgendaEntry::factory(5)->create();

        // ExpeditionEntry::factory(15)->create();
    }
}
