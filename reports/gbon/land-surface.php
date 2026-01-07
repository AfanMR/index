<?php
// reports/gbon/land-surface.php
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
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">GBON - Land Surface</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl lg:text-4xl font-bold text-gray-800 mb-4">
            GBON Land Surface Monitoring Report
        </h1>
        <p class="text-lg text-gray-600 max-w-3xl mx-auto">
            Laporan monitoring untuk Global Basic Observing Network (GBON) - Land Surface stations
        </p>
    </div>

    <!-- Control Panel -->
    <div class="bg-white rounded-lg shadow-md p-4 lg:p-6 mb-6 relative">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-4 mb-4">
            <!-- Period Type -->
            <div class="flex flex-col">
                <label for="periodTypeSelect" class="text-sm font-medium text-gray-700 mb-1">Period Type</label>
                <select id="periodTypeSelect" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-bmkg-blue focus:border-transparent">
                    <option value="monthly" selected>Monthly</option>
                    <option value="daily">Daily</option>
                    <option value="six_hour">6 Hours</option>
                </select>
            </div>

            <!-- Variable Selector -->
            <div class="flex flex-col">
                <label for="variableSelect" class="text-sm font-medium text-gray-700 mb-1">Variable</label>
                <select id="variableSelect" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-bmkg-blue focus:border-transparent">
                    <option value="pressure">Pressure</option>
                    <option value="temperature">Temperature</option>
                    <option value="zonal_wind">Zonal Wind</option>
                    <option value="meridional_wind">Meridional Wind</option>
                    <option value="humidity">Humidity</option>
                </select>
            </div>

            <!-- Region Selector -->
            <div class="flex flex-col">
                <label for="regionSelect" class="text-sm font-medium text-gray-700 mb-1">Region</label>
                <select id="regionSelect" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-bmkg-blue focus:border-transparent">
                    <option value="SGP,IDN,BRN,PHL,TLS,PNG,MYS">Region V</option>
                    <option value="IDN">Indonesia</option>
                    <option value="SGP">Singapore</option>
                    <option value="MYS">Malaysia</option>
                    <option value="PHL">Philippines</option>
                    <option value="TLS">East Timor</option>
                    <option value="PNG">Papua New Guinea</option>
                    <option value="BRN">Brunei</option>
                    <option value="USA_PACIFIC">USA Pacific</option>
                </select>
            </div>

            <!-- Station Selector (Multi-select) -->
            <div class="flex flex-col relative" id="station-select-container">
                <label class="text-sm font-medium text-gray-700 mb-1">Station</label>
                <button type="button" id="stationDropdownBtn" class="px-3 py-2 border border-gray-300 rounded-md text-sm text-left bg-white focus:outline-none focus:ring-2 focus:ring-bmkg-blue focus:border-transparent flex justify-between items-center">
                    <span class="truncate" id="stationDropdownLabel">All Stations</span>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                
                <!-- Dropdown Menu -->
                <div id="stationDropdownMenu" class="hidden absolute top-full left-0 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg z-50 max-h-60 flex flex-col">
                    <div class="p-2 border-b border-gray-200">
                        <input type="text" id="stationSearchInput" placeholder="Search stations..." class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:outline-none focus:border-bmkg-blue">
                    </div>
                    <div class="p-2 border-b border-gray-200 bg-gray-50 flex items-center">
                        <input type="checkbox" id="selectAllStations" class="rounded text-bmkg-blue focus:ring-bmkg-blue h-3 w-3 mr-2">
                        <label for="selectAllStations" class="text-xs font-medium text-gray-700 cursor-pointer select-none">Select All</label>
                    </div>
                    <div id="stationListContainer" class="overflow-y-auto flex-1 p-1">
                        <!-- Checkboxes will be injected here -->
                    </div>
                </div>
            </div>

            <!-- Center Selector -->
            <div class="flex flex-col">
                <label for="centerSelect" class="text-sm font-medium text-gray-700 mb-1">Center</label>
                <select id="centerSelect" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-bmkg-blue focus:border-transparent">
                    <option value="ALL">All Centers</option>
                    <option value="DWD">DWD</option>
                    <option value="ECMWF">ECMWF</option>
                    <option value="JMA">JMA</option>
                    <option value="NCEP">NCEP</option>
                </select>
            </div>

            <!-- Date Selector -->
            <div class="flex flex-col">
                <label for="dateSelect" class="text-sm font-medium text-gray-700 mb-1">Date</label>
                <input type="month" id="dateSelect" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-bmkg-blue focus:border-transparent">
            </div>
        </div>

        <!-- Time Period Buttons (for 6-hour period only) -->
        <div id="timePeriodContainer" class="hidden flex-col mb-4">
            <label class="text-sm font-medium text-gray-700 mb-2">Time Period (UTC)</label>
            <div class="flex gap-2 flex-wrap">
                <button class="time-period-btn px-3 py-2 border border-gray-300 rounded-md text-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-bmkg-blue" data-period="00">00:00</button>
                <button class="time-period-btn px-3 py-2 border border-gray-300 rounded-md text-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-bmkg-blue" data-period="06">06:00</button>
                <button class="time-period-btn px-3 py-2 border border-gray-300 rounded-md text-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-bmkg-blue" data-period="12">12:00</button>
                <button class="time-period-btn px-3 py-2 border border-gray-300 rounded-md text-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-bmkg-blue" data-period="18">18:00</button>
            </div>
        </div>

        <!-- Status Display -->
        <div class="flex justify-end">
            <div class="text-xs text-gray-500" id="lastUpdateTime">
                Loading data...
            </div>
        </div>

        <!-- Loading Overlay -->
        <div id="loadingOverlay" class="hidden absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center z-10 rounded-lg">
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-bmkg-blue mx-auto mb-4"></div>
                <div class="text-sm text-gray-600">Loading land surface data...</div>
            </div>
        </div>
    </div>

    <!-- Report Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Statistics Cards -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Stations</p>
                    <p id="totalStations" class="text-2xl font-bold text-gray-900">
                        <span class="loading-spinner animate-pulse">Loading...</span>
                    </p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Complete Stations</p>
                    <p id="activeStations" class="text-2xl font-bold text-green-600">
                        <span class="loading-spinner animate-pulse">Loading...</span>
                    </p>
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
                    <p class="text-sm font-medium text-gray-600">Average Availability</p>
                    <p id="dataAvailability" class="text-2xl font-bold text-bmkg-blue">
                        <span class="loading-spinner animate-pulse">Loading...</span>
                    </p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Charts Section -->
    <div class="bg-white rounded-lg shadow-md p-4 lg:p-6 mb-6">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
            <h2 class="text-xl font-semibold text-gray-800">Station Data Analytics</h2>
            <!-- Legend -->
            <div class="flex flex-wrap gap-4 text-xs">
                <div class="flex items-center gap-1">
                    <div class="w-3 h-3 bg-green-500 rounded"></div>
                    <span>Complete (≥ 80%)</span>
                </div>
                <div class="flex items-center gap-1">
                    <div class="w-3 h-3 bg-yellow-500 rounded"></div>
                    <span>Issues (≥ 30%)</span>
                </div>
                <div class="flex items-center gap-1">
                    <div class="w-3 h-3 bg-red-500 rounded"></div>
                    <span>Critical (< 30%)</span>
                </div>
                <div class="flex items-center gap-1">
                    <div class="w-3 h-3 bg-gray-500 rounded"></div>
                    <span>Not Received</span>
                </div>
            </div>
        </div>
        
        <!-- Charts Container -->
        <div class="flex flex-col gap-6 mb-8">
            <!-- Time-Series Data Chart -->
            <div class="bg-gray-50 rounded-lg p-4 lg:p-6 relative">
                <div class="flex justify-between items-center mb-4">
                    <div class="text-lg font-semibold text-gray-700">Data Received Over Time</div>
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex items-center gap-1">
                        <label class="text-xs text-gray-600">View:</label>
                        <select id="chartPeriodSelect" class="text-xs border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-bmkg-blue">
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-1" id="dayRangeContainer">
                        <label class="text-xs text-gray-600">Range:</label>
                        <select id="dayRangeSelect" class="text-xs border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-bmkg-blue">
                            <option value="7">7 Hari</option>
                            <option value="14">14 Hari</option>
                            <option value="30">30 Hari</option>
                        </select>
                    </div>
                </div>
                </div>
                <div class="relative w-full" style="height: 350px;">
                    <canvas id="allStationsChart" class="w-full h-full"></canvas>
                </div>
                <div id="allStationsChartLoading" class="hidden absolute inset-0 bg-gray-50 bg-opacity-90 flex items-center justify-center rounded-lg">
                    <div class="text-center">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-bmkg-blue mx-auto mb-3"></div>
                        <div class="text-sm text-gray-600">Loading time-series data...</div>
                        <div class="text-xs text-gray-400 mt-1">This may take a moment</div>
                    </div>
                </div>
            </div>
            
            <!-- Status Distribution Pie Chart and Chart Type Selector -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Status Distribution -->
                <div class="bg-gray-50 rounded-lg p-4 lg:p-6 relative">
                    <div class="flex justify-between items-center mb-4">
                        <div class="text-lg font-semibold text-gray-700">Status Distribution (Selected Stations)</div>
                        <select id="chartTypeSelect" class="text-xs border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-bmkg-blue">
                            <option value="pie">Pie Chart</option>
                            <option value="doughnut">Doughnut Chart</option>
                            <option value="bar">Bar Chart</option>
                        </select>
                    </div>
                    <div class="relative h-64 lg:h-80">
                        <canvas id="statusChart" class="w-full h-full"></canvas>
                        <div id="statusChartLoading" class="hidden absolute inset-0 bg-gray-50 bg-opacity-90 flex items-center justify-center">
                            <div class="text-center">
                                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-bmkg-blue mx-auto mb-2"></div>
                                <div class="text-xs text-gray-600">Loading chart...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Station Information Table -->
    <div class="bg-white rounded-lg shadow-md p-4 lg:p-6 relative">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Weather Station Information</h2>
            <div class="text-sm text-gray-500" id="stationCount">
                Loading stations...
            </div>
        </div>

        <!-- Table Loading Overlay -->
        <div id="tableLoadingOverlay" class="hidden absolute inset-0 bg-white bg-opacity-90 flex items-center justify-center z-10 rounded-lg">
            <div class="text-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-bmkg-blue mx-auto mb-3"></div>
                <div class="text-sm text-gray-600">Loading station data...</div>
            </div>
        </div>
        
        <!-- Error Message Container -->
        <div id="errorMessage" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
            <div class="flex">
                <svg class="w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <span id="errorText">Error loading data</span>
            </div>
        </div>
        
        <!-- Table Container with horizontal scroll on mobile -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse bg-white rounded-lg overflow-hidden shadow-sm">
                <thead>
                    <tr class="bg-bmkg-blue text-white">
                        <th class="px-3 lg:px-4 py-3 text-left font-semibold text-sm">Station Name</th>
                        <th class="px-3 lg:px-4 py-3 text-center font-semibold text-sm hidden sm:table-cell">WIGOS ID</th>
                        <th class="px-3 lg:px-4 py-3 text-center font-semibold text-sm">Country</th>
                        <th class="px-3 lg:px-4 py-3 text-center font-semibold text-sm hidden md:table-cell">Coordinates</th>
                        <th class="px-3 lg:px-4 py-3 text-center font-semibold text-sm">DWD</th>
                        <th class="px-3 lg:px-4 py-3 text-center font-semibold text-sm">ECMWF</th>
                        <th class="px-3 lg:px-4 py-3 text-center font-semibold text-sm">JMA</th>
                        <th class="px-3 lg:px-4 py-3 text-center font-semibold text-sm">NCEP</th>
                        <th class="px-3 lg:px-4 py-3 text-center font-semibold text-sm">Overall</th>
                    </tr>
                </thead>
                <tbody id="station-table-body" class="divide-y divide-gray-200">
                    <tr>
                        <td colspan="11" class="px-4 py-8 text-center text-gray-500">
                            <div class="animate-pulse flex items-center justify-center">
                                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-bmkg-blue mr-2"></div>
                                Loading station data...
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Controls -->
        <div class="flex flex-col sm:flex-row justify-between items-center mt-4 pt-4 border-t border-gray-200">
            <div class="flex items-center text-sm text-gray-700 mb-4 sm:mb-0">
                <span>Show </span>
                <select id="entriesPerPage" class="mx-2 px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-bmkg-blue">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span> entries</span>
                <span class="ml-4" id="paginationInfo">Showing 0 to 0 of 0 entries</span>
            </div>
            
            <div class="flex items-center space-x-1">
                <button id="prevPage" class="px-3 py-1 text-sm border border-gray-300 rounded hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                
                <div id="pageNumbers" class="flex space-x-1">
                    <!-- Page numbers will be generated here -->
                </div>
                
                <button id="nextPage" class="px-3 py-1 text-sm border border-gray-300 rounded hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Footer with sync info -->
        <div class="flex justify-between items-center text-xs text-gray-500 italic mt-4 pt-4 border-t border-gray-200">
            <div>
                Data source: WMO WDQMS API
            </div>
            <div id="dataTimestamp">
                Loading...
            </div>
        </div>
    </div>
</main>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Reports Land Surface JS (Removed to avoid conflict) -->
<!-- <script src="../../assets/js/reports_land_surface.js"></script> -->

<script>
    // Configuration Constants
    const API_ENDPOINT = '../../api/';
    const CENTERS = ['DWD', 'ECMWF', 'JMA', 'NCEP'];
    
    // Global variables
    let currentStations = [];
    let statusChart = null;
    let allStationsChart = null; // Renamed from coverageChart
    let isLoading = false;
    let selectedStationIds = new Set(); // Track selected stations
    
    // Pagination variables
    let currentPage = 1;
    let entriesPerPage = 10;
    let totalEntries = 0;
    let filteredStations = [];

    // Loading functions
    function showControlLoading(show = true) {
        const overlay = document.getElementById('loadingOverlay');
        if (show) {
            overlay.classList.remove('hidden');
        } else {
            overlay.classList.add('hidden');
        }
    }

    function showTableLoading(show = true) {
        const overlay = document.getElementById('tableLoadingOverlay');
        if (show) {
            overlay.classList.remove('hidden');
        } else {
            overlay.classList.add('hidden');
        }
    }

    function showChartLoading(show = true) {
        const statusLoading = document.getElementById('statusChartLoading');
        const allStationsLoading = document.getElementById('allStationsChartLoading');
        
        if (show) {
            statusLoading?.classList.remove('hidden');
            allStationsLoading?.classList.remove('hidden');
        } else {
            statusLoading?.classList.add('hidden');
            allStationsLoading?.classList.add('hidden');
        }
    }

    function showAllLoading(show = true) {
        showControlLoading(show);
        showTableLoading(show);
        showChartLoading(show);
        
        // Update last update time
        const lastUpdateElement = document.getElementById('lastUpdateTime');
        if (show) {
            lastUpdateElement.textContent = 'Loading data...';
        }
    }

    // Filter stations based on current selection
    function filterStationsByCurrentSelection(stations) {
        if (!stations || stations.length === 0) return [];
        
        const selectedCenter = document.getElementById('centerSelect').value;
        const selectedRegion = document.getElementById('regionSelect').value;
        const selectedVariable = document.getElementById('variableSelect').value;
        
        let filteredStations = [...stations]; // Create a copy
        
        // Filter by region if specific region is selected
        if (selectedRegion && selectedRegion !== 'SGP,IDN,BRN,PHL,TLS,PNG,MYS' && selectedRegion !== 'ALL_COMBINED') {
            filteredStations = filteredStations.filter(station => {
                return station.territoryCode === selectedRegion || 
                       station.countryCode === selectedRegion ||
                       station.territory === selectedRegion ||
                       station.country === selectedRegion;
            });
        }

        // Filter by Selected Stations (Multi-select)
        if (selectedStationIds.size > 0 && !selectedStationIds.has('ALL')) {
            filteredStations = filteredStations.filter(station => {
                const id = station.wigosId || station.name;
                return selectedStationIds.has(id);
            });
        }
        
        return filteredStations;
    }
    
    // Apply all filters and update display
    function applyFiltersAndUpdate() {
        if (currentStations.length === 0) return;
        
        showTableLoading(true);
        showChartLoading(true);
        
        setTimeout(() => {
            const filteredData = filterStationsByCurrentSelection(currentStations);
            
            // Update table header based on current filters
            updateTableHeader();
            
            // Update all displays with filtered data
            populateStationTable(filteredData);
            updateCharts(filteredData);
            updateStatistics(filteredData);
            
            // Update station count display
            const stationCountElement = document.getElementById('stationCount');
            stationCountElement.textContent = `Showing: ${filteredData.length} of ${currentStations.length} stations`;
            
            showTableLoading(false);
            showChartLoading(false);
        }, 250);
    }

    // --- Station Selector Functions ---

    function populateStationSelector(stations) {
        const container = document.getElementById('stationListContainer');
        container.innerHTML = '';
        
        // Get current station IDs from new data
        const newStationIds = new Set(stations.map(s => s.wigosId || s.name));
        
        // Check if we should preserve selection (only reset if station list changed significantly)
        const shouldPreserve = selectedStationIds.size > 0 && 
                               !selectedStationIds.has('ALL') &&
                               Array.from(selectedStationIds).some(id => id !== 'ALL' && newStationIds.has(id));
        
        if (!shouldPreserve) {
            // First load or stations changed - select all
            selectedStationIds.clear();
            selectedStationIds.add('ALL');
        }
        
        updateStationDropdownLabel();
        
        // Sort stations by name
        const sortedStations = [...stations].sort((a, b) => (a.name || '').localeCompare(b.name || ''));

        // Add checkboxes
        sortedStations.forEach(station => {
            const name = station.name || 'Unknown';
            const id = station.wigosId || name;
            
            const div = document.createElement('div');
            div.className = 'flex items-center p-2 hover:bg-gray-50';
            
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.className = 'station-checkbox rounded text-bmkg-blue focus:ring-bmkg-blue h-3 w-3 mr-2';
            checkbox.value = id;
            // Set checked based on current selection
            checkbox.checked = selectedStationIds.has('ALL') || selectedStationIds.has(id);
            
            const label = document.createElement('label');
            label.className = 'text-xs text-gray-700 cursor-pointer flex-1 truncate';
            label.textContent = `${name} (${id})`;
            label.title = `${name} (${id})`;
            
            // Event listener
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    selectedStationIds.add(this.value);
                } else {
                    selectedStationIds.delete(this.value);
                    selectedStationIds.delete('ALL');
                    document.getElementById('selectAllStations').checked = false;
                }
                
                // Check if all are checked
                const customCheckboxes = container.querySelectorAll('.station-checkbox');
                let allChecked = true;
                for (let cb of customCheckboxes) {
                    if (!cb.checked && cb.parentElement.style.display !== 'none') {
                        allChecked = false;
                        break;
                    }
                }
                
                if (allChecked) {
                    selectedStationIds.add('ALL');
                    document.getElementById('selectAllStations').checked = true;
                }
                
                updateStationDropdownLabel();
                applyFiltersAndUpdate();
            });
            
            label.addEventListener('click', () => checkbox.click());
            
            div.appendChild(checkbox);
            div.appendChild(label);
            container.appendChild(div);
        });

        // Search functionality
        const searchInput = document.getElementById('stationSearchInput');
        // Remove old listener if exists (by cloning node? No, usually fine to just add new one if init runs once. But init runs once.)
        // Actually, populate is called multiple times. I should probably attach listener in InitControlPanel.
        // But for now, I'll check if listener attached or just clone.
        const newSearch = searchInput.cloneNode(true);
        searchInput.parentNode.replaceChild(newSearch, searchInput);
        
        newSearch.addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            const items = container.querySelectorAll('div');
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(term) ? 'flex' : 'none';
            });
        });

        // Select All functionality
        const selectAllCb = document.getElementById('selectAllStations');
        const newSelectAll = selectAllCb.cloneNode(true);
        selectAllCb.parentNode.replaceChild(newSelectAll, selectAllCb);
        
        // Set Select All checkbox based on current selection
        newSelectAll.checked = selectedStationIds.has('ALL');
        
        newSelectAll.addEventListener('change', function() {
            const checkboxes = container.querySelectorAll('.station-checkbox');
            const isChecked = this.checked;
            
            checkboxes.forEach(cb => {
                if (cb.parentElement.style.display !== 'none') {
                    cb.checked = isChecked;
                    if (isChecked) selectedStationIds.add(cb.value);
                    else selectedStationIds.delete(cb.value);
                }
            });
            
            if (isChecked) selectedStationIds.add('ALL');
            else if (selectedStationIds.size === 0) selectedStationIds.clear(); // just to be safe
            
            updateStationDropdownLabel();
            applyFiltersAndUpdate();
        });
    }

    function updateStationDropdownLabel() {
        const label = document.getElementById('stationDropdownLabel');
        const count = selectedStationIds.has('ALL') ? currentStations.length : selectedStationIds.size;
        
        if (selectedStationIds.has('ALL')) {
            label.textContent = 'All Stations';
        } else if (count === 0) {
            label.textContent = 'No Stations Selected';
        } else if (count === 1) {
             const iterator = selectedStationIds.values();
             label.textContent = iterator.next().value;
        } else {
            label.textContent = `${count} Stations Selected`;
        }
    }

    // Toggle Dropdown (only add listener once)
    const btn = document.getElementById('stationDropdownBtn');
    if (!btn.hasAttribute('data-listener-attached')) {
        btn.setAttribute('data-listener-attached', 'true');
        document.addEventListener('click', function(e) {
            const container = document.getElementById('station-select-container');
            const menu = document.getElementById('stationDropdownMenu');
            
            if (document.getElementById('stationDropdownBtn').contains(e.target)) {
                menu.classList.toggle('hidden');
            } else if (!container.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });
    }

    // Update table header based on current filters
    function updateTableHeader() {
        const selectedCenter = document.getElementById('centerSelect').value;
        const selectedRegion = document.getElementById('regionSelect').value;
        
        const tableHead = document.querySelector('table thead tr');
        
        // Base headers that always appear
        let headers = [
            '<th class="px-3 lg:px-4 py-3 text-left font-semibold text-sm">Station Name</th>',
            '<th class="px-3 lg:px-4 py-3 text-center font-semibold text-sm hidden sm:table-cell">WIGOS ID</th>'
        ];
        
        // Add Country/Region header conditionally
        if (selectedRegion && selectedRegion !== 'SGP,IDN,BRN,PHL,TLS,PNG,MYS') {
            // If specific region selected, show region name
            const regionNames = {
                'IDN': 'Indonesia',
                'SGP': 'Singapore', 
                'MYS': 'Malaysia',
                'PHL': 'Philippines',
                'TLS': 'East Timor',
                'PNG': 'Papua New Guinea',
                'BRN': 'Brunei',
                'USA_PACIFIC': 'USA Pacific'
            };
            const regionName = regionNames[selectedRegion] || selectedRegion;
            headers.push(`<th class="px-3 lg:px-4 py-3 text-center font-semibold text-sm">${regionName}</th>`);
        } else {
            // Show generic "Country" header
            headers.push('<th class="px-3 lg:px-4 py-3 text-center font-semibold text-sm">Country</th>');
        }
        
        // Add Coordinates header
        headers.push('<th class="px-3 lg:px-4 py-3 text-center font-semibold text-sm hidden md:table-cell">Coordinates</th>');
        
        // Add Station Type header
        headers.push('<th class="px-3 lg:px-4 py-3 text-center font-semibold text-sm hidden lg:table-cell">Station Type</th>');
        
        // Add In OSCAR header
        headers.push('<th class="px-3 lg:px-4 py-3 text-center font-semibold text-sm hidden lg:table-cell">In OSCAR</th>');
        
        // Add Center headers based on selection
        if (selectedCenter === 'ALL') {
            // Show all centers
            CENTERS.forEach(center => {
                headers.push(`<th class="px-3 lg:px-4 py-3 text-center font-semibold text-sm">${center}</th>`);
            });
        } else {
            // Show only selected center
            headers.push(`<th class="px-3 lg:px-4 py-3 text-center font-semibold text-sm">${selectedCenter}</th>`);
        }
        
        // Add Overall header
        headers.push('<th class="px-3 lg:px-4 py-3 text-center font-semibold text-sm">Overall</th>');
        
        // Update the table header
        tableHead.innerHTML = headers.join('');
    }

    
    // Fetch data from GBON API
    async function fetchGBONData(territory, date, variable = 'pressure', period = 'six_hour', time_period = '00') {
        try {
            const url = `${API_ENDPOINT}reports_land_surface.php?territory=${territory}&date=${date}&period=${period}&time_period=${time_period}&variable=${variable}`;
            
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const data = await response.json();
            if (data.error) {
                throw new Error(data.message || 'API Error');
            }
            
            return data;
        } catch (error) {
            console.error('Error fetching GBON data:', error);
            throw error;
        }
    }
    
    // Calculate station status based on center data
    function calculateStationStatus(station, center = null) {
        if (center && center !== 'ALL') {
            // Calculate for specific center
            if (station.centers && station.centers[center]) {
                const centerData = station.centers[center];
                const received = centerData.received || 0;
                const expected = centerData.expected || 6;
                const percentage = expected > 0 ? (received / expected) * 100 : 0;
                
                if (percentage >= 80) return 'complete';
                if (percentage >= 30) return 'issues';
                if (percentage > 0) return 'critical';
                return 'not_received';
            } else if (station[center] !== undefined) {
                const received = station[center];
                const expected = station.expected || 6;
                const percentage = expected > 0 ? (received / expected) * 100 : 0;
                
                if (percentage >= 80) return 'complete';
                if (percentage >= 30) return 'issues';
                if (percentage > 0) return 'critical';
                return 'not_received';
            }
            return 'not_received';
        } else {
            // Calculate overall status (existing logic)
            if (station.dataCompleteness !== undefined) {
                const availability = station.dataCompleteness;
                if (availability >= 80) return 'complete';
                if (availability >= 30) return 'issues';
                if (availability > 0) return 'critical';
                return 'not_received';
            }
            
            // Fallback to station status code
            switch (station.stationStatusCode) {
                case 'operational': return 'complete';
                case 'issues': return 'issues';
                case 'critical': return 'critical';
                default: return 'not_received';
            }
        }
    }
    
    // Calculate center statistics
    function calculateCenterStatistics(stations, selectedCenter = 'ALL') {
        const stats = {};
        
        CENTERS.forEach(center => {
            if (selectedCenter !== 'ALL' && selectedCenter !== center) return;
            
            stats[center] = {
                complete: 0,
                issues: 0,
                critical: 0,
                not_received: 0,
                total: 0
            };
            
            stations.forEach(station => {
                if (station.centers && station.centers[center]) {
                    stats[center].total++;
                    const status = calculateStationStatus(station, center);
                    stats[center][status]++;
                } else if (station[center] !== undefined) {
                    stats[center].total++;
                    const status = calculateStationStatus(station, center);
                    stats[center][status]++;
                }
            });
        });
        
        return stats;
    }
    
    // Initialize charts
    function initializeCharts() {
        // Status Distribution Chart (Pie by default)
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        statusChart = new Chart(statusCtx, {
            type: 'pie',
            data: {
                labels: ['Complete', 'Issues', 'Critical', 'Not Received'],
                datasets: [{
                    data: [0, 0, 0, 0],
                    backgroundColor: ['#10B981', '#F59E0B', '#EF4444', '#6B7280'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right', // Legend on right for Pie
                        labels: {
                            usePointStyle: true,
                            font: { size: 11 }
                        }
                    }
                }
            }
        });

        // Station Availability Chart (Bar)
        // Renamed from coverageChart to allStationsChart to match ID
        const allStationsCtx = document.getElementById('allStationsChart');
        if (!allStationsCtx) {
            console.error('Canvas element allStationsChart not found!');
            return;
        }
        
        allStationsChart = new Chart(allStationsCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: [], // Will be dates: 1, 2, 3... or Week 1, Week 2... or Jan, Feb...
                datasets: [{
                    label: 'Data Received',
                    data: [],
                    backgroundColor: '#3B82F6', // Blue
                    borderColor: '#2563EB',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Data Received: ${context.parsed.y}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Date',
                            font: { size: 12 }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Data Count',
                            font: { size: 12 }
                        }
                    }
                }
            }
        });
    }
    
    // Fetch time-series data from API
    async function fetchTimeSeriesData(view = 'daily') {
        const territory = document.getElementById('regionSelect').value;
        const dateValue = document.getElementById('dateSelect').value;
        const variable = document.getElementById('variableSelect').value;
        
        // Parse date value (YYYY-MM format for monthly selector)
        let year, month;
        if (dateValue.includes('-')) {
            const parts = dateValue.split('-');
            year = parseInt(parts[0]);
            month = parseInt(parts[1]);
        } else {
            year = new Date().getFullYear();
            month = new Date().getMonth() + 1;
        }
        
        // Get selected stations for filtering (wigosIds)
        let stationsParam = '';
        const selectedIds = Array.from(selectedStationIds).filter(id => id !== 'ALL');
        console.log('Selected stations for chart:', selectedIds.length, selectedIds);
        
        if (selectedIds.length > 0 && selectedIds.length < currentStations.length) {
            stationsParam = `&stations=${encodeURIComponent(selectedIds.join(','))}`;
        }
        
        // Get day range for daily view
        let daysParam = '';
        if (view === 'daily') {
            const dayRangeSelect = document.getElementById('dayRangeSelect');
            const days = dayRangeSelect ? dayRangeSelect.value : '7';
            daysParam = `&days=${days}`;
        }
        
        const url = `${API_ENDPOINT}reports_land_surface_timeseries.php?territory=${territory}&year=${year}&month=${month}&variable=${variable}&view=${view}${stationsParam}${daysParam}`;
        
        console.log('Fetching time-series data:', url);
        
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        return await response.json();
    }
    
    // Pagination functions
    function updatePagination() {
        totalEntries = filteredStations.length;
        const totalPages = Math.ceil(totalEntries / entriesPerPage);
        const startEntry = (currentPage - 1) * entriesPerPage + 1;
        const endEntry = Math.min(currentPage * entriesPerPage, totalEntries);
        
        // Update info text
        document.getElementById('paginationInfo').textContent = 
            `Showing ${startEntry} to ${endEntry} of ${totalEntries} entries`;
        
        // Update navigation buttons
        const prevBtn = document.getElementById('prevPage');
        const nextBtn = document.getElementById('nextPage');
        
        prevBtn.disabled = currentPage === 1;
        nextBtn.disabled = currentPage === totalPages || totalPages === 0;
        
        // Generate page numbers
        const pageNumbers = document.getElementById('pageNumbers');
        pageNumbers.innerHTML = '';
        
        if (totalPages > 0) {
            const maxVisiblePages = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
            
            if (endPage - startPage + 1 < maxVisiblePages) {
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }
            
            // Add first page and ellipsis if needed
            if (startPage > 1) {
                addPageButton(1, false);
                if (startPage > 2) {
                    const ellipsis = document.createElement('span');
                    ellipsis.className = 'px-2 py-1 text-gray-500';
                    ellipsis.textContent = '...';
                    pageNumbers.appendChild(ellipsis);
                }
            }
            
            // Add visible page numbers
            for (let i = startPage; i <= endPage; i++) {
                addPageButton(i, i === currentPage);
            }
            
            // Add last page and ellipsis if needed
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    const ellipsis = document.createElement('span');
                    ellipsis.className = 'px-2 py-1 text-gray-500';
                    ellipsis.textContent = '...';
                    pageNumbers.appendChild(ellipsis);
                }
                addPageButton(totalPages, false);
            }
        }
    }
    
    function addPageButton(pageNum, isActive) {
        const button = document.createElement('button');
        button.className = isActive 
            ? 'px-3 py-1 text-sm bg-bmkg-blue text-white border border-bmkg-blue rounded'
            : 'px-3 py-1 text-sm border border-gray-300 rounded hover:bg-gray-50';
        button.textContent = pageNum;
        button.addEventListener('click', () => goToPage(pageNum));
        document.getElementById('pageNumbers').appendChild(button);
    }
    
    function goToPage(page) {
        currentPage = page;
        renderCurrentPage();
        updatePagination();
    }
    
    function renderCurrentPage() {
        const startIndex = (currentPage - 1) * entriesPerPage;
        const endIndex = Math.min(startIndex + entriesPerPage, filteredStations.length);
        const pageStations = filteredStations.slice(startIndex, endIndex);
        
        const tableBody = document.getElementById('station-table-body');
        tableBody.innerHTML = '';
        
        if (pageStations.length === 0) {
            const selectedCenter = document.getElementById('centerSelect').value;
            const colCount = selectedCenter === 'ALL' ? 11 : 8; // 6 base columns + centers + overall
            
            tableBody.innerHTML = `
                <tr>
                    <td colspan="${colCount}" class="px-4 py-8 text-center text-gray-500">
                        <div>No stations found</div>
                    </td>
                </tr>
            `;
            return;
        }
        
        pageStations.forEach(station => {
            tableBody.appendChild(createStationRow(station));
        });
    }
    
    // Update charts with current data
    function updateCharts(stations) {
        showChartLoading(true);
        console.log('DEBUG: updateCharts called'); 
        
        // Add small delay for visual feedback
        setTimeout(async () => {
            try {
                console.log('Updating charts for', stations.length, 'stations');
                
                // 1. Update Status Distribution Chart
                if (statusChart) {
                    let complete = 0, issues = 0, critical = 0, notReceived = 0;
                    
                    stations.forEach(station => {
                        // Use updated logic for station status (no center arg = overall/monthly)
                        let status = 'not_received';
                        
                        if (station.dataCompleteness !== undefined) {
                            const availability = station.dataCompleteness;
                            if (availability >= 80) status = 'complete';
                            else if (availability >= 30) status = 'issues';
                            else if (availability > 0) status = 'critical';
                            else status = 'not_received';
                        } else if (station.stationStatusCode) {
                            // Fallback
                            switch(station.stationStatusCode) {
                                case 'operational': status = 'complete'; break;
                                case 'issues': status = 'issues'; break;
                                case 'critical': status = 'critical'; break;
                                 default: status = 'not_received';
                            }
                        }
                        
                        if (status === 'complete') complete++;
                        else if (status === 'issues') issues++;
                        else if (status === 'critical') critical++;
                        else notReceived++;
                    });
                    
                    // Update based on chart type (Simple data update)
                    const data = [complete, issues, critical, notReceived];
                    
                    // Check Chart Type
                    const chartTypeEl = document.getElementById('chartTypeSelect');
                    const chartType = chartTypeEl ? chartTypeEl.value : 'pie';
                    
                    if (statusChart.config.type !== chartType) {
                        // Destroy and recreate if type changed
                        statusChart.destroy();
                        const statusCtx = document.getElementById('statusChart').getContext('2d');
                        statusChart = new Chart(statusCtx, {
                            type: chartType,
                            data: {
                                labels: ['Complete', 'Issues', 'Critical', 'Not Received'],
                                datasets: [{
                                    label: 'Stations',
                                    data: data,
                                    backgroundColor: ['#10B981', '#F59E0B', '#EF4444', '#6B7280'],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: chartType === 'bar' ? 'top' : 'right',
                                        display: chartType !== 'bar'
                                    }
                                }
                            }
                        });
                    } else {
                        statusChart.data.datasets[0].data = data;
                        statusChart.update();
                    }
                } else {
                    console.warn('DEBUG: statusChart not initialized');
                }
                
                // 2. Update Time-Series Chart (Bar)
                if (allStationsChart) {
                    const periodSelect = document.getElementById('chartPeriodSelect');
                    const selectedPeriod = periodSelect ? periodSelect.value : 'daily';
                    
                    // Update X-axis label based on period
                    const xAxisLabels = {
                        'daily': 'Tanggal',
                        'weekly': 'Minggu',
                        'monthly': 'Bulan',
                        'yearly': 'Tahun'
                    };
                    allStationsChart.options.scales.x.title.text = xAxisLabels[selectedPeriod] || 'Date';
                    
                    // Fetch time-series data
                    try {
                        const tsData = await fetchTimeSeriesData(selectedPeriod);
                        
                        if (tsData.success && tsData.labels && tsData.stations) {
                            // Generate colors for each station
                            const colors = [
                                '#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6',
                                '#EC4899', '#06B6D4', '#84CC16', '#F97316', '#6366F1'
                            ];
                            
                            // Create datasets for each station
                            const datasets = tsData.stations.map((station, idx) => ({
                                label: station.name,
                                data: station.data,
                                backgroundColor: colors[idx % colors.length],
                                borderColor: colors[idx % colors.length],
                                borderWidth: 1
                            }));
                            
                            allStationsChart.data.labels = tsData.labels;
                            allStationsChart.data.datasets = datasets;
                            allStationsChart.update();
                            console.log('Time-series chart updated:', tsData.stations.length, 'stations');
                        } else {
                            console.warn('Time-series data not available:', tsData);
                            allStationsChart.data.labels = [];
                            allStationsChart.data.datasets = [{ label: 'No Data', data: [], backgroundColor: '#ccc' }];
                            allStationsChart.update();
                        }
                    } catch (tsError) {
                        console.error('Error fetching time-series data:', tsError);
                        allStationsChart.data.labels = ['Error'];
                        allStationsChart.data.datasets = [{ label: 'Error', data: [0], backgroundColor: '#EF4444' }];
                        allStationsChart.update();
                    }
                } else {
                    console.warn('DEBUG: allStationsChart not initialized');
                }
            } catch (err) {
                console.error('DEBUG: Critical error in updateCharts:', err);
            } finally {
                showChartLoading(false);
            }
        }, 200);
    }
    
    // Populate station table with detailed information (with pagination)
    function populateStationTable(stations) {
        console.log('Populating table with', stations ? stations.length : 0, 'stations');
        
        const errorMessageElement = document.getElementById('errorMessage');
        const timestampElement = document.getElementById('dataTimestamp');
        
        errorMessageElement.classList.add('hidden');
        
        if (!stations || stations.length === 0) {
            const tableBody = document.getElementById('station-table-body');
            
            // Check if this is due to filtering
            const selectedCenter = document.getElementById('centerSelect').value;
            const selectedRegion = document.getElementById('regionSelect').value;
            const isFiltered = selectedCenter !== 'ALL' || (selectedRegion && selectedRegion !== 'SGP,IDN,BRN,PHL,TLS,PNG,MYS');
            
            let emptyMessage = 'No station data available';
            let emptyDetail = 'Try selecting different parameters or check back later.';
            
            if (isFiltered && currentStations.length > 0) {
                emptyMessage = 'No stations match current filters';
                emptyDetail = 'Try changing the selected Center or Region to see more data.';
            }
            
            const colCount = selectedCenter === 'ALL' ? 11 : 8; // Dynamic column count
            
            tableBody.innerHTML = `
                <tr>
                    <td colspan="${colCount}" class="px-4 py-12 text-center text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8h.01M6 21h.01"></path>
                        </svg>
                        <div>
                            <h3 class="text-lg font-medium text-gray-600 mb-2">${emptyMessage}</h3>
                            <p class="text-sm">${emptyDetail}</p>
                        </div>
                    </td>
                </tr>
            `;
            
            timestampElement.textContent = new Date().toLocaleString();
            
            // Reset pagination
            filteredStations = [];
            currentPage = 1;
            updatePagination();
            return;
        }
        
        // Store filtered stations for pagination
        filteredStations = stations;
        currentPage = 1; // Reset to first page
        
        // Update timestamp with current selection info
        const selectedDate = document.getElementById('dateSelect').value;
        const selectedPeriod = document.getElementById('periodTypeSelect').value;
        const selectedCenter = document.getElementById('centerSelect').value;
        const selectedRegion = document.getElementById('regionSelect').value;
        const selectedVariable = document.getElementById('variableSelect').value;
        
        let timeInfo = '';
        let dateDisplay = selectedDate;
        
        if (selectedPeriod === 'six_hour') {
            const activeTimeBtn = document.querySelector('.time-period-btn.active');
            const timeValue = activeTimeBtn ? activeTimeBtn.dataset.period : '00';
            timeInfo = ` ${timeValue}:00 UTC`;
        } else if (selectedPeriod === 'monthly') {
            // Format month display (YYYY-MM to Month YYYY)
            if (selectedDate) {
                const [year, month] = selectedDate.split('-');
                const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                                  'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                dateDisplay = `${monthNames[parseInt(month) - 1]} ${year}`;
            }
        }
        
        let filterInfo = '';
        if (selectedCenter !== 'ALL') {
            filterInfo += ` | Center: ${selectedCenter}`;
        }
        if (selectedRegion && selectedRegion !== 'SGP,IDN,BRN,PHL,TLS,PNG,MYS') {
            filterInfo += ` | Region: ${selectedRegion}`;
        }
        if (selectedVariable) {
            filterInfo += ` | Variable: ${selectedVariable.charAt(0).toUpperCase() + selectedVariable.slice(1)}`;
        }
        
        timestampElement.textContent = `Data for: ${dateDisplay}${timeInfo}${filterInfo}`;
        
        // Render current page and update pagination
        renderCurrentPage();
        updatePagination();
    }
    
    // Create individual station row
    function createStationRow(station) {
        const row = document.createElement('tr');
        row.className = 'hover:bg-blue-50 transition-colors duration-200';
        
        // Calculate overall status
        const overallStatus = calculateStationStatus(station);
        const statusColors = {
            'complete': 'text-green-600 font-medium',
            'issues': 'text-yellow-600 font-medium', 
            'critical': 'text-red-600 font-medium',
            'not_received': 'text-gray-600 font-medium'
        };
        
        // Calculate center coverage percentages
        const centerCoverages = {};
        CENTERS.forEach(center => {
            if (station.centers && station.centers[center]) {
                const centerData = station.centers[center];
                const received = centerData.received || 0;
                const expected = centerData.expected || 6;
                const coverage = expected > 0 ? Math.round((received / expected) * 100) : 0;
                centerCoverages[center] = { coverage, received, expected };
            } else if (station[center] !== undefined) {
                const received = station[center];
                const expected = station.expected || 6;
                const coverage = expected > 0 ? Math.round((received / expected) * 100) : 0;
                centerCoverages[center] = { coverage, received, expected };
            } else {
                centerCoverages[center] = { coverage: 0, received: 0, expected: 6 };
            }
        });
        
        // Calculate overall coverage
        let totalReceived = 0, totalExpected = 0;
        Object.values(centerCoverages).forEach(data => {
            totalReceived += data.received;
            totalExpected += data.expected;
        });
        const overallCoverage = totalExpected > 0 ? Math.round((totalReceived / totalExpected) * 100) : 0;
        
        // Helper function to get coverage cell HTML
        function getCoverageCell(centerData) {
            const { coverage, received, expected } = centerData;
            let cellClass = 'text-gray-600';
            if (coverage >= 80) cellClass = 'text-green-600 font-medium';
            else if (coverage >= 30) cellClass = 'text-yellow-600 font-medium';
            else if (coverage > 0) cellClass = 'text-red-600 font-medium';
            
            return `<td class="px-3 lg:px-4 py-3 text-center text-sm ${cellClass}" title="${received}/${expected}">${coverage}%</td>`;
        }
        
        // Build row HTML dynamically based on current filters
        const selectedCenter = document.getElementById('centerSelect').value;
        const selectedRegion = document.getElementById('regionSelect').value;
        
        let rowHTML = '';
        
        // Station Name (always first)
        rowHTML += `
            <td class="px-3 lg:px-4 py-3 text-left text-sm">
                <div class="font-medium text-gray-900">${station.name || 'Unknown Station'}</div>
                <div class="text-xs text-gray-500">${station.wigosId || 'N/A'}</div>
            </td>`;
        
        // WIGOS ID (always second)
        rowHTML += `<td class="px-3 lg:px-4 py-3 text-center text-xs font-mono text-gray-600 hidden sm:table-cell">${station.wigosId || 'N/A'}</td>`;
        
        // Country/Region (third)
        if (selectedRegion && selectedRegion !== 'SGP,IDN,BRN,PHL,TLS,PNG,MYS') {
            // Show specific region data
            const regionData = station.countryCode || station.territory || 'N/A';
            rowHTML += `<td class="px-3 lg:px-4 py-3 text-center text-sm">${regionData}</td>`;
        } else {
            // Show generic country
            rowHTML += `<td class="px-3 lg:px-4 py-3 text-center text-sm">${station.countryCode || station.territory || 'N/A'}</td>`;
        }
        
        // Coordinates (fourth)
        rowHTML += `<td class="px-3 lg:px-4 py-3 text-center text-xs font-mono text-gray-600 hidden md:table-cell">
            ${station.latitude ? station.latitude.toFixed(4) : 'N/A'}, ${station.longitude ? station.longitude.toFixed(4) : 'N/A'}
        </td>`;
        
        // Station Type (fifth)
        rowHTML += `<td class="px-3 lg:px-4 py-3 text-center text-sm text-gray-700 hidden lg:table-cell">
            ${station.stationTypeName || 'N/A'}
        </td>`;
        
        // In OSCAR (sixth)
        const oscarStatus = station.inOSCAR;
        const oscarColor = oscarStatus === 'Yes' ? 'text-green-600' : oscarStatus === 'No' ? 'text-red-600' : 'text-gray-600';
        rowHTML += `<td class="px-3 lg:px-4 py-3 text-center text-sm ${oscarColor} hidden lg:table-cell">
            ${oscarStatus || 'N/A'}
        </td>`;
        
        // Center data columns
        if (selectedCenter === 'ALL') {
            // Show all centers
            CENTERS.forEach(center => {
                rowHTML += getCoverageCell(centerCoverages[center]);
            });
        } else {
            // Show only selected center
            rowHTML += getCoverageCell(centerCoverages[selectedCenter]);
        }
        
        // Overall (always last)
        rowHTML += `<td class="px-3 lg:px-4 py-3 text-center text-sm ${statusColors[overallStatus]}">${overallCoverage}%</td>`;
        
        row.innerHTML = rowHTML;
        
        return row;
    }
    
    
    // Initialize control panel
    function initializeControlPanel() {
        // Set default values
        const yesterday = new Date();
        yesterday.setDate(yesterday.getDate() - 2);
        const dateStr = yesterday.toISOString().split('T')[0];
        const dateInput = document.getElementById('dateSelect');
        
        // Set default based on period type
        const periodSelect = document.getElementById('periodTypeSelect');
        if (periodSelect.value === 'monthly') {
            dateInput.type = 'month';
            const lastMonth = new Date();
            lastMonth.setMonth(lastMonth.getMonth() - 1);
            dateInput.value = lastMonth.toISOString().slice(0, 7);
        } else {
            dateInput.type = 'date';
            dateInput.value = dateStr;
        }
        
        // Set default time period button
        document.querySelector('.time-period-btn[data-period="00"]').classList.add('active', 'bg-bmkg-blue', 'text-white');
        
        // Period type change handler
        document.getElementById('periodTypeSelect').addEventListener('change', function() {
            const timePeriodContainer = document.getElementById('timePeriodContainer');
            const dateInput = document.getElementById('dateSelect');
            
            if (this.value === 'six_hour') {
                timePeriodContainer.style.display = 'block';
                dateInput.type = 'date';
                // Set to 2 days ago for six-hour data
                const twoDaysAgo = new Date();
                twoDaysAgo.setDate(twoDaysAgo.getDate() - 2);
                dateInput.value = twoDaysAgo.toISOString().split('T')[0];
            } else if (this.value === 'daily') {
                timePeriodContainer.style.display = 'none';
                dateInput.type = 'date';
                // Set to yesterday for daily data
                const yesterday = new Date();
                yesterday.setDate(yesterday.getDate() - 1);
                dateInput.value = yesterday.toISOString().split('T')[0];
            } else if (this.value === 'monthly') {
                timePeriodContainer.style.display = 'none';
                dateInput.type = 'month';
                // Set to last month for monthly data
                const lastMonth = new Date();
                lastMonth.setMonth(lastMonth.getMonth() - 1);
                const yearMonth = lastMonth.toISOString().slice(0, 7);
                dateInput.value = yearMonth;
            }
            
            // Auto load data when period changes
            showControlLoading(true);
            setTimeout(() => {
                loadData();
            }, 100);
        });
        
        // Variable change handler - reload data from API
        document.getElementById('variableSelect').addEventListener('change', function() {
            console.log('Variable changed to:', this.value);
            showControlLoading(true);
            setTimeout(() => {
                loadData();
            }, 100);
        });
        
        // Region change handler - reload data from API
        document.getElementById('regionSelect').addEventListener('change', function() {
            console.log('Region changed to:', this.value);
            showControlLoading(true);
            setTimeout(() => {
                loadData();
            }, 100);
        });
        
        // Center change handler - filter existing data (no API reload needed)
        document.getElementById('centerSelect').addEventListener('change', function() {
            console.log('Center changed to:', this.value);
            if (currentStations.length > 0) {
                applyFiltersAndUpdate();
            } else {
                // If no data loaded yet, load it
                loadData();
            }
        });
        
        // Date change handler - reload data from API
        document.getElementById('dateSelect').addEventListener('change', function() {
            console.log('Date changed to:', this.value);
            showControlLoading(true);
            setTimeout(() => {
                loadData();
            }, 100);
        });
        
        // Time period button handlers
        document.querySelectorAll('.time-period-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                console.log('Time period changed to:', this.dataset.period);
                document.querySelectorAll('.time-period-btn').forEach(b => {
                    b.classList.remove('active', 'bg-bmkg-blue', 'text-white');
                });
                this.classList.add('active', 'bg-bmkg-blue', 'text-white');
                
                // Auto load data when time period changes
                showControlLoading(true);
                setTimeout(() => {
                    loadData();
                }, 100);
            });
        });

        // Chart Type Listener (Added)
        const chartTypeSelect = document.getElementById('chartTypeSelect');
        if (chartTypeSelect) {
            chartTypeSelect.addEventListener('change', () => {
                 // Re-update charts with current filtered data
                 const filteredStations = filterStationsByCurrentSelection(currentStations);
                 updateCharts(filteredStations);
            });
        }
        
        // Chart Period Selector Listener
        const chartPeriodSelect = document.getElementById('chartPeriodSelect');
        const dayRangeContainer = document.getElementById('dayRangeContainer');
        
        if (chartPeriodSelect) {
            // Toggle day range visibility based on period
            const toggleDayRange = () => {
                if (dayRangeContainer) {
                    dayRangeContainer.style.display = chartPeriodSelect.value === 'daily' ? 'flex' : 'none';
                }
            };
            toggleDayRange(); // Initial state
            
            chartPeriodSelect.addEventListener('change', () => {
                toggleDayRange();
                const filteredStations = filterStationsByCurrentSelection(currentStations);
                updateCharts(filteredStations);
            });
        }
        
        // Day Range Selector Listener
        const dayRangeSelect = document.getElementById('dayRangeSelect');
        if (dayRangeSelect) {
            dayRangeSelect.addEventListener('change', () => {
                const filteredStations = filterStationsByCurrentSelection(currentStations);
                updateCharts(filteredStations);
            });
        }
    }
    
    // Initialize pagination controls
    function initializePagination() {
        // Entries per page change handler
        document.getElementById('entriesPerPage').addEventListener('change', function() {
            entriesPerPage = parseInt(this.value);
            currentPage = 1; // Reset to first page
            renderCurrentPage();
            updatePagination();
        });
        
        // Previous page button
        document.getElementById('prevPage').addEventListener('click', function() {
            if (currentPage > 1) {
                goToPage(currentPage - 1);
            }
        });
        
        // Next page button
        document.getElementById('nextPage').addEventListener('click', function() {
            const totalPages = Math.ceil(totalEntries / entriesPerPage);
            if (currentPage < totalPages) {
                goToPage(currentPage + 1);
            }
        });
    }
    
    // Load data based on current form values
    async function loadData() {
        // Prevent multiple simultaneous requests
        if (isLoading) return;
        
        try {
            console.log('TRACE: Starting loadData');
            isLoading = true;
            showAllLoading(true);
            
            const territory = document.getElementById('regionSelect').value;
            const variable = document.getElementById('variableSelect').value;
            const date = document.getElementById('dateSelect').value;
            const period = document.getElementById('periodTypeSelect').value;
            
            let timePeriod = '00';
            if (period === 'six_hour') {
                const activeTimeBtn = document.querySelector('.time-period-btn.active');
                timePeriod = activeTimeBtn ? activeTimeBtn.dataset.period : '00';
            }
            
            if (!territory || !variable || !date) {
                throw new Error('Please fill in all required fields');
            }
            
            // Clear existing data first
            currentStations = [];
            document.getElementById('station-table-body').innerHTML = `
                <tr>
                    <td colspan="9" class="px-4 py-12 text-center text-gray-500">
                        <div class="animate-pulse">Loading station data...</div>
                    </td>
                </tr>
            `;
            
            console.log('TRACE: Fetching data...');
            const data = await fetchGBONData(territory, date, variable, period, timePeriod);
            console.log('TRACE: Data fetched');
            
            if (data.stations && data.stations.length > 0) {
                console.log('Data loaded successfully:', data.stations.length, 'stations');
                currentStations = data.stations;
                
                // Populate/Update Station Selector
                console.log('TRACE: Populating selector');
                populateStationSelector(currentStations);
                
                // Apply all filters to the loaded data
                console.log('TRACE: Filtering data');
                const filteredData = filterStationsByCurrentSelection(data.stations);
                console.log('Filtered data:', filteredData.length, 'stations after filtering');
                
                // Update table header first
                console.log('TRACE: Updating header');
                updateTableHeader();
                
                // Update all displays with filtered data
                console.log('TRACE: Populating table');
                populateStationTable(filteredData);
                
                console.log('TRACE: Updating statistics');
                try {
                    updateStatistics(filteredData);
                } catch(e) { console.error('Error in updateStatistics:', e); }
                
                console.log('TRACE: Updating charts');
                updateCharts(filteredData);
                
                // Update last update time
                document.getElementById('lastUpdateTime').textContent = 
                    `Last updated: ${new Date().toLocaleTimeString()}`;
                    
                // Update main station count
                const stationCountElement = document.getElementById('stationCount');
                if (filteredData.length !== data.stations.length) {
                    stationCountElement.textContent = `Showing: ${filteredData.length} of ${data.stations.length} stations`;
                } else {
                    stationCountElement.textContent = `Total: ${data.stations.length} stations`;
                }
            } else {
                throw new Error('No station data found for the selected parameters');
            }
            
        } catch (error) {
            console.error('Error loading data:', error);
            showErrorMessage(`Error: ${error.message}`);
            
            // Clear displays
            document.getElementById('station-table-body').innerHTML = `
                <tr>
                    <td colspan="9" class="px-4 py-12 text-center text-gray-500">
                        <div>
                            <p class="text-lg mb-2">Failed to load land surface data</p>
                            <p class="text-sm">${error.message}</p>
                        </div>
                    </td>
                </tr>
            `;
            
            // Reset statistics
            document.getElementById('totalStations').innerHTML = '<span class="text-gray-400">0</span>';
            document.getElementById('activeStations').innerHTML = '<span class="text-gray-400">0</span>';
            document.getElementById('dataAvailability').innerHTML = '<span class="text-gray-400">0.0%</span>';
            
        } finally {
            console.log('TRACE: loadData finally block');
            isLoading = false;
            showAllLoading(false);
        }
    }
    
    // Show error message
    function showErrorMessage(message) {
        const errorMessageElement = document.getElementById('errorMessage');
        const errorTextElement = document.getElementById('errorText');
        
        errorTextElement.textContent = message;
        errorMessageElement.classList.remove('hidden');
        
        // Auto-hide error after 10 seconds
        setTimeout(() => {
            errorMessageElement.classList.add('hidden');
        }, 10000);
    }
    
    // Update statistics cards
    function updateStatistics(stations) {
        if (!stations || stations.length === 0) {
            document.getElementById('totalStations').innerHTML = '<span class="text-gray-400">0</span>';
            document.getElementById('activeStations').innerHTML = '<span class="text-gray-400">0</span>';
            document.getElementById('dataAvailability').innerHTML = '<span class="text-gray-400">0.0%</span>';
            return;
        }
        
        // Total Stations
        const totalStations = stations.length;
        
        let operationalStations = 0;
        let totalAvailability = 0;
        let availabilityCount = 0;
        
        stations.forEach(station => {
            // Count Operational (Complete) Stations
            // Use no-arg call to get overall status
            const status = calculateStationStatus(station);
            if (status === 'complete') {
                operationalStations++;
            }
            
            // Calculate Availability
            if (station.dataCompleteness !== undefined) {
                totalAvailability += station.dataCompleteness;
                availabilityCount++;
            } else {
                // Fallback: Average of centers
                let sTotal = 0;
                let sCount = 0;
                
                if (station.centers) {
                    Object.values(station.centers).forEach(c => {
                        const exp = c.expected || 6;
                        if (exp > 0) {
                            sTotal += (c.received || 0) / exp; // Fraction
                            sCount++;
                        }
                    });
                } else {
                     // Check direct properties if flat structure
                     CENTERS.forEach(center => {
                         if (station[center] !== undefined) {
                             const exp = station.expected || 6;
                             if(exp > 0) {
                                 sTotal += station[center] / exp;
                                 sCount++;
                             }
                         }
                     });
                }
                
                if (sCount > 0) {
                    // Average fraction for this station, conv to %
                    totalAvailability += (sTotal / sCount) * 100;
                    availabilityCount++;
                }
            }
        });
        
        const averageAvailability = availabilityCount > 0 ? (totalAvailability / availabilityCount) : 0;
        
        // Update DOM elements
        document.getElementById('totalStations').textContent = totalStations;
        document.getElementById('activeStations').textContent = operationalStations;
        document.getElementById('dataAvailability').textContent = `${averageAvailability.toFixed(1)}%`;
    }
    
    // Add loading spinners to charts
    function addLoadingSpinners() {
        const chartContainers = document.querySelectorAll('.relative.h-64');
        chartContainers.forEach(container => {
            const spinner = document.createElement('div');
            spinner.className = 'loading-spinner absolute inset-0 flex items-center justify-center bg-gray-50 bg-opacity-75';
            spinner.innerHTML = `
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-bmkg-blue"></div>
            `;
            container.appendChild(spinner);
        });
    }
    
    // Initialize everything when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        initializeCharts();
        initializeControlPanel();
        initializePagination();
        
        // Show initial loading
        showAllLoading(true);
        
        // Auto-load data on page load
        setTimeout(() => {
            loadData();
        }, 500);
        
        // Auto-refresh every 5 minutes
        setInterval(() => {
            console.log('Auto-refreshing data...');
            loadData();
        }, 300000);
    });
</script>

<?php include '../../includes/footer.php'; ?>