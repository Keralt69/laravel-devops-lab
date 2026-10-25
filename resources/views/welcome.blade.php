<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel DevOps CI/CD Lab') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600,700&display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            body { font-family: 'Figtree', sans-serif; }
        </style>
    </head>
    <body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col justify-between antialiased">
        <div class="relative min-h-screen flex flex-col items-center justify-center p-6">
            <!-- Header -->
            <div class="w-full max-w-4xl bg-slate-800/80 backdrop-blur border border-slate-700 rounded-2xl shadow-2xl p-8 mb-6">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4 pb-6 border-b border-slate-700">
                    <div class="flex items-center space-x-3">
                        <div class="h-12 w-12 bg-red-600 rounded-xl flex items-center justify-center text-white font-bold text-2xl shadow-lg shadow-red-500/30">
                            🚀
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-white">
                                {{ $appInfo['app_name'] }}
                            </h1>
                            <p class="text-sm text-slate-400">Automated Jenkins CI/CD Deployment</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                            <span class="w-2 h-2 mr-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                            Pipeline Live (v1.0.0)
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/20 text-indigo-400 border border-indigo-500/30">
                            Stage: {{ $appInfo['deployment_stage'] }}
                        </span>
                    </div>
                </div>

                <!-- Server & Deployment Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 my-6">
                    <div class="bg-slate-900/60 p-4 rounded-xl border border-slate-700/60">
                        <div class="text-xs font-medium text-slate-400 uppercase tracking-wider">Environment</div>
                        <div class="text-lg font-bold text-sky-400 mt-1 capitalize">{{ $appInfo['environment'] }}</div>
                        <div class="text-xs text-slate-500 mt-1">Host: {{ $appInfo['hostname'] }}</div>
                    </div>
                    <div class="bg-slate-900/60 p-4 rounded-xl border border-slate-700/60">
                        <div class="text-xs font-medium text-slate-400 uppercase tracking-wider">Runtimes</div>
                        <div class="text-lg font-bold text-amber-400 mt-1">PHP {{ $appInfo['php_version'] }}</div>
                        <div class="text-xs text-slate-500 mt-1">Laravel {{ $appInfo['laravel_version'] }}</div>
                    </div>
                    <div class="bg-slate-900/60 p-4 rounded-xl border border-slate-700/60">
                        <div class="text-xs font-medium text-slate-400 uppercase tracking-wider">Database Status</div>
                        <div class="text-lg font-bold text-emerald-400 mt-1">{{ $appInfo['db_status'] }}</div>
                        <div class="text-xs text-slate-500 mt-1">Server Time: {{ $appInfo['timestamp'] }}</div>
                    </div>
                </div>

                <!-- Lab Tasks Checklist -->
                <div class="mt-8">
                    <h2 class="text-lg font-bold text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        CI/CD Lab Implementation Tasks
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                        <div class="p-3 bg-slate-900/40 border border-slate-700/80 rounded-lg flex items-start space-x-3">
                            <span class="text-emerald-400 font-bold">✔ Task 1</span>
                            <div>
                                <strong class="text-slate-200">Direct Local Deployment:</strong>
                                <p class="text-slate-400 text-xs">Jenkins → Git → Local PHP Environment</p>
                            </div>
                        </div>
                        <div class="p-3 bg-slate-900/40 border border-slate-700/80 rounded-lg flex items-start space-x-3">
                            <span class="text-emerald-400 font-bold">✔ Task 2</span>
                            <div>
                                <strong class="text-slate-200">Docker Local Deployment:</strong>
                                <p class="text-slate-400 text-xs">Jenkins → Docker Build → Local Container</p>
                            </div>
                        </div>
                        <div class="p-3 bg-slate-900/40 border border-slate-700/80 rounded-lg flex items-start space-x-3">
                            <span class="text-emerald-400 font-bold">✔ Task 3</span>
                            <div>
                                <strong class="text-slate-200">Direct AWS EC2 Deployment:</strong>
                                <p class="text-slate-400 text-xs">Jenkins → SSH → EC2 Nginx & PHP-FPM</p>
                            </div>
                        </div>
                        <div class="p-3 bg-slate-900/40 border border-slate-700/80 rounded-lg flex items-start space-x-3">
                            <span class="text-emerald-400 font-bold">✔ Task 4</span>
                            <div>
                                <strong class="text-slate-200">Docker AWS EC2 Deployment:</strong>
                                <p class="text-slate-400 text-xs">Jenkins → Image Archive → EC2 Docker Container</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Task 1 Verification Notice -->
                <div class="mt-6 p-4 rounded-xl bg-indigo-950/40 border border-indigo-800/40 text-xs text-indigo-300">
                    <p class="font-semibold text-indigo-200 mb-1">💡 Blade View Verification Instructions (Task 1 Procedure):</p>
                    <p>To verify automated continuous deployment: modify this text in <code class="bg-indigo-900/80 px-1 py-0.5 rounded text-indigo-100">resources/views/welcome.blade.php</code> (e.g. change version badge to v1.1), commit and push to GitHub. Jenkins will trigger the build and deploy automatically!</p>
                </div>
            </div>

            <!-- Footer -->
            <footer class="text-center text-xs text-slate-500">
                DevOps Lab Manual Assignment &bull; Jenkins CI/CD Pipeline for Laravel Deployment
            </footer>
        </div>
    </body>
</html>
