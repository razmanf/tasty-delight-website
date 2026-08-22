@props([
    'currentPageOptionProperty' => 'tableRecordsPerPage',
    'extremeLinks' => false,
    'paginator',
    'pageOptions' => [],
])

@php
    use Illuminate\Contracts\Pagination\CursorPaginator;

    $isRtl = __('filament-panels::layout.direction') === 'rtl';
    $isSimple = ! $paginator instanceof \Illuminate\Pagination\LengthAwarePaginator;
@endphp

<nav
    aria-label="{{ __('filament::components/pagination.label') }}"
    role="navigation"
    {{
        $attributes->class([
            'fi-pagination',
            'fi-simple' => $isSimple,
        ])
    }}
>
    @if (! $paginator->onFirstPage())
        @php
            if ($paginator instanceof CursorPaginator) {
                $wireClickAction = "setPage('{$paginator->previousCursor()->encode()}', '{$paginator->getCursorName()}')";
            } else {
                $wireClickAction = "previousPage('{$paginator->getPageName()}')";
            }
        @endphp

        <x-filament::button
            color="gray"
            rel="prev"
            :wire:click="$wireClickAction"
            :wire:key="$this->getId() . '.pagination.previous'"
            class="fi-pagination-previous-btn"
        >
            {{ __('filament::components/pagination.actions.previous.label') }}
        </x-filament::button>
    @endif

    @if (! $isSimple)
        <span class="fi-pagination-overview">
            {{
                trans_choice(
                    'filament::components/pagination.overview',
                    $paginator->total(),
                    [
                        'first' => \Illuminate\Support\Number::format($paginator->firstItem() ?? 0),
                        'last' => \Illuminate\Support\Number::format($paginator->lastItem() ?? 0),
                        'total' => \Illuminate\Support\Number::format($paginator->total()),
                    ],
                )
            }}
        </span>
    @endif

    @if (count($pageOptions) > 1)
        <div class="fi-pagination-records-per-page-select-ctn">
            <label class="fi-pagination-records-per-page-select fi-compact">
                <x-filament::input.wrapper>
                    <x-filament::input.select
                        :wire:model.live="$currentPageOptionProperty"
                    >
                        @foreach ($pageOptions as $option)
                            <option value="{{ $option }}">
                                {{ $option === 'all' ? __('filament::components/pagination.fields.records_per_page.options.all') : $option }}
                            </option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>

                <span class="fi-sr-only">
                    {{ __('filament::components/pagination.fields.records_per_page.label') }}
                </span>
            </label>

            <div style="display: flex; flex-direction: row; align-items: center; gap: 0.75rem; white-space: nowrap; flex-wrap: nowrap;" class="hidden sm:flex">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400" style="white-space: nowrap;">
                    {{ __('filament::components/pagination.fields.records_per_page.label') }}
                </span>

                <div x-data="{ open: false }" class="pagination-per-page-vars" style="position: relative; display: inline-block; text-align: left;" @click.outside="open = false">
                    <button type="button" @click="open = !open"
                            style="display: flex; align-items: center; justify-content: space-between; min-width: 70px; flex-shrink: 0; padding: 0.5rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; white-space: nowrap; outline: none; cursor: pointer; border: 1px solid var(--ppg-border); background: var(--ppg-bg); color: var(--ppg-text); transition: all 0.2s;"
                            class="shadow-sm">
                        <span>{{ $this->{$currentPageOptionProperty} === 'all' ? __('filament::components/pagination.fields.records_per_page.options.all') : $this->{$currentPageOptionProperty} }}</span>
                        <x-filament::icon
                            icon="heroicon-m-chevron-down"
                            class="ml-2"
                            x-bind:style="open ? 'transform: rotate(180deg); width: 16px; height: 16px; color: var(--ppg-muted); transition: transform 0.2s ease-in-out;' : 'transform: rotate(0deg); width: 16px; height: 16px; color: var(--ppg-muted); transition: transform 0.2s ease-in-out;'"
                        />
                    </button>

                    <div x-show="open" x-cloak
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         style="display: none; position: absolute; right: 0; margin-top: 0; min-width: 70px; border-radius: 0.75rem; overflow: hidden; z-index: 50; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); background-color: var(--ppg-bg); border: 1px solid var(--ppg-border);">
                        <div style="display: flex; flex-direction: column; padding: 0.25rem 0;" role="menu">
                            @foreach ($pageOptions as $option)
                                <button type="button"
                                        wire:click="$set('{{ $currentPageOptionProperty }}', '{{ $option }}')"
                                        @click="open = false"
                                        style="display: flex; align-items: center; justify-content: space-between; width: 100%; text-align: left; padding: 0.5rem 1rem; font-size: 0.875rem; color: var(--ppg-text); transition: background-color 0.2s;"
                                        onmouseover="this.style.backgroundColor='var(--ppg-hover)'"
                                        onmouseout="this.style.backgroundColor='transparent'"
                                        role="menuitem">
                                    <span style="{{ $this->{$currentPageOptionProperty} == $option ? 'font-weight: 600;' : '' }}">
                                        {{ $option === 'all' ? __('filament::components/pagination.fields.records_per_page.options.all') : $option }}
                                    </span>
                                    @if($this->{$currentPageOptionProperty} == $option)
                                        <x-filament::icon
                                            icon="heroicon-m-check"
                                            class="ml-2"
                                            style="width: 16px; height: 16px; color: #DD6625;"
                                        />
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>
    @endif

    @if ($paginator->hasMorePages())
        @php
            if ($paginator instanceof CursorPaginator) {
                $wireClickAction = "setPage('{$paginator->nextCursor()->encode()}', '{$paginator->getCursorName()}')";
            } else {
                $wireClickAction = "nextPage('{$paginator->getPageName()}')";
            }
        @endphp

        <x-filament::button
            color="gray"
            rel="next"
            :wire:click="$wireClickAction"
            :wire:key="$this->getId() . '.pagination.next'"
            class="fi-pagination-next-btn"
        >
            {{ __('filament::components/pagination.actions.next.label') }}
        </x-filament::button>
    @endif

    @if ((! $isSimple) && $paginator->hasPages())
        <ol class="fi-pagination-items">
            @if (! $paginator->onFirstPage())
                @if ($extremeLinks)
                    <x-filament::pagination.item
                        :aria-label="__('filament::components/pagination.actions.first.label')"
                        :icon="$isRtl ? \Filament\Support\Icons\Heroicon::ChevronDoubleRight : \Filament\Support\Icons\Heroicon::ChevronDoubleLeft"
                        :icon-alias="
                            $isRtl
                            ? \Filament\Support\View\SupportIconAlias::PAGINATION_FIRST_BUTTON_RTL
                            : \Filament\Support\View\SupportIconAlias::PAGINATION_FIRST_BUTTON
                        "
                        rel="first"
                        :wire:click="'gotoPage(1, \'' . $paginator->getPageName() . '\')'"
                        :wire:key="$this->getId() . '.pagination.first'"
                    />
                @endif

                <x-filament::pagination.item
                    :aria-label="__('filament::components/pagination.actions.previous.label')"
                    :icon="$isRtl ? \Filament\Support\Icons\Heroicon::ChevronRight : \Filament\Support\Icons\Heroicon::ChevronLeft"
                    {{-- @deprecated Use `SupportIconAlias::PAGINATION_PREVIOUS_BUTTON_RTL` instead of `SupportIconAlias::PAGINATION_PREVIOUS_BUTTON` for RTL. --}}
                    :icon-alias="
                        $isRtl
                        ? [
                            \Filament\Support\View\SupportIconAlias::PAGINATION_PREVIOUS_BUTTON_RTL,
                            \Filament\Support\View\SupportIconAlias::PAGINATION_PREVIOUS_BUTTON,
                        ]
                        : \Filament\Support\View\SupportIconAlias::PAGINATION_PREVIOUS_BUTTON
                    "
                    rel="prev"
                    :wire:click="'previousPage(\'' . $paginator->getPageName() . '\')'"
                    :wire:key="$this->getId() . '.pagination.previous'"
                />
            @endif

            @foreach ($paginator->render()->offsetGet('elements') as $element)
                @if (is_string($element))
                    <x-filament::pagination.item disabled :label="$element" />
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <x-filament::pagination.item
                            :active="$page === $paginator->currentPage()"
                            :aria-label="trans_choice('filament::components/pagination.actions.go_to_page.label', $page, ['page' => \Illuminate\Support\Number::format($page)])"
                            :label="\Illuminate\Support\Number::format($page)"
                            :wire:click="'gotoPage(' . $page . ', \'' . $paginator->getPageName() . '\')'"
                            :wire:key="$this->getId() . '.pagination.' . $paginator->getPageName() . '.' . $page"
                        />
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <x-filament::pagination.item
                    :aria-label="__('filament::components/pagination.actions.next.label')"
                    :icon="$isRtl ? \Filament\Support\Icons\Heroicon::ChevronLeft : \Filament\Support\Icons\Heroicon::ChevronRight"
                    {{-- @deprecated Use `SupportIconAlias::PAGINATION_NEXT_BUTTON_RTL` instead of `SupportIconAlias::PAGINATION_NEXT_BUTTON` for RTL. --}}
                    :icon-alias="
                        $isRtl
                        ? [
                            \Filament\Support\View\SupportIconAlias::PAGINATION_NEXT_BUTTON_RTL,
                            \Filament\Support\View\SupportIconAlias::PAGINATION_NEXT_BUTTON,
                        ]
                        : \Filament\Support\View\SupportIconAlias::PAGINATION_NEXT_BUTTON
                    "
                    rel="next"
                    :wire:click="'nextPage(\'' . $paginator->getPageName() . '\')'"
                    :wire:key="$this->getId() . '.pagination.next'"
                />

                @if ($extremeLinks)
                    <x-filament::pagination.item
                        :aria-label="__('filament::components/pagination.actions.last.label')"
                        :icon="$isRtl ? \Filament\Support\Icons\Heroicon::ChevronDoubleLeft : \Filament\Support\Icons\Heroicon::ChevronDoubleRight"
                        :icon-alias="
                            $isRtl
                            ? \Filament\Support\View\SupportIconAlias::PAGINATION_LAST_BUTTON_RTL
                            : \Filament\Support\View\SupportIconAlias::PAGINATION_LAST_BUTTON
                        "
                        rel="last"
                        :wire:click="'gotoPage(' . $paginator->lastPage() . ', \'' . $paginator->getPageName() . '\')'"
                        :wire:key="$this->getId() . '.pagination.last'"
                    />
                @endif
            @endif
        </ol>
    @endif
</nav>
