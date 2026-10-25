<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
*/
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Run Through Full Laravel Framework if Vendor Exists
|--------------------------------------------------------------------------
*/
if (file_exists(__DIR__.'/../vendor/autoload.php')) {
    require __DIR__.'/../vendor/autoload.php';
    $app = require_once __DIR__.'/../bootstrap/app.php';
    $kernel = $app->make(Kernel::class);

    $response = $kernel->handle(
        $request = Request::capture()
    )->send();

    $kernel->terminate($request, $response);
    exit;
}

/*
|--------------------------------------------------------------------------
| Standalone Preview Mode (Runs immediately without composer install)
|--------------------------------------------------------------------------
*/
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

// Health check endpoint
if ($uri === '/health') {
    header('Content-Type: application/json');
    http_response_code(200);
    echo json_encode([
        'status' => 'UP',
        'healthy' => true,
        'app' => 'Laravel DevOps CI/CD Lab',
        'environment' => 'local',
        'php' => PHP_VERSION,
        'mode' => 'preview-runner',
        'timestamp' => date('c'),
    ], JSON_PRETTY_PRINT);
    exit;
}

// Ping API endpoint
if ($uri === '/api/ping') {
    header('Content-Type: application/json');
    http_response_code(200);
    echo json_encode([
        'message' => 'pong',
        'status' => 'healthy',
        'timestamp' => time(),
    ]);
    exit;
}

// Main Dashboard
$viewPath = __DIR__ . '/../resources/views/welcome.blade.php';
$content = file_exists($viewPath) ? file_get_contents($viewPath) : '<h1>Laravel DevOps Lab</h1>';

// Substitute template variables for immediate rendering
$dbStatus = file_exists(__DIR__ . '/../database/database.sqlite') ? 'Connected (SQLite)' : 'SQLite Ready';
$replacements = [
    "{{ config('app.name', 'Laravel DevOps CI/CD Lab') }}" => 'Laravel DevOps CI/CD Lab',
    "{{ \$appInfo['app_name'] }}" => 'Laravel DevOps CI/CD Lab',
    "{{ \$appInfo['environment'] }}" => 'local',
    "{{ \$appInfo['deployment_stage'] }}" => 'Local-Direct-Runner',
    "{{ \$appInfo['hostname'] }}" => gethostname(),
    "{{ \$appInfo['php_version'] }}" => PHP_VERSION,
    "{{ \$appInfo['laravel_version'] }}" => '10.x (DevOps Ready)',
    "{{ \$appInfo['db_status'] }}" => $dbStatus,
    "{{ \$appInfo['timestamp'] }}" => date('Y-m-d H:i:s'),
    "{{ str_replace('_', '-', app()->getLocale()) }}" => 'en',
];

$rendered = str_replace(array_keys($replacements), array_values($replacements), $content);
echo $rendered;
