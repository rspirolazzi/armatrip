<?php

namespace Tests\API;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

/**
 * Logs que reporta la web (POST /api/log/save): público, limitado y sin inyección de líneas.
 */
class WebLogTest extends TestCase
{
    use WithoutMiddleware;

    public function test_message_goes_to_context_without_line_breaks()
    {
        Log::shouldReceive('info')->once()->withArgs(function ($message, $context) {
            return $message === '[WEB] Mensaje reportado por la web'
                && $context['message'] === 'falla [2026-01-01] production.ERROR: falso'
                && mb_strlen($context['data']) === 5000;
        });

        $response = $this->json('POST', '/api/log/save', [
            'message' => "falla\n[2026-01-01] production.ERROR: falso",
            'data' => str_repeat('x', 6000),
        ]);

        $response->assertOk();
    }

    public function test_limits_ten_per_minute_without_failing()
    {
        Log::shouldReceive('info')->times(10);

        for ($i = 0; $i < 12; $i++) {
            $this->json('POST', '/api/log/save', ['message' => 'error ' . $i])->assertOk();
        }
    }
}
