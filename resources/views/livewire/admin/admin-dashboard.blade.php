<div class="space-y-6">

    {{-- Header & Search --}}
    <div class="flex justify-between items-center bg-blue-500 p-6 rounded-lg">
        <h1 class="text-white text-2xl font-bold">Settings Page</h1>
        <div class="relative">
            <input type="text"
                   wire:model.debounce.300ms="searchQuery"
                   placeholder="Search here"
                   class="rounded-full px-4 py-2 w-64 focus:outline-none" />
            <button class="absolute right-0 top-0 mt-2 me-2 bg-white rounded-full p-2 shadow">
                🔍
            </button>
            @if(!empty($searchResults))
                <ul class="absolute mt-1 bg-white rounded shadow w-64 z-10">
                    @foreach($searchResults as $item)
                        <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                            {{ $item['name'] }} — Rs. {{ $item['price'] }}
                        </li>
                    @endforeach
                </ul>
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
                'icon'      => '<i class="far fa-chart-bar"></i>',
                'deltaColor'=> 'text-green-500',
                'bgIcon'    => 'text-red-500'
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
                <div class="font-bold text-sm text-gray-500">{{ $card['label'] }}</div>
                <div class="text-2xl font-bold">{{ $card['value'] }}</div>
                <div class="text-sm {{ $card['deltaColor'] }} mt-1">
                    {{ $card['trend']=='up' ? '↑' : '↓' }} {{ $card['delta'] }} Since {{ $card['trend']=='up' ? 'last month' : ($card['label']=='NEW USERS' ? 'last week' : 'yesterday') }}
                </div>
            </div>
            <div class="{{ $card['bgIcon'] }} text-3xl">{!! $card['icon'] !!}</div>
        </div>
        @endforeach
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-gray-900 p-6 rounded-lg shadow-md">
            <h3 class="text-white font-semibold mb-4">Sales value</h3>
            <canvas id="salesChart"></canvas>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-gray-800 font-semibold mb-4">Total orders</h3>
            <canvas id="ordersChart"></canvas>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Sales Line Chart
new Chart(document.getElementById('salesChart'), {
    type: 'line',
    data: {
        labels: @json($months),
        datasets: [
            { label:'2025', data:@json($sales2025), borderColor:'white', backgroundColor:'transparent', tension:0.4 },
            { label:'2024', data:@json($sales2024), borderColor:'#4f46e5', backgroundColor:'transparent', tension:0.4 },
        ]
    },
    options: {
        scales: {
            x:{ grid:{ color:'#374151' }, ticks:{ color:'#9ca3af' } },
            y:{ grid:{ color:'#374151' }, ticks:{ color:'#9ca3af' } }
        },
        plugins:{ legend:{ labels:{ color:'white' } } }
    }
});

// Orders Bar Chart
new Chart(document.getElementById('ordersChart'), {
    type: 'bar',
    data: {
        labels: @json($months),
        datasets: [
            { label:'2025', data:@json($orders2025), backgroundColor:'#ec4899' },
            { label:'2024', data:@json($orders2024), backgroundColor:'#4f46e5' },
        ]
    },
    options: {
        scales: {
            x:{ grid:{ display:false }, ticks:{ color:'#4b5563' } },
            y:{ grid:{ color:'#e5e7eb' }, ticks:{ color:'#4b5563' } }
        },
        plugins:{ legend:{ labels:{ color:'#4b5563' } } }
    }
});
</script>
@endpush