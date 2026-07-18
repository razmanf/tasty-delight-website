@php
    use Filament\Widgets\View\Components\ChartWidgetComponent;
    use Illuminate\View\ComponentAttributeBag;

    $color = $this->getColor();
    $heading = $this->getHeading();
    $description = $this->getDescription();
    $filters = $this->getFilters();
    $isCollapsible = $this->isCollapsible();
    $type = $this->getType();
    $maxHeight = $this->getMaxHeight();
    $hasMaxHeight = filled($maxHeight) && $maxHeight !== '100%';
@endphp

<x-filament-widgets::widget class="fi-wi-chart">
    <x-filament::section
        :description="$description"
        :heading="$heading"
        :collapsible="$isCollapsible"
    >
        @if ($filters || method_exists($this, 'getFiltersSchema'))
            <x-slot name="afterHeader">
                @if ($filters)
                    @php
                        $currentFilterValue = $this->filter ?? array_key_first($filters);
                        $currentFilterLabel = $filters[$currentFilterValue] ?? 'Select';
                    @endphp
                    <style>
                        .chart-filter-vars {
                            --cf-bg: #ffffff;
                            --cf-border: #e5e7eb;
                            --cf-text: #374151;
                            --cf-muted: #9ca3af;
                            --cf-primary: #DD6625;
                            --cf-hover: #f3f4f6;
                        }
                        .dark .chart-filter-vars {
                            --cf-bg: #1f2937;
                            --cf-border: #374151;
                            --cf-text: #e5e7eb;
                            --cf-hover: rgba(55, 65, 81, 0.5);
                        }
                    </style>
                    <div x-data="{ open: false }" class="chart-filter-vars" style="position: relative; display: inline-block; text-align: left;" @click.outside="open = false">
                        <button type="button" @click="open = !open" 
                                style="display: flex; align-items: center; justify-content: space-between; min-width: 140px; flex-shrink: 0; padding: 0.5rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; white-space: nowrap; outline: none; cursor: pointer; border: 1px solid var(--cf-border); background: var(--cf-bg); color: var(--cf-text); transition: all 0.2s;"
                                class="shadow-sm">
                            <span>{{ $currentFilterLabel }}</span>
                            <x-filament::icon
                                icon="heroicon-m-chevron-down"
                                class="ml-2"
                                x-bind:style="open ? 'transform: rotate(180deg); width: 20px; height: 20px; color: var(--cf-muted); transition: transform 0.2s ease-in-out;' : 'transform: rotate(0deg); width: 20px; height: 20px; color: var(--cf-muted); transition: transform 0.2s ease-in-out;'"
                            />
                        </button>

                        <div x-show="open" x-cloak
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             style="display: none; position: absolute; right: 0; margin-top: 0px; min-width: 140px; border-radius: 0.75rem; overflow: hidden; z-index: 10; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); background-color: var(--cf-bg); border: 1px solid var(--cf-border);">
                            <div style="display: flex; flex-direction: column; padding: 0.25rem 0;" role="menu">
                                @foreach ($filters as $value => $label)
                                    <button type="button" wire:click="$set('filter', '{{ $value }}')" @click="open = false"
                                            style="display: flex; align-items: center; justify-content: space-between; width: 100%; text-align: left; padding: 0.5rem 1rem; font-size: 0.875rem; color: var(--cf-text); transition: background-color 0.2s;"
                                            onmouseover="this.style.backgroundColor='var(--cf-hover)'"
                                            onmouseout="this.style.backgroundColor='transparent'"
                                            role="menuitem">
                                        <span style="{{ $currentFilterValue == $value ? 'font-weight: 600;' : '' }}">{{ $label }}</span>
                                        @if($currentFilterValue == $value)
                                            <x-filament::icon
                                                icon="heroicon-m-check"
                                                class="ml-2"
                                                style="width: 16px; height: 16px; color: var(--cf-primary);"
                                            />
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @if (method_exists($this, 'getFiltersSchema'))
                    <x-filament::dropdown
                        placement="bottom-end"
                        shift
                        width="xs"
                        class="fi-wi-chart-filter"
                    >
                        <x-slot name="trigger">
                            {{ $this->getFiltersTriggerAction() }}
                        </x-slot>

                        <div class="fi-wi-chart-filter-content">
                            {{ $this->getFiltersSchema() }}

                            @if (method_exists($this, 'hasDeferredFilters') && $this->hasDeferredFilters())
                                <div
                                    class="fi-wi-chart-filter-content-actions-ctn"
                                >
                                    {{ $this->getFiltersApplyAction() }}

                                    {{ $this->getFiltersResetAction() }}
                                </div>
                            @endif
                        </div>
                    </x-filament::dropdown>
                @endif
            </x-slot>
        @endif

        <div
            @if ($pollingInterval = $this->getPollingInterval())
                wire:poll.{{ $pollingInterval }}="updateChartData"
            @endif
        >
            <div
                x-load
                x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('chart', 'filament/widgets') }}"
                wire:ignore
                data-chart-type="{{ $type }}"
                x-data="chart({
                            cachedData: @js($this->getCachedData()),
                            options: @js($this->getOptions()),
                            type: @js($type),
                        })"
                {{
                    (new ComponentAttributeBag)
                        ->color(ChartWidgetComponent::class, $color)
                        ->class([
                            'fi-wi-chart-canvas-ctn',
                            'fi-wi-chart-canvas-ctn-no-aspect-ratio' => $hasMaxHeight,
                        ])
                }}
            >
                <canvas
                    x-ref="canvas"
                    @style([
                        'width: 100%',
                        'height: 100%; max-height: 100%' => ! $hasMaxHeight,
                        ('max-height: ' . e($maxHeight)) => $hasMaxHeight,
                    ])
                ></canvas>

                <span
                    x-ref="backgroundColorElement"
                    class="fi-wi-chart-bg-color"
                ></span>

                <span
                    x-ref="borderColorElement"
                    class="fi-wi-chart-border-color"
                ></span>

                <span
                    x-ref="gridColorElement"
                    class="fi-wi-chart-grid-color"
                ></span>

                <span
                    x-ref="textColorElement"
                    class="fi-wi-chart-text-color"
                ></span>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
