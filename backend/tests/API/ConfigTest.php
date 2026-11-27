<?php

namespace Tests\API;

use App\Support\BuildInfo;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCaseWithDatabase;

/**
 * GET /api/config incluye el build del backend para el footer de la web.
 * Sin middleware: CORS llama a header() y en consola falla si PHPUnit ya imprimió salida.
 */
class ConfigTest extends TestCaseWithDatabase
{
    use WithoutMiddleware;

    public function test_config_includes_build()
    {
        config(['app.build' => ['number' => '42', 'sha' => 'abc1234', 'date' => '2026-11-27']]);

        $response = $this->json('GET', '/api/config');

        $response->assertOk();
        $response->assertJson(['data' => ['build' => ['number' => '42', 'sha' => 'abc1234', 'date' => '2026-11-27']]]);
    }

    public function test_build_label()
    {
        $this->assertEquals('build 42 (abc1234, 27/11/2026)', BuildInfo::label(['number' => '42', 'sha' => 'abc1234', 'date' => '2026-11-27']));
        $this->assertEquals('dev', BuildInfo::label(['number' => null]));
    }
}
