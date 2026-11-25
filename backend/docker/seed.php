<?php

// Siembra la base solo si todavía no hay usuarios.

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

if (Illuminate\Support\Facades\DB::table('users')->exists()) {
    exit(0);
}

exit($kernel->call('db:seed', ['--force' => true], new Symfony\Component\Console\Output\ConsoleOutput()));
