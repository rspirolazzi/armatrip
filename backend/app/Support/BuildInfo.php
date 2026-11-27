<?php

namespace App\Support;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Versiones desplegadas: el build del backend (config/app.php) y la versión
 * de la web, que publica version.json junto a sus archivos.
 */
class BuildInfo
{
    /**
     * Texto del build: "build 42 (abc1234, 27/11/2026)", o "dev" sin datos del CI.
     */
    public static function label($build)
    {
        $build = is_array($build) ? $build : [];
        if (empty($build['number'])) {
            return 'dev';
        }
        $details = array_filter([
            $build['sha'] ?? null,
            !empty($build['date']) ? date('d/m/Y', strtotime($build['date'])) : null,
        ]);
        return 'build ' . $build['number'] . ($details ? ' (' . implode(', ', $details) . ')' : '');
    }

    public static function api()
    {
        return 'API ' . self::label(config('app.build'));
    }

    /**
     * Lee la versión de la web. Se cachea para no consultarla en cada página del panel.
     */
    public static function web()
    {
        return Cache::remember('build-info:web', 10, function () {
            try {
                $url = rtrim(config('app.webUrl'), '/') . '/version.json';
                $response = (new Client(['timeout' => 2, 'connect_timeout' => 2]))->get($url);
                $data = json_decode((string) $response->getBody(), true);
                if (!is_array($data) || empty($data['version'])) {
                    return 'Web: no disponible';
                }
                return 'Web ' . $data['version'] . ' · ' . self::label($data['build'] ?? []);
            } catch (\Exception $e) {
                Log::warning('No se pudo leer la versión de la web', ['message' => $e->getMessage()]);
                return 'Web: no disponible';
            }
        });
    }
}
