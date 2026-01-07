<?php
// reports/nwp/marine.php
require_once '../../config/config.php';
include '../../includes/header.php';
include '../../includes/navigation.php';
?>

<!-- Main Content -->
<main class="container mx-auto px-4 py-8 lg:py-12 max-w-7xl">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="<?= url('/') ?>" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-bmkg-blue">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                    Home
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="<?= url('/reports.php') ?>" class="ml-1 text-sm font-medium text-gray-700 hover:text-bmkg-blue md:ml-2">Monitoring Reports</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Near-real-time NWP - Marine</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl lg:text-4xl font-bold text-gray-800 mb-4">
            Near-real-time NWP - Marine Monitoring
        </h1>
        <p class="text-lg text-gray-600 max-w-3xl mx-auto">
            Monitoring Numerical Weather Prediction untuk area laut dan pesisir dalam waktu mendekati real-time
        </p>
    </div>

    <!-- Report Content -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
        <!-- Statistics Cards -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Marine NWP Models</p>
                    <p class="text-2xl font-bold text-gray-900">6</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Models</p>
                    <p class="text-2xl font-bold text-green-600">5</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Wave Latency</p>
                    <p class="text-2xl font-bold text-bmkg-blue">3.2min</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Ocean Success Rate</p>
                    <p class="text-2xl font-bold text-green-600">97.4%</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Marine Models Status -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Marine Model Performance</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="border rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-medium text-gray-800">WaveWatch III</h3>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Active</span>
                </div>
                <p class="text-sm text-gray-600">Last Update: 2 minutes ago</p>
                <div class="mt-2">
                    <div class="text-xs text-gray-500">Wave Accuracy: 98.1%</div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 98.1%"></div>
                    </div>
                </div>
            </div>

            <div class="border rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-medium text-gray-800">HYCOM</h3>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Active</span>
                </div>
                <p class="text-sm text-gray-600">Last Update: 1 minute ago</p>
                <div class="mt-2">
                    <div class="text-xs text-gray-500">Ocean Accuracy: 96.8%</div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 96.8%"></div>
                    </div>
                </div>
            </div>

            <div class="border rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-medium text-gray-800">NEMO</h3>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Active</span>
                </div>
                <p class="text-sm text-gray-600">Last Update: 3 minutes ago</p>
                <div class="mt-2">
                    <div class="text-xs text-gray-500">Current Accuracy: 94.5%</div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 94.5%"></div>
                    </div>
                </div>
            </div>

            <div class="border rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-medium text-gray-800">SWAN</h3>
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Delayed</span>
                </div>
                <p class="text-sm text-gray-600">Last Update: 7 minutes ago</p>
                <div class="mt-2">
                    <div class="text-xs text-gray-500">Wave Accuracy: 91.2%</div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                        <div class="bg-yellow-600 h-2 rounded-full" style="width: 91.2%"></div>
                    </div>
                </div>
            </div>

            <div class="border rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-medium text-gray-800">ROMS</h3>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Active</span>
                </div>
                <p class="text-sm text-gray-600">Last Update: 2 minutes ago</p>
                <div class="mt-2">
                    <div class="text-xs text-gray-500">Coastal Accuracy: 97.3%</div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 97.3%"></div>
                    </div>
                </div>
            </div>

            <div class="border rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-medium text-gray-800">SCHISM</h3>
                    <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Offline</span>
                </div>
                <p class="text-sm text-gray-600">Last Update: 45 minutes ago</p>
                <div class="mt-2">
                    <div class="text-xs text-gray-500">Accuracy: 88.9%</div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                        <div class="bg-red-600 h-2 rounded-full" style="width: 88.9%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Marine Parameters -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Wave Parameters</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Significant Wave Height</span>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">98.1%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Wave Period</span>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">96.7%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Wave Direction</span>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">95.4%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Swell Height</span>
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">92.8%</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Ocean Currents</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Surface Current Speed</span>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">96.8%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Current Direction</span>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">97.2%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Subsurface Current</span>
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">89.5%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Tidal Current</span>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">94.3%</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Sea Surface Parameters</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Sea Surface Temperature</span>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">98.9%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Sea Level Pressure</span>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">99.1%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Salinity</span>
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">91.7%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Sea Ice Coverage</span>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">95.6%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Marine Performance Charts -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Real-time Marine Performance</h2>
        <div class="text-center py-8">
            <div class="bg-gray-100 rounded-lg p-8">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-600 mb-2">Marine Charts Coming Soon</h3>
                <p class="text-gray-500">Real-time marine model performance charts, wave forecasts, and ocean current analysis will be displayed here.</p>
            </div>
        </div>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>