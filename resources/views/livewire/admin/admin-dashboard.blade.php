<div class="space-y-6">

    {{-- Header & Search --}}
<div class="flex justify-between items-center bg-blue-500 p-6 rounded-lg">
    <h1 class="text-white text-2xl font-bold">Overview</h1>
    
    <div id="search-bar" class="w-[20rem] bg-white rounded-md shadow-lg z-10 relative">
        <div class="flex items-center justify-center p-2">
            <input 
                type="text" 
                placeholder="Search here"
                wire:model.debounce.300ms="searchQuery"
                class="w-full rounded-md px-2 py-1 bg-white border-none focus:outline-none focus:ring-0 focus:ring-gray-600 focus:border-transparent"
            >        
        </div>

        {{-- Livewire loading spinner (appears below the input box) --}}
        <div wire:loading wire:target="searchQuery" class="text-center text-sm text-gray-600 mt-2">
            Searching...
        </div>

        @if(!empty($searchResults))
            <ul class="absolute left-0 mt-1 bg-white rounded shadow w-full z-10">
                @foreach(array_slice($searchResults, 0, 10) as $item)
                    <li 
                        class="px-4 py-2 hover:bg-gray-100 cursor-pointer"
                        wire:click="goToProduct({{ $item['id'] }})"
                    >
                        {{ $item['name'] }} — Rs. {{ $item['price'] }}
                    </li>
                @endforeach
            </ul>
        @endif

        @if(!empty($searchQuery) && empty($searchResults))
            <div class="px-4 py-2 text-gray-500">No results found.</div>
        @endif

    </div>
</div>


    {{-- Metrics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @foreach ([
            [
                'label'     => 'TRAFFIC',
                'value'     => number_format($traffic),
                'delta'     => '3.48%',
                'trend'     => 'up',
                'icon'      => '<i class="fa-solid fa-chart-column"></i>',
                'deltaColor'=> 'text-green-500',
                'bgIcon'    => 'text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-red-500'
            ], 
            [
                'label'     => 'NEW USERS',
                'value'     => number_format($newUsers),
                'delta'     => '3.48%',
                'trend'     => 'down',
                'icon'      => '<i class="fas fa-users"></i>',
                'deltaColor'=> 'text-red-500',
                'bgIcon'    => 'text-orange-500'
            ],
            [
                'label'     => 'SALES',
                'value'     => $sales,
                'delta'     => '1.10%',
                'trend'     => 'down',
                'icon'      => '<i class="fas fa-dollar-sign"></i>',
                'deltaColor'=> 'text-orange-500',
                'bgIcon'    => 'text-pink-500'
            ],
            [
                'label'     => 'PERFORMANCE',
                'value'     => $performance.'%',
                'delta'     => '12%',
                'trend'     => 'up',
                'icon'      => '<i class="fas fa-chart-line"></i>',
                'deltaColor'=> 'text-green-500',
                'bgIcon'    => 'text-blue-500'
            ],
        ] as $card)
        <div class="bg-white p-6 rounded-lg shadow flex justify-between items-center">
            <div>
                <div class="font-extrabold text-sm text-gray-500">{{ $card['label'] }}</div>
                <div class="text-2xl font-bold">{{ $card['value'] }}</div>
                <div class="text-sm {{ $card['deltaColor'] }} mt-1">
                    {{ $card['trend']=='up' ? '↑' : '↓' }} {{ $card['delta'] }} 
                    <span class="text-gray-500">
                        Since {{ $card['trend']=='up' ? 'last month' : ($card['label']=='NEW USERS' ? 'last week' : 'yesterday') }}
                    </span>
                </div>                    
            </div>
            <div class="{{ $card['bgIcon'] }} text-2xl">{!! $card['icon'] !!}</div>
        </div>
        @endforeach
    </div>

    {{-- Charts --}}
    <div class="px-4 md:px-6 mx-auto w-full mt-10">
        <div class="flex flex-wrap">
            {{-- Sales Value Line Chart --}}
            <div class="w-full xl:w-8/12 px-4">
                <div class="relative flex flex-col min-w-0 break-words w-full mb-8 shadow-lg rounded-lg bg-gray-900">
                    <div class="rounded-t mb-0 px-4 py-3 bg-transparent">
                        <div class="flex flex-wrap items-center">
                            <div class="relative w-full max-w-full flex-grow flex-1">
                                <h6 class="uppercase mb-1 text-xs font-semibold text-gray-400">Overview</h6>
                                <h2 class="text-xl font-semibold text-white">Sales value</h2>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 flex-auto">
                        <div class="relative h-[350px]">
                            <canvas id="line-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Orders Bar Chart --}}
            <div class="w-full xl:w-4/12 px-4">
                <div class="relative flex flex-col min-w-0 break-words w-full mb-8 shadow-lg rounded-lg bg-white">
                    <div class="rounded-t mb-0 px-4 py-3 bg-transparent">
                        <div class="flex flex-wrap items-center">
                            <div class="relative w-full max-w-full flex-grow flex-1">
                                <h6 class="uppercase mb-1 text-xs font-semibold text-gray-500">Performance</h6>
                                <h2 class="text-xl font-semibold text-gray-800">Total orders</h2>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 flex-auto">
                        <div class="relative h-[350px]">
                            <canvas id="bar-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Sales Line Chart
    new Chart(document.getElementById('line-chart'), {
        type: 'line',
        data: {
            labels: @json($months),
            datasets: [
                {
                    label: '2025',
                    data: @json($sales2025),
                    borderColor: 'white',
                    backgroundColor: 'transparent',
                    tension: 0.4
                },
                {
                    label: '2024',
                    data: @json($sales2024),
                    borderColor: '#4f46e5',
                    backgroundColor: 'transparent',
                    tension: 0.4
                }
            ]
        },
        options: {
            scales: {
                x: {
                    grid: { color: '#374151' },
                    ticks: { color: '#9ca3af' }
                },
                y: {
                    grid: { color: '#374151' },
                    ticks: { color: '#9ca3af' }
                }
            },
            plugins: {
                legend: {
                    labels: { color: 'white' }
                }
            }
        }
    });

    // Orders Bar Chart
    new Chart(document.getElementById('bar-chart'), {
        type: 'bar',
        data: {
            labels: @json($months),
            datasets: [
                {
                    label: '2025',
                    data: @json($orders2025),
                    backgroundColor: '#ec4899'
                },
                {
                    label: '2024',
                    data: @json($orders2024),
                    backgroundColor: '#4f46e5'
                }
            ]
        },
        options: {
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#4b5563' }
                },
                y: {
                    grid: { color: '#e5e7eb' },
                    ticks: { color: '#4b5563' }
                }
            },
            plugins: {
                legend: {
                    labels: { color: '#4b5563' }
                }
            }
        }
    });
</script>
@endpush