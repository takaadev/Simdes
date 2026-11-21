<?php

namespace App\View\Components;

use App\Enums\RoleAccessType;
use App\Models\User;
use App\Models\VillageOfficial;
use BackedEnum;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class UserOfficialProfile extends Component
{
    public ?User $user = null;
    public ?VillageOfficial $official = null;
    public string $displayName = 'Pengguna';
    public string $initials = 'US';
    public string $roleLabel = '-';
    public string $roleColor = 'primary';

    public function __construct()
    {
        /** @var User|null $authUser */
        $authUser = Auth::user();

        if (!$authUser) {
            return;
        }

        $this->user = $authUser->loadMissing('villageOfficial.position');
        $this->official = $this->user->villageOfficial;

        $this->displayName = $this->official?->nama ?: ($this->user->name ?: 'Pengguna');

        $this->initials = collect(preg_split('/\s+/', trim($this->displayName)))
            ->filter()
            ->take(2)
            ->map(fn(string $word) => mb_strtoupper(mb_substr($word, 0, 1)))
            ->implode('') ?: 'US';

        $roleEnum = $this->user->role instanceof RoleAccessType
            ? $this->user->role
            : RoleAccessType::tryFrom((string) $this->user->role);

        $this->roleLabel = $roleEnum?->label() ?? ($this->user->role ?: '-');
        $this->roleColor = $roleEnum?->color() ?? 'primary';
    }

    public function formatDate($date, string $format = 'd F Y'): string
    {
        return $date
            ? Carbon::parse($date)->locale('id')->translatedFormat($format)
            : '-';
    }

    public function enumLabel($value): string
    {
        if ($value instanceof BackedEnum) {
            return method_exists($value, 'label')
                ? $value->label()
                : (string) $value->value;
        }

        return filled($value) ? (string) $value : '-';
    }

    public function render(): View
    {
        return view('components.user-official-profile');
    }
}