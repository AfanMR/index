<?php
// reports/nwp/land.php
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
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Near-real-time NWP - Land</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl lg:text-4xl font-bold text-gray-800 mb-4">
            Near-real-time NWP - Land Monitoring
        </h1>
        <p class="text-lg text-gray-600 max-w-3xl mx-auto">
            Monitoring Numerical Weather Prediction untuk stasiun darat dalam waktu mendekati real-time
        </p>
    </div>

    <!-- Report Content -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
        <!-- Statistics Cards -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Land NWP Models</p>
                    <p class="text-2xl font-bold text-gray-900">8</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Models</p>
                    <p class="text-2xl font-bold text-green-600">7</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Data Latency</p>
                    <p class="text-2xl font-bold text-bmkg-blue">1.8min</p>
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
                    <p class="text-sm font-medium text-gray-600">Success Rate</p>
                    <p class="text-2xl font-bold text-green-600">99.2%</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Land Surface Models Status -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Land Surface Model Performance</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="border rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-medium text-gray-800">GFS Land</h3>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Active</span>
                </div>
                <p class="text-sm text-gray-600">Last Update: 1 minute ago</p>
                <div class="mt-2">
                    <div class="text-xs text-gray-500">Timeliness: 99.8%</div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 99.8%"></div>
                    </div>
                </div>
            </div>

            <div class="border rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-medium text-gray-800">ECMWF Land</h3>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Active</span>
                </div>
                <p class="text-sm text-gray-600">Last Update: 2 minutes ago</p>
                <div class="mt-2">
                    <div class="text-xs text-gray-500">Timeliness: 99.1%</div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 99.1%"></div>
                    </div>
                </div>
            </div>

            <div class="border rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-medium text-gray-800">NAM</h3>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Active</span>
                </div>
                <p class="text-sm text-gray-600">Last Update: 1 minute ago</p>
                <div class="mt-2">
                    <div class="text-xs text-gray-500">Timeliness: 98.4%</div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 98.4%"></div>
                    </div>
                </div>
            </div>

            <div class="border rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-medium text-gray-800">HRRR</h3>
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Delayed</span>
                </div>
                <p class="text-sm text-gray-600">Last Update: 6 minutes ago</p>
                <div class="mt-2">
                    <div class="text-xs text-gray-500">Timeliness: 94.7%</div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                        <div class="bg-yellow-600 h-2 rounded-full" style="width: 94.7%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Land Surface Parameters -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Surface Parameters</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Temperature (2m)</span>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">99.5%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Precipitation</span>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">98.8%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Wind Speed</span>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">99.1%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Humidity</span>
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">96.2%</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Soil Parameters</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Soil Temperature</span>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">97.8%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Soil Moisture</span>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">98.3%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Snow Depth</span>
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">92.1%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Albedo</span>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">95.7%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Performance Charts -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Real-time Performance Charts</h2>
        <div class="text-center py-8">
            <div class="bg-gray-100 rounded-lg p-8">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-600 mb-2">Land Surface Charts Coming Soon</h3>
                <p class="text-gray-500">Real-time land surface model performance charts and analytics will be displayed here.</p>
            </div>
        </div>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>