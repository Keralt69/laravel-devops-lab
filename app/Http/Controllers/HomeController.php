<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Show the main application dashboard.
     */
    public function index()
    {
        $dbStatus = 'Connected';
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $dbStatus = 'Disconnected / Not configured (' . $e->getMessage() . ')';
        }

        $appInfo = [
            'app_name' => config('app.name', 'Laravel DevOps CI/CD Lab'),
            'environment' => app()->environment(),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_ip' => request()->server('SERVER_ADDR', request()->ip()),
            'hostname' => gethostname(),
            'db_status' => $dbStatus,
            'deployment_stage' => env('DEPLOYMENT_STAGE', 'Local/Production'),
            'build_number' => env('BUILD_NUMBER', 'v1.0.0'),
            'timestamp' => now()->toDateTimeString(),
        ];

        return view('welcome', compact('appInfo'));
    }

    /**
     * Health check endpoint for Jenkins CI/CD pipeline verification.
     */
    public function health()
    {
        return response()->json([
            'status' => 'UP',
            'timestamp' => now()->toIso8601String(),
            'app' => config('app.name'),
            'environment' => app()->environment(),
            'php' => PHP_VERSION,
            'laravel' => app()->version(),
            'healthy' => true,
        ], 200);
    }
}
