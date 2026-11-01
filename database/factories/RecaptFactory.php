<?php

namespace Database\Factories;

use App\Enums\PeriodTypeList;
use App\Models\Recapt;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Recapt>
 */
class RecaptFactory extends Factory
{
    protected $model = Recapt::class;

    public function definition(): array
    {
        $periodStart = fake()->dateTimeBetween('-2 years', '-1 month');
        $periodEnd = fake()->dateTimeBetween($periodStart, 'now');

        return [
            'title' => 'Rekapitulasi Penduduk ' . fake()->year(),

            'period_type' => fake()->randomElement(
                PeriodTypeList::cases()
            ),

            'period_start' => $periodStart->format('Y-m-d'),
            'period_end' => $periodEnd->format('Y-m-d'),

            'snapshot_date' => fake()
                ->dateTimeBetween($periodEnd, 'now')
                ->format('Y-m-d'),

            'notes' => fake()->optional()->sentence(),

            'created_by' => User::factory(),
        ];
    }

    public function monthly(): static
    {
        return $this->state(fn() => [
            'period_type' => PeriodTypeList::MONTHLY,
        ]);
    }

    public function yearly(): static
    {
        return $this->state(fn() => [
            'period_type' => PeriodTypeList::YEARLY,
        ]);
    }

    public function custom(): static
    {
        return $this->state(fn() => [
            'period_type' => PeriodTypeList::CUSTOM,
        ]);
    }
}
