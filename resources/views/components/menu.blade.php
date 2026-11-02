<nav class="sidebar-nav-scroll" aria-label="Menu Administrasi">
    <ul class="sidebar-menu mb-2">

        @foreach ($menus as $menu)

            @if ($menu['type'] === 'label')
                <x-menu-label :label="$menu['label']" />
            @elseif ($menu['type'] === 'link')
                <x-menu-link :href="$menu['route']" :label="$menu['label']" :icon="$menu['icon']" :active="request()->fullUrlIs($menu['route'])" />
            @elseif ($menu['type'] === 'group')
                @php
                    $items = collect($menu['items'])->filter(fn($item) => $item['enabled'] ?? true)->values();
                @endphp

                @if ($items->isNotEmpty())
                    <x-menu-group :group="$menu['group']" :label="$menu['label']" :icon="$menu['icon']" :badge="$items->count()"
                        :tooltip="$menu['tooltip']" :expanded="$menu['expanded'] ?? false">
                        @foreach ($items as $item)
                            <x-menu-link-group href="{{ $item['route'] }}" :label="$item['label']" :slug="$item['slug']"
                                :ready="$item['ready']" :active="request()->fullUrlIs($item['route'])" />
                        @endforeach
                    </x-menu-group>
                @endif
            @endif
        @endforeach
    </ul>
</nav>
