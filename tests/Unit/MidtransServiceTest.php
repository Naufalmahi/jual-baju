<?php

namespace Tests\Unit;

use App\Services\MidtransService;
use Tests\TestCase;

class MidtransServiceTest extends TestCase
{
    public function test_get_snap_url_returns_sandbox_url_when_not_production(): void
    {
        config(['midtrans.snap_url' => 'https://app.sandbox.midtrans.com/snap/snap.js']);

        $service = new MidtransService();

        $this->assertSame('https://app.sandbox.midtrans.com/snap/snap.js', $service->getSnapUrl());
    }

    public function test_get_snap_url_returns_production_url_when_production(): void
    {
        config(['midtrans.snap_url' => 'https://app.midtrans.com/snap/snap.js']);

        $service = new MidtransService();

        $this->assertSame('https://app.midtrans.com/snap/snap.js', $service->getSnapUrl());
    }
}
