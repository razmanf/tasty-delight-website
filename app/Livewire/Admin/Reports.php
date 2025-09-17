<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Order;
use App\Models\Product;

class Reports extends Component
{
    public $from;
    public $to;
    public $preset = '30'; // default 30 days

    protected $queryString = ['from', 'to', 'preset'];

    // detected schema values
    protected $orderTable = null;
    protected $productTable = null;
    protected $createdAtColumn = null;
    protected $totalColumn = null;
    protected $itemsTable = null;
    protected $itemsQtyColumn = null;
    protected $itemsPriceColumn = null;
    protected $productPivotProductId = null;

    public function mount()
    {
        $this->to   = now()->endOfDay()->format('Y-m-d');
        $this->from = now()->subDays(29)->startOfDay()->format('Y-m-d');

        $this->detectSchema();
    }

    protected function detectSchema()
    {
        // determine order table name
        if (Schema::hasTable('orders')) {
            $this->orderTable = 'orders';
        } elseif (Schema::hasTable('order')) {
            $this->orderTable = 'order';
        } else {
            $this->orderTable = (new Order())->getTable();
        }

        // determine product table name
        if (Schema::hasTable('products')) {
            $this->productTable = 'products';
        } elseif (Schema::hasTable('product')) {
            $this->productTable = 'product';
        } else {
            $this->productTable = (new Product())->getTable();
        }

        // detect created/created_at column in orders table (common names)
        $possibleCreated = ['created_at', 'created', 'createdAt', 'order_date', 'date', 'createdOn'];
        foreach ($possibleCreated as $col) {
            if (Schema::hasColumn($this->orderTable, $col)) {
                $this->createdAtColumn = $col;
                break;
            }
        }

        // If not found, fall back to created_at (but warn)
        if (!$this->createdAtColumn) {
            // keep default but warn admin (avoid breaking UI)
            $this->createdAtColumn = 'created_at';
            if (! Schema::hasColumn($this->orderTable, $this->createdAtColumn)) {
                session()->flash('error', "Couldn't find a created-at column on `{$this->orderTable}`. Please tell me which column records order creation date.");
            }
        }

        // detect total column on orders
        $possibleTotals = ['total_price', 'total', 'total_amount', 'amount', 'grand_total', 'price'];
        foreach ($possibleTotals as $col) {
            if (Schema::hasColumn($this->orderTable, $col)) {
                $this->totalColumn = $col;
                break;
            }
        }

        // detect items/pivot table & columns
        $possibleItemTables = ['order_items', 'order_item', 'order_product', 'order_products', 'order_line_items'];
        foreach ($possibleItemTables as $tbl) {
            if (Schema::hasTable($tbl)) {
                $this->itemsTable = $tbl;
                break;
            }
        }

        if ($this->itemsTable) {
            $this->itemsQtyColumn = Schema::hasColumn($this->itemsTable, 'quantity') ? 'quantity'
                : (Schema::hasColumn($this->itemsTable, 'qty') ? 'qty' : null);

            $this->itemsPriceColumn = Schema::hasColumn($this->itemsTable, 'price') ? 'price'
                : (Schema::hasColumn($this->itemsTable, 'unit_price') ? 'unit_price' : null);

            $this->productPivotProductId = Schema::hasColumn($this->itemsTable, 'product_id') ? 'product_id' : null;
        }
    }

    public function updatedPreset($val)
    {
        if ($val === '7') {
            $this->from = now()->subDays(6)->format('Y-m-d');
            $this->to   = now()->format('Y-m-d');
        } elseif ($val === '30') {
            $this->from = now()->subDays(29)->format('Y-m-d');
            $this->to   = now()->format('Y-m-d');
        } elseif ($val === '90') {
            $this->from = now()->subDays(89)->format('Y-m-d');
            $this->to   = now()->format('Y-m-d');
        }
    }

    public function applyRange()
    {
        try {
            $f = Carbon::parse($this->from)->startOfDay();
            $t = Carbon::parse($this->to)->endOfDay();

            if ($f->gt($t)) {
                session()->flash('error', 'From date must be before To date.');
                return;
            }

            $this->preset = 'custom';
        } catch (\Exception $e) {
            session()->flash('error', 'Invalid date format.');
        }
    }

    // ensure detection is run on each request (protected props not persisted by Livewire)
    protected function ensureSchema()
    {
        if (is_null($this->orderTable) || is_null($this->createdAtColumn)) {
            $this->detectSchema();
        }
    }

    protected function computeTotalRevenue(Carbon $from, Carbon $to): float
    {
        $this->ensureSchema();

        if ($this->totalColumn) {
            $row = DB::table($this->orderTable)
                ->whereBetween($this->createdAtColumn, [$from, $to])
                ->selectRaw("COALESCE(SUM(`{$this->totalColumn}`),0) as revenue")
                ->first();

            return (float) ($row->revenue ?? 0);
        }

        if ($this->itemsTable && $this->itemsQtyColumn && $this->itemsPriceColumn) {
            $orderFk = Schema::hasColumn($this->itemsTable, 'order_id') ? 'order_id'
                : (Schema::hasColumn($this->itemsTable, 'orderId') ? 'orderId' : null);

            if ($orderFk) {
                $row = DB::table($this->itemsTable.' as it')
                    ->join($this->orderTable.' as o', 'it.' . $orderFk, '=', 'o.id')
                    ->whereBetween('o.'.$this->createdAtColumn, [$from, $to])
                    ->selectRaw("COALESCE(SUM(it.`{$this->itemsQtyColumn}` * it.`{$this->itemsPriceColumn}`), 0) as revenue")
                    ->first();

                return (float) ($row->revenue ?? 0);
            }
        }

        session()->flash('error', 'No order total column nor order items table detected. Please add a total column or an order_items table.');
        return 0.0;
    }

    public function exportCsv()
    {
        $this->ensureSchema();

        if (! Schema::hasColumn($this->orderTable, $this->createdAtColumn)) {
            session()->flash('error', "Cannot export: column `{$this->createdAtColumn}` not found on `{$this->orderTable}`.");
            return;
        }

        $from = Carbon::parse($this->from)->startOfDay();
        $to   = Carbon::parse($this->to)->endOfDay();

        $orders = DB::table($this->orderTable)
            ->whereBetween($this->createdAtColumn, [$from, $to])
            ->orderBy($this->createdAtColumn, 'desc')
            ->get();

        $filename = 'orders-report-'.$from->format('Ymd').'_'.$to->format('Ymd').'.csv';

        $callback = function () use ($orders) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Order ID','Status','Total','Created At','Updated At']);

            foreach ($orders as $o) {
                $totalForOrder = 0;

                if ($this->totalColumn && isset($o->{$this->totalColumn})) {
                    $totalForOrder = $o->{$this->totalColumn};
                } elseif ($this->itemsTable && $this->itemsQtyColumn && $this->itemsPriceColumn) {
                    $orderFk = Schema::hasColumn($this->itemsTable, 'order_id') ? 'order_id' : 'orderId';
                    $sumRow = DB::table($this->itemsTable)
                        ->where($orderFk, $o->id)
                        ->selectRaw("COALESCE(SUM(`{$this->itemsQtyColumn}` * `{$this->itemsPriceColumn}`),0) as s")
                        ->first();
                    $totalForOrder = $sumRow->s ?? 0;
                }

                // Use detected created-at column (may not be 'created_at')
                $createdVal = $o->{$this->createdAtColumn} ?? '';

                fputcsv($handle, [
                    $o->id,
                    $o->status ?? '-',
                    number_format((float) $totalForOrder, 2),
                    $createdVal,
                    $o->updated_at ?? '',
                ]);
            }

            fclose($handle);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv',
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }

    public function getSummaryProperty()
    {
        $this->ensureSchema();

        if (! Schema::hasColumn($this->orderTable, $this->createdAtColumn)) {
            return (object) ['totalOrders' => 0, 'totalRevenue' => 0, 'avgOrder' => 0];
        }

        $from = Carbon::parse($this->from)->startOfDay();
        $to   = Carbon::parse($this->to)->endOfDay();

        $totalOrders = (int) DB::table($this->orderTable)->whereBetween($this->createdAtColumn, [$from, $to])->count();
        $totalRevenue = $this->computeTotalRevenue($from, $to);
        $avgOrder = $totalOrders ? $totalRevenue / $totalOrders : 0.0;

        return (object) [
            'totalOrders'  => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'avgOrder'     => $avgOrder,
        ];
    }

    public function getOrdersByDayProperty()
    {
        $this->ensureSchema();

        if (! Schema::hasColumn($this->orderTable, $this->createdAtColumn)) {
            return collect();
        }

        $from = Carbon::parse($this->from)->startOfDay();
        $to   = Carbon::parse($this->to)->endOfDay();

        if ($this->totalColumn) {
            $rows = DB::table($this->orderTable)
                ->selectRaw('DATE(`'.$this->createdAtColumn.'`) as date, COUNT(*) as orders_count, COALESCE(SUM(`'.$this->totalColumn.'`),0) as revenue')
                ->whereBetween($this->createdAtColumn, [$from, $to])
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            return $rows;
        }

        if ($this->itemsTable && $this->itemsQtyColumn && $this->itemsPriceColumn) {
            $orderFk = Schema::hasColumn($this->itemsTable, 'order_id') ? 'order_id' : 'orderId';

            $rows = DB::table($this->itemsTable.' as it')
                ->join($this->orderTable.' as o', 'it.'.$orderFk, '=', 'o.id')
                ->whereBetween('o.'.$this->createdAtColumn, [$from, $to])
                ->selectRaw('DATE(o.`'.$this->createdAtColumn.'`) as date, COUNT(DISTINCT it.'.$orderFk.') as orders_count, COALESCE(SUM(it.`'.$this->itemsQtyColumn.'` * it.`'.$this->itemsPriceColumn.'`),0) as revenue')
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            return $rows;
        }

        return collect();
    }

    public function getTopProductsProperty()
    {
        $this->ensureSchema();

        $from = Carbon::parse($this->from)->startOfDay();
        $to   = Carbon::parse($this->to)->endOfDay();

        if ($this->itemsTable && $this->productPivotProductId && $this->itemsQtyColumn) {
            $orderFk = Schema::hasColumn($this->itemsTable, 'order_id') ? 'order_id' : 'orderId';
            $pTable = $this->productTable;

            $rows = DB::table($this->itemsTable.' as it')
                ->join($this->orderTable.' as o', 'it.'.$orderFk, '=', 'o.id')
                ->join($pTable.' as p', 'it.'.$this->productPivotProductId, '=', 'p.id')
                ->whereBetween('o.'.$this->createdAtColumn, [$from, $to])
                ->selectRaw('p.id, p.name, COALESCE(SUM(it.'.$this->itemsQtyColumn.'),0) as qty_sold')
                ->groupBy('p.id','p.name')
                ->orderByDesc('qty_sold')
                ->limit(8)
                ->get();

            return $rows;
        }

        return Product::orderByDesc('created_at')->limit(8)->get();
    }

    public function render()
    {
        return view('livewire.admin.reports', [
            'summary'      => $this->summary,
            'ordersByDay'  => $this->ordersByDay,
            'topProducts'  => $this->topProducts,
        ]);
    }
}
