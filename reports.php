<?php
// pages/monitoring.php
require_once 'config/config.php';
include 'includes/header.php';
include 'includes/navigation.php';
?>

<!-- Main Content -->
<main class="container mx-auto px-4 py-8 lg:py-12 max-w-7xl">
    <!-- Page Header -->
    <div class="text-center mb-12">
        <h1 class="text-3xl lg:text-4xl font-bold text-gray-800 mb-4">
            Monitoring Reports
        </h1>
        <p class="text-lg text-gray-600 max-w-3xl mx-auto">
            Comprehensive monitoring reports for weather observation networks and numerical weather prediction systems
        </p>
    </div>

    <!-- Reports Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- GBON Reports -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6">
                <h2 class="text-xl font-bold text-white mb-2">GBON</h2>
                <p class="text-blue-100">Global Basic Observing Network</p>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <a href="<?= url('/reports/gbon/land-surface.php') ?>" 
                       class="block p-3 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-blue-300 transition-colors duration-200">
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-gray-800">Land Surface</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Surface weather stations monitoring</p>
                    </a>
                    
                    <a href="<?= url('/reports/gbon/marine.php') ?>" 
                       class="block p-3 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-blue-300 transition-colors duration-200">
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-gray-800">Marine</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Marine observation stations</p>
                    </a>
                </div>
            </div>
        </div>

        <!-- Near-real-time NWP -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6">
                <h2 class="text-xl font-bold text-white mb-2">Near-real-time NWP</h2>
                <p class="text-purple-100">Numerical Weather Prediction</p>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <a href="<?= url('/reports/nwp/land.php') ?>" 
                       class="block p-3 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-purple-300 transition-colors duration-200">
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-gray-800">Land</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Land surface NWP monitoring</p>
                    </a>
                    
                    <a href="<?= url('/reports/nwp/marine.php') ?>" 
                       class="block p-3 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-purple-300 transition-colors duration-200">
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-gray-800">Marine</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Marine and ocean NWP monitoring</p>
                    </a>
                </div>
            </div>
        </div>

        <!-- GCOS -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-green-600 p-6">
                <h2 class="text-xl font-bold text-white mb-2">GCOS</h2>
                <p class="text-green-100">Global Climate Observing System</p>
            </div>
            <div class="p-6">
                <a href="<?= url('/reports/gcos/index.php') ?>" 
                   class="block p-3 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-green-300 transition-colors duration-200">
                    <div class="flex items-center justify-between">
                        <span class="font-medium text-gray-800">Climate Monitoring</span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">Essential climate variables monitoring</p>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <div class="text-3xl font-bold text-blue-600 mb-2">490+</div>
            <div class="text-gray-600">Total Monitoring Stations</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <div class="text-3xl font-bold text-green-600 mb-2">92.8%</div>
            <div class="text-gray-600">Average Data Availability</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <div class="text-3xl font-bold text-purple-600 mb-2">12</div>
            <div class="text-gray-600">Active NWP Models</div>
        </div>
    </div>

    <!-- Recent Updates -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Recent Updates</h2>
        <div class="space-y-4">
            <div class="flex items-start space-x-3 p-3 bg-blue-50 rounded-lg">
                <div class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                <div>
                    <p class="text-sm font-medium text-gray-800">GBON Land Surface Report Updated</p>
                    <p class="text-xs text-gray-600">New station performance metrics added - 2 hours ago</p>
                </div>
            </div>
            <div class="flex items-start space-x-3 p-3 bg-purple-50 rounded-lg">
                <div class="flex-shrink-0 w-2 h-2 bg-purple-500 rounded-full mt-2"></div>
                <div>
                    <p class="text-sm font-medium text-gray-800">NWP Land & Marine Monitoring Enhanced</p>
                    <p class="text-xs text-gray-600">Separated land and marine model performance indicators - 4 hours ago</p>
                </div>
            </div>
            <div class="flex items-start space-x-3 p-3 bg-green-50 rounded-lg">
                <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                <div>
                    <p class="text-sm font-medium text-gray-800">GCOS Climate Variables Updated</p>
                    <p class="text-xs text-gray-600">Monthly climate monitoring report published - 1 day ago</p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?> 