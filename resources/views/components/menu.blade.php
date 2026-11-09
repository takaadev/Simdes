<nav class="sidebar-nav-scroll" aria-label="Menu Administrasi">
    <ul class="sidebar-menu mb-2">
        @foreach ($menus as $menu)

            @if ($menu['type'] === 'label')
                <x-menu-label :label="$menu['label']" />

            @elseif ($menu['type'] === 'link')
                <x-menu-link
                    :href="route($menu['route'])"
                    :label="$menu['label']"
                    :icon="$menu['icon']"
                    :active="request()->routeIs($menu['route'])"
                />

            @elseif ($menu['type'] === 'group')
                @php
                    $items = collect($menu['items'])
                        ->filter(fn ($item) => $item['enabled'] ?? true)
                        ->values();

                    $isExpanded = $items->contains(function ($item) {
                        if ($item['route'] === '#') {
                            return false;
                        }

                        $routePrefix = \Illuminate\Support\Str::beforeLast(
                            $item['route'],
                            '.'
                        );

                        return request()->routeIs(
                            $routePrefix . '.*'
                        );
                    });
                @endphp

                @if ($items->isNotEmpty())
                    <x-menu-group
                        :group="$menu['group']"
                        :label="$menu['label']"
                        :icon="$menu['icon']"
                        :badge="$items->count()"
                        :tooltip="$menu['tooltip']"
                        :expanded="$isExpanded">

                        @foreach ($items as $item)
                            @php
                                $routePrefix = \Illuminate\Support\Str::beforeLast(
                                    $item['route'],
                                    '.'
                                );

                                $isActive = $item['route'] !== '#'
                                    && request()->routeIs($routePrefix . '.*');
                            @endphp

                            <x-menu-link-group
                                :href="$item['route'] === '#' ? '#' : route($item['route'])"
                                :label="$item['label']"
                                :ready="$item['ready']"
                                :active="$isActive"
                            />
                        @endforeach

                    </x-menu-group>
                @endif
            @endif

        @endforeach

    </ul>
</nav>