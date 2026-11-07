@props([
    'label' => '',
    'value' => 0,
    'icon' => 'chart-simple',
    'color' => 'primary',
    'id' => null,
    'size' => 'col-12 col-sm-6 col-xl-3',
    'duration' => 1000,
])

@php
    $colors = [
        'blue' => 'primary',
        'primary' => 'primary',
        'gray' => 'secondary',
        'secondary' => 'secondary',
        'green' => 'success',
        'success' => 'success',
        'red' => 'danger',
        'danger' => 'danger',
        'yellow' => 'warning',
        'warning' => 'warning',
        'cyan' => 'info',
        'info' => 'info',
        'light' => 'light',
        'dark' => 'dark',
        'orange' => 'warning',
        'purple' => 'primary',
        'pink' => 'danger',
    ];

    $colorClass = $colors[strtolower($color)] ?? 'primary';

    $widgetId = $id ?? 'widget-' . \Illuminate\Support\Str::uuid();
@endphp

<div {{ $attributes->merge(['class' => $size]) }}>
    <article class="stat-card stat-card-{{ $colorClass }} py-3">
        <div class="d-flex align-items-center justify-content-between gap-2">
            <div class="min-w-0">
                <div class="stat-label mb-1">
                    {{ $label }}
                </div>

                <div class="stat-value fs-3" id="{{ $widgetId }}" data-widget-counter data-value="{{ $value }}"
                    data-duration="{{ $duration }}">
                    {{ number_format((float) $value, 0, ',', '.') }}
                </div>
            </div>

            <div class="stat-icon-box stat-icon-{{ $colorClass }}">
                <i class="fa-solid fa-{{ $icon }}" aria-hidden="true"></i>
            </div>
        </div>
    </article>
</div>

@once
    @push('header')
        <style>
            .stat-card {
                border-top: 3px solid transparent;
            }

            .stat-card-primary {
                border-top-color: var(--bs-primary);
            }

            .stat-card-secondary {
                border-top-color: var(--bs-secondary);
            }

            .stat-card-success {
                border-top-color: var(--bs-success);
            }

            .stat-card-danger {
                border-top-color: var(--bs-danger);
            }

            .stat-card-warning {
                border-top-color: var(--bs-warning);
            }

            .stat-card-info {
                border-top-color: var(--bs-info);
            }

            .stat-card-light {
                border-top-color: var(--bs-light);
            }

            .stat-card-dark {
                border-top-color: var(--bs-dark);
            }

            .stat-icon-primary {
                color: var(--bs-primary);
                background: rgba(var(--bs-primary-rgb), .10);
            }

            .stat-icon-secondary {
                color: var(--bs-secondary);
                background: rgba(var(--bs-secondary-rgb), .10);
            }

            .stat-icon-success {
                color: var(--bs-success);
                background: rgba(var(--bs-success-rgb), .10);
            }

            .stat-icon-danger {
                color: var(--bs-danger);
                background: rgba(var(--bs-danger-rgb), .10);
            }

            .stat-icon-warning {
                color: var(--bs-warning);
                background: rgba(var(--bs-warning-rgb), .12);
            }

            .stat-icon-info {
                color: var(--bs-info);
                background: rgba(var(--bs-info-rgb), .10);
            }

            .stat-icon-light {
                color: var(--bs-secondary);
                background: var(--bs-light);
            }

            .stat-icon-dark {
                color: var(--bs-dark);
                background: rgba(var(--bs-dark-rgb), .10);
            }
        </style>
    @endpush

    @push('footer')
        <script>
            (() => {
                // Animated Counter: Satu Angka
                function animateWidgetCounter(element, target, duration) {
                    const current = Number(element.dataset.value) || 0;
                    const end = Number(target) || 0;

                    if (current === end) return;

                    const startTime = performance.now();

                    function update(timestamp) {
                        const progress = Math.min(
                            (timestamp - startTime) / duration,
                            1
                        );

                        const ease = 1 - Math.pow(1 - progress, 3);

                        const value = Math.round(
                            current + (end - current) * ease
                        );

                        element.textContent = value.toLocaleString('id-ID');

                        if (progress < 1) {
                            requestAnimationFrame(update);
                        } else {
                            element.dataset.value = end;
                        }
                    }

                    requestAnimationFrame(update);
                }

                // Animated Counter: Dua Angka
                function animateWidgetPair(element, firstTarget, secondTarget, duration) {
                    const values = element.textContent.split('/');

                    const firstCurrent = Number(values[0]?.trim()) || 0;
                    const secondCurrent = Number(values[1]?.trim()) || 0;

                    const firstEnd = Number(firstTarget) || 0;
                    const secondEnd = Number(secondTarget) || 0;

                    if (firstCurrent === firstEnd && secondCurrent === secondEnd) return;

                    const startTime = performance.now();

                    function update(timestamp) {
                        const progress = Math.min(
                            (timestamp - startTime) / duration,
                            1
                        );

                        const ease = 1 - Math.pow(1 - progress, 3);

                        const firstValue = Math.round(
                            firstCurrent + (firstEnd - firstCurrent) * ease
                        );

                        const secondValue = Math.round(
                            secondCurrent + (secondEnd - secondCurrent) * ease
                        );

                        element.textContent =
                            `${firstValue.toLocaleString('id-ID')} / ${secondValue.toLocaleString('id-ID')}`;

                        if (progress < 1) {
                            requestAnimationFrame(update);
                        }
                    }

                    requestAnimationFrame(update);
                }

                // Inisialisasi Counter Saat Halaman Dimuat
                document.querySelectorAll('[data-widget-counter]').forEach(element => {
                    animateWidgetCounter(
                        element,
                        element.dataset.value,
                        Number(element.dataset.duration) || 800
                    );
                });

                // Update Satu Angka
                window.updateWidgetCounter = function(id, value) {
                    const element = document.getElementById(id);

                    if (!element) return;

                    animateWidgetCounter(
                        element,
                        value,
                        Number(element.dataset.duration) || 800
                    );
                };

                // Update Dua Angka
                window.updateWidgetPair = function(id, firstValue, secondValue) {
                    const element = document.getElementById(id);

                    if (!element) return;

                    animateWidgetPair(
                        element,
                        firstValue,
                        secondValue,
                        Number(element.dataset.duration) || 800
                    );
                };
            })
            ();
        </script>
    @endpush
@endonce
