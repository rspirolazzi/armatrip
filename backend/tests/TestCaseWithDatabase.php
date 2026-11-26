<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * Tests con base de datos.
 *
 * ⚠️ Recrea la base configurada (migrate:fresh --seed) una vez por ejecución y
 * corre cada test dentro de una transacción que se descarta al terminar.
 * Nunca correrlo contra datos reales (docs/analysis-backend.md, sección Tests).
 */
abstract class TestCaseWithDatabase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    /**
     * Igual que RefreshDatabase, pero siembra la base: el registro necesita
     * empresas, provincias y paquetes con fechas.
     */
    protected function refreshTestDatabase()
    {
        if (! RefreshDatabaseState::$migrated) {
            $this->artisan('migrate:fresh', ['--seed' => true]);

            $this->app[Kernel::class]->setArtisan(null);

            RefreshDatabaseState::$migrated = true;
        }

        $this->beginDatabaseTransaction();
    }
}
