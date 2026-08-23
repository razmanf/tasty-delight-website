<?php
// Test helper: directly read or set a product's stock value, bypassing all
// application cart/order logic. Used only by Playwright tests to simulate a
// concurrent stock-depleting purchase for race-condition-path verification.
//
// Usage:
//   php set-product-stock.php get <productId>
//   php set-product-stock.php set <productId> <stock>

require __DIR__ . '/../../../vendor/autoload.php';
$app = require __DIR__ . '/../../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

[, $action, $productId, $stock] = array_pad($argv, 4, null);

$product = App\Models\Product::find((int) $productId);
if (!$product) {
    fwrite(STDERR, "Product not found: {$productId}\n");
    exit(1);
}

if ($action === 'get') {
    echo $product->stock . "\n";
} elseif ($action === 'set') {
    $product->update(['stock' => (int) $stock]);
    echo "ok\n";
} else {
    fwrite(STDERR, "Unknown action: {$action}\n");
    exit(1);
}