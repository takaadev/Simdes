<?php

namespace Database\Seeders;

use App\Models\AgendaEntry;
use App\Models\AppSetting;
use App\Models\FamilyCard;
use App\Models\Resident;
use App\Models\ResidentMutation;
use App\Models\TemporaryResident;
use App\Models\User;
use App\Models\VillageAsset;
use App\Models\VillageDecree;
use App\Models\VillageLand;
use App\Models\VillageOfficial;
use App\Models\VillageProfile;
use App\Models\VillageRegulation;
use App\Models\VillageTreasuryLand;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'admin@gmail.com',
            'role' => 'admin'
        ]);

        VillageProfile::factory()->create();

        AppSetting::factory(10)->create([
            'updated_by' => $admin->id,
        ]);

        FamilyCard::factory(50)->create();
        Resident::factory(200)->create();
        ResidentMutation::factory(50)->create();
        TemporaryResident::factory(20)->create();

        VillageOfficial::factory(15)->create();
        VillageRegulation::factory(10)->create();
        VillageDecree::factory(10)->create();
        // VillagePublication::factory(10)->create();
        VillageAsset::factory(15)->create();
        VillageLand::factory(10)->create();
        VillageTreasuryLand::factory(10)->create();

        AgendaEntry::factory(20)->create();

        // ExpeditionEntry::factory(15)->create();
    }
}
