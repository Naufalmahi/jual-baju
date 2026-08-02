<?php

namespace Tests\Unit;

use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Storage;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Tests\TestCase;

class BarcodeGeneratorTest extends TestCase
{
    public function test_picqer_barcode_generator_autoloads_and_generates_png(): void
    {
        $generator = new BarcodeGeneratorPNG();
        $png = $generator->getBarcode('JB-2026-0001', $generator::TYPE_CODE_128);

        $this->assertNotEmpty($png);
        $this->assertStringStartsWith("\x89PNG", $png);
    }

    public function test_product_controller_generates_barcode_image_to_storage(): void
    {
        Storage::fake('public');

        $controller = new ProductController();
        $method = new \ReflectionMethod(ProductController::class, 'generateBarcodeImage');
        $path = $method->invoke($controller, 'JB-2026-0001');

        $this->assertStringStartsWith('barcodes/', $path);
        Storage::disk('public')->assertExists($path);
    }
}
