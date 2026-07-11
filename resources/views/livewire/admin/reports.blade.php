<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    {{-- Header + filters --}}
    <div class="flex items-center justify-between py-4">
        <h2 class="text-2xl font-semibold text-gray-700">Reports</h2>

        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2">
                <label class="text-sm">From</label>
                <input type="date" wire:model="from" class="border rounded p-2" />
            </div>

            <div class="flex items-center gap-2">
                <label class="text-sm">To</label>
                <input type="date" wire:model="to" class="border rounded p-2" />
            </div>

            <select wire:model="preset" class="border rounded p-2">
                <option value="7">Last 7 days</option>
                <option value="30">Last 30 days</option>
                <option value="90">Last 90 days</option>
                <option value="custom">Custom</option>
            </select>

            <button wire:click="applyRange" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Apply</button>

            <button wire:click="exportCsv" class="px-3 py-1 bg-gray-700 text-white rounded hover:bg-gray-800 transition">Export CSV</button>
        </div>
    </div>

    {{-- KPIs --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded shadow">
            <div class="text-sm text-gray-500">Total Orders</div>
            <div class="text-2xl font-semibold">{{ number_format($summary->totalOrders) }}</div>
        </div>

        <div class="bg-white p-4 rounded shadow">
            <div class="text-sm text-gray-500">Total Revenue</div>
            <div class="text-2xl font-semibold">{{ number_format($summary->totalRevenue, 2) }}</div>
        </div>

        <div class="bg-white p-4 rounded shadow">
            <div class="text-sm text-gray-500">Average Order</div>
            <div class="text-2xl font-semibold">{{ number_format($summary->avgOrder, 2) }}</div>
        </div>
    </div>

    {{-- Orders by day --}}
    <div class="mt-6 bg-white p-4 rounded shadow">
        <h3 class="text-lg font-semibold mb-3">Orders by day</h3>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orders</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($ordersByDay as $row)
                        <tr>
                            <td class="px-6 py-3">{{ \Illuminate\Support\Carbon::parse($row->date)->format('Y-m-d') }}</td>
                            <td class="px-6 py-3">{{ $row->orders_count }}</td>
                            <td class="px-6 py-3">{{ number_format($row->revenue ?? 0, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">No orders in this range.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Top products --}}
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold mb-3">Top products (by number of orders)</h3>

            <ul class="space-y-2">
                @forelse($topProducts as $prod)
                    <li class="flex justify-between items-center">
                        <div>
                            <div class="font-medium">{{ $prod->name }}</div>
                            <div class="text-sm text-gray-500">Orders: {{ $prod->orders_count ?? 0 }}</div>
                        </div>
                        <div class="text-sm text-gray-500">Created: {{ optional($prod->created_at)->format('Y-m-d') }}</div>
                    </li>
                @empty
                    <li class="text-gray-500">No products found.</li>
                @endforelse
            </ul>
        </div>

        {{-- Empty panel (future charts or metrics) --}}
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold mb-3">Notes</h3>
            <p class="text-sm text-gray-600">
                Export CSV downloads the orders in the selected range. If you want a PDF export or charts, I can add Chart.js and an endpoint for PDF generation next.
            </p>
        </div>
    </div>
</div>
