<?php
// reports/gbon/upper-air.php
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
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">GBON - Upper Air</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl lg:text-4xl font-bold text-gray-800 mb-4">
            GBON Upper Air Monitoring Report
        </h1>
        <p class="text-lg text-gray-600 max-w-3xl mx-auto">
            Laporan monitoring untuk Global Basic Observing Network (GBON) - Upper Air stations
        </p>
    </div>

    <!-- Control Panel -->
    <div class="bg-white rounded-lg shadow-md p-4 lg:p-6 mb-6 relative">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-4 mb-4">
            <!-- Period Type -->
            <div class="flex flex-col">
                <label for="periodTypeSelect" class="text-sm font-medium text-gray-700 mb-1">Period Type</label>
                <select id="periodTypeSelect" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-bmkg-blue focus:border-transparent">
                    <option value="daily">Daily</option>
                    <option value="monthly">Monthly</option>
                </select>
            </div>

            <!-- Variable Selector -->
            <div class="flex flex-col">
                <label for="variableSelect" class="text-sm font-medium text-gray-700 mb-1">Variable</label>
                <select id="variableSelect" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-bmkg-blue focus:border-transparent">
                    <option value="temperature">Temperature</option>
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
                <input type="date" id="dateSelect" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-bmkg-blue focus:border-transparent">
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
                <div class="text-sm text-gray-600">Loading upper air data...</div>
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
                    <p class="text-sm font-medium text-gray-600">Operational Stations</p>
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

    <!-- Analytics Charts Section - All Stations -->
    <div class="bg-white rounded-lg shadow-md p-4 lg:p-6 mb-6">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
            <h2 class="text-xl font-semibold text-gray-800">Station Data Availability Overview</h2>
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
        
        <!-- Charts Container - All Stations -->
        <div class="flex flex-col gap-6 mb-8">
            <!-- Station Coverage Bar Chart (All Stations) -->
            <div class="bg-gray-50 rounded-lg p-4 lg:p-6 relative">
                <div class="text-center text-lg font-semibold text-gray-700 mb-4">All Station Data Coverage (%)</div>
                <div class="relative" style="height: 400px; min-height: 300px;">
                    <canvas id="allStationsChart" class="w-full h-full"></canvas>
                    <div id="allStationsChartLoading" class="hidden absolute inset-0 bg-gray-50 bg-opacity-90 flex items-center justify-center">
                        <div class="text-center">
                            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-bmkg-blue mx-auto mb-2"></div>
                            <div class="text-xs text-gray-600">Loading chart...</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Status Distribution Pie Chart -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-gray-50 rounded-lg p-4 lg:p-6 relative">
                    <div class="text-center text-lg font-semibold text-gray-700 mb-4">Status Distribution</div>
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
                <div class="bg-gray-50 rounded-lg p-4 lg:p-6 relative">
                    <div class="text-center text-lg font-semibold text-gray-700 mb-4">Coverage by Center</div>
                    <div class="relative h-64 lg:h-80">
                        <canvas id="coverageChart" class="w-full h-full"></canvas>
                        <div id="coverageChartLoading" class="hidden absolute inset-0 bg-gray-50 bg-opacity-90 flex items-center justify-center">
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
                    <tr class="bg-bmkg-blue text-white" id="tableHeaderRow">
                        <th class="px-3 lg:px-4 py-3 text-left font-semibold text-sm">Station Name</th>
                        <th class="px-3 lg:px-4 py-3 text-center font-semibold text-sm hidden sm:table-cell">WIGOS ID</th>
                        <th class="px-3 lg:px-4 py-3 text-center font-semibold text-sm">Country</th>
                        <th class="px-3 lg:px-4 py-3 text-center font-semibold text-sm hidden md:table-cell">Coordinates</th>
                        <th class="px-3 lg:px-4 py-3 text-center font-semibold text-sm hidden lg:table-cell">Station Type</th>
                        <th class="px-3 lg:px-4 py-3 text-center font-semibold text-sm hidden lg:table-cell">In OSCAR</th>
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

<script>
    // Configuration Constants
    const API_ENDPOINT = '../../api/';
    const VARIABLES = ['temperature'];
    const PERIODS = ['daily', 'monthly'];
    const CENTERS = ['DWD', 'ECMWF', 'JMA', 'NCEP'];
    
    // Global variables
    let currentStations = [];
    let statusChart = null;
    let coverageChart = null;
    let allStationsChart = null;
    let isLoading = false;
    
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
        const coverageLoading = document.getElementById('coverageChartLoading');
        
        if (show) {
            statusLoading?.classList.remove('hidden');
            coverageLoading?.classList.remove('hidden');
        } else {
            statusLoading?.classList.add('hidden');
            coverageLoading?.classList.add('hidden');
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
        
        // Filter by variable (upper air typically only has temperature)
        if (selectedVariable && selectedVariable !== 'all' && selectedVariable !== 'temperature') {
            filteredStations = filteredStations.filter(station => {
                return station.variable === selectedVariable || 
                       station.variableType === selectedVariable ||
                       station.parameterName === selectedVariable;
            });
        }
        
        // Filter by center if specific center is selected
        if (selectedCenter && selectedCenter !== 'ALL') {
            filteredStations = filteredStations.filter(station => {
                // Check if station has data for the selected center
                if (station.centers && station.centers[selectedCenter]) {
                    return true;
                } else if (station[selectedCenter] !== undefined) {
                    return true;
                }
                return false;
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

    
    // Fetch data from Upper Air API
    async function fetchUpperAirData(territory, date, variable = 'temperature', period = 'daily') {
        try {
            const url = `${API_ENDPOINT}reports_upper_air.php?territory=${territory}&date=${date}&period=${period}&variable=${variable}`;
            
            // Add timeout to prevent hanging
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 30000); // 30 second timeout
            
            const response = await fetch(url, { signal: controller.signal });
            clearTimeout(timeoutId);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const data = await response.json();
            
            if (data.error) {
                throw new Error(data.message || 'API Error');
            }
            
            return data;
        } catch (error) {
            if (error.name === 'AbortError') {
                console.error('Request timeout - API took too long to respond');
                throw new Error('Request timeout. The API is not responding. Please try again later.');
            }
            console.error('Error fetching Upper Air data:', error);
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
        // All Stations Bar Chart (shows all stations with their coverage)
        const allStationsCtx = document.getElementById('allStationsChart')?.getContext('2d');
        if (allStationsCtx) {
            allStationsChart = new Chart(allStationsCtx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Data Coverage (%)',
                        data: [],
                        backgroundColor: [],
                        borderColor: [],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `Coverage: ${context.raw}%`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            max: 100,
                            title: { display: true, text: 'Data Coverage (%)', font: { size: 12 } },
                            ticks: { callback: function(value) { return value + '%'; } }
                        },
                        y: {
                            title: { display: true, text: 'Stations', font: { size: 12 } },
                            ticks: { font: { size: 10 }, autoSkip: false }
                        }
                    }
                }
            });
        }

        // Status Distribution Chart (Pie/Doughnut)
        const statusCtx = document.getElementById('statusChart')?.getContext('2d');
        if (statusCtx) {
            statusChart = new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Complete (≥ 80%)', 'Issues (≥ 30%)', 'Critical (< 30%)', 'Not Received'],
                    datasets: [{
                        data: [0, 0, 0, 0],
                        backgroundColor: ['#10B981', '#F59E0B', '#EF4444', '#6B7280'],
                        borderColor: ['#059669', '#D97706', '#DC2626', '#4B5563'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { usePointStyle: true, padding: 15, font: { size: 11 } }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((context.raw / total) * 100).toFixed(1) : 0;
                                    return `${context.label}: ${context.raw} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Coverage by Center Chart (Bar Chart)
        const coverageCtx = document.getElementById('coverageChart')?.getContext('2d');
        if (coverageCtx) {
            coverageChart = new Chart(coverageCtx, {
                type: 'bar',
                data: {
                    labels: ['DWD', 'ECMWF', 'JMA', 'NCEP'],
                    datasets: [{
                        label: 'Average Coverage (%)',
                        data: [0, 0, 0, 0],
                        backgroundColor: ['#3B82F6', '#8B5CF6', '#EC4899', '#14B8A6'],
                        borderColor: ['#2563EB', '#7C3AED', '#DB2777', '#0D9488'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) { return `Average: ${context.raw}%`; }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            title: { display: true, text: 'Average Coverage (%)', font: { size: 12 } },
                            ticks: { callback: function(value) { return value + '%'; } }
                        },
                        x: {
                            title: { display: true, text: 'Centers', font: { size: 12 } }
                        }
                    }
                }
            });
        }
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
        
        // Show loading for all stations chart
        const allStationsLoading = document.getElementById('allStationsChartLoading');
        if (allStationsLoading) allStationsLoading.classList.remove('hidden');
        
        setTimeout(() => {
            const selectedCenter = document.getElementById('centerSelect').value;
            
            // 1. Update All Stations Chart (horizontal bar chart showing all stations)
            if (allStationsChart && stations && stations.length > 0) {
                const stationLabels = [];
                const stationCoverages = [];
                const backgroundColors = [];
                const borderColors = [];
                
                stations.forEach(station => {
                    const name = station.name || station.wigosId || 'Unknown';
                    stationLabels.push(name.length > 25 ? name.substring(0, 22) + '...' : name);
                    
                    let totalReceived = 0;
                    let totalExpected = 0;
                    
                    if (selectedCenter === 'ALL') {
                        CENTERS.forEach(center => {
                            if (station.centers && station.centers[center]) {
                                totalReceived += station.centers[center].received || 0;
                                totalExpected += station.centers[center].expected || 6;
                            }
                        });
                    } else {
                        if (station.centers && station.centers[selectedCenter]) {
                            totalReceived = station.centers[selectedCenter].received || 0;
                            totalExpected = station.centers[selectedCenter].expected || 6;
                        }
                    }
                    
                    const coverage = totalExpected > 0 ? Math.round((totalReceived / totalExpected) * 100) : 0;
                    stationCoverages.push(coverage);
                    
                    if (coverage >= 80) {
                        backgroundColors.push('#10B981');
                        borderColors.push('#059669');
                    } else if (coverage >= 30) {
                        backgroundColors.push('#F59E0B');
                        borderColors.push('#D97706');
                    } else if (coverage > 0) {
                        backgroundColors.push('#EF4444');
                        borderColors.push('#DC2626');
                    } else {
                        backgroundColors.push('#6B7280');
                        borderColors.push('#4B5563');
                    }
                });
                
                allStationsChart.data.labels = stationLabels;
                allStationsChart.data.datasets[0].data = stationCoverages;
                allStationsChart.data.datasets[0].backgroundColor = backgroundColors;
                allStationsChart.data.datasets[0].borderColor = borderColors;
                
                const chartContainer = document.getElementById('allStationsChart')?.parentElement;
                if (chartContainer) {
                    const minHeight = 300;
                    const heightPerStation = 25;
                    const newHeight = Math.max(minHeight, stations.length * heightPerStation);
                    chartContainer.style.height = newHeight + 'px';
                }
                
                allStationsChart.update();
            }
            
            // 2. Update Status Distribution Chart (Pie/Doughnut)
            let complete = 0, issues = 0, critical = 0, notReceived = 0;
            
            stations.forEach(station => {
                let totalReceived = 0;
                let totalExpected = 0;
                
                if (selectedCenter === 'ALL') {
                    CENTERS.forEach(center => {
                        if (station.centers && station.centers[center]) {
                            totalReceived += station.centers[center].received || 0;
                            totalExpected += station.centers[center].expected || 6;
                        }
                    });
                } else {
                    if (station.centers && station.centers[selectedCenter]) {
                        totalReceived = station.centers[selectedCenter].received || 0;
                        totalExpected = station.centers[selectedCenter].expected || 6;
                    }
                }
                
                const coverage = totalExpected > 0 ? (totalReceived / totalExpected) * 100 : 0;
                
                if (coverage >= 80) complete++;
                else if (coverage >= 30) issues++;
                else if (coverage > 0) critical++;
                else notReceived++;
            });
            
            if (statusChart) {
                statusChart.data.datasets[0].data = [complete, issues, critical, notReceived];
                statusChart.update();
            }
            
            // 3. Update Coverage by Center Chart (Bar Chart)
            const centerAverages = [];
            
            CENTERS.forEach(center => {
                let totalCoverage = 0;
                let stationCount = 0;
                
                stations.forEach(station => {
                    if (station.centers && station.centers[center]) {
                        const received = station.centers[center].received || 0;
                        const expected = station.centers[center].expected || 6;
                        if (expected > 0) {
                            totalCoverage += (received / expected) * 100;
                            stationCount++;
                        }
                    }
                });
                
                const avgCoverage = stationCount > 0 ? Math.round(totalCoverage / stationCount) : 0;
                centerAverages.push(avgCoverage);
            });
            
            if (coverageChart) {
                coverageChart.data.datasets[0].data = centerAverages;
                coverageChart.update();
            }
            
            showChartLoading(false);
            if (allStationsLoading) allStationsLoading.classList.add('hidden');
        }, 200);
    }
    
    // Populate station table with detailed information (with pagination)
    function populateStationTable(stations) {
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
        
        let dateDisplay = selectedDate;
        
        if (selectedPeriod === 'monthly') {
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
        
        timestampElement.textContent = `Data for: ${dateDisplay} (${selectedPeriod})${filterInfo}`;
        
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
        
        let rowHTML = '';
        
        // Station Name (always first) - shows WIGOS below on mobile
        rowHTML += `
            <td class="px-3 lg:px-4 py-3 text-left text-sm">
                <div class="font-medium text-gray-900">${station.name || 'Unknown Station'}</div>
                <div class="text-xs text-gray-500">${station.wigosId || 'N/A'}</div>
            </td>`;
        
        // WIGOS ID (hidden on small screens)
        rowHTML += `<td class="px-3 lg:px-4 py-3 text-center text-xs font-mono text-gray-600 hidden sm:table-cell">${station.wigosId || 'N/A'}</td>`;
        
        // Country
        rowHTML += `<td class="px-3 lg:px-4 py-3 text-center text-sm">${station.countryCode || station.territory || 'N/A'}</td>`;
        
        // Coordinates (hidden on medium and below)
        rowHTML += `<td class="px-3 lg:px-4 py-3 text-center text-xs font-mono text-gray-600 hidden md:table-cell">
            ${station.latitude ? station.latitude.toFixed(4) : 'N/A'}, ${station.longitude ? station.longitude.toFixed(4) : 'N/A'}
        </td>`;
        
        // Station Type (hidden on large and below)
        rowHTML += `<td class="px-3 lg:px-4 py-3 text-center text-sm text-gray-700 hidden lg:table-cell">
            ${station.stationTypeName || 'SYNOP'}
        </td>`;
        
        // In OSCAR (hidden on large and below)
        const oscarStatus = station.inOSCAR || 'True';
        const oscarColor = oscarStatus === 'Yes' || oscarStatus === 'True' ? 'text-green-600' : oscarStatus === 'No' || oscarStatus === 'False' ? 'text-red-600' : 'text-gray-600';
        rowHTML += `<td class="px-3 lg:px-4 py-3 text-center text-sm ${oscarColor} hidden lg:table-cell">
            ${oscarStatus}
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
        // Set default values (2 days ago)
        const twoDaysAgo = new Date();
        twoDaysAgo.setDate(twoDaysAgo.getDate() - 2);
        const dateStr = twoDaysAgo.toISOString().split('T')[0];
        const dateInput = document.getElementById('dateSelect');
        
        // Set default based on period type
        const periodSelect = document.getElementById('periodTypeSelect');
        if (periodSelect.value === 'monthly') {
            dateInput.type = 'month';
            dateInput.style.cursor = 'pointer';
            const lastMonth = new Date();
            lastMonth.setMonth(lastMonth.getMonth() - 1);
            dateInput.value = lastMonth.toISOString().slice(0, 7);
        } else {
            dateInput.type = 'date';
            dateInput.style.cursor = 'pointer';
            dateInput.value = dateStr;
        }
        
        // Period type change handler (no time period buttons for upper air)
        document.getElementById('periodTypeSelect').addEventListener('change', function() {
            const dateInput = document.getElementById('dateSelect');
            
            if (this.value === 'daily') {
                dateInput.type = 'date';
                dateInput.removeAttribute('readonly');
                // Set to 2 days ago for daily data
                const twoDaysAgo = new Date();
                twoDaysAgo.setDate(twoDaysAgo.getDate() - 2);
                dateInput.value = twoDaysAgo.toISOString().split('T')[0];
            } else if (this.value === 'monthly') {
                // Force a re-render to show month picker
                dateInput.type = 'text';
                dateInput.type = 'month';
                dateInput.setAttribute('readonly', 'readonly');
                dateInput.style.cursor = 'pointer';
                // Set to last month for monthly data
                const lastMonth = new Date();
                lastMonth.setMonth(lastMonth.getMonth() - 1);
                const yearMonth = lastMonth.toISOString().slice(0, 7);
                dateInput.value = yearMonth;
                
                // Force focus to trigger picker
                setTimeout(() => {
                    dateInput.removeAttribute('readonly');
                }, 50);
            }
            
            // Auto load data when period changes
            loadData();
        });
        
        // Variable change handler - reload data from API
        document.getElementById('variableSelect').addEventListener('change', function() {
            showControlLoading(true);
            setTimeout(() => {
                loadData();
            }, 100);
        });
        
        // Region change handler - reload data from API
        document.getElementById('regionSelect').addEventListener('change', function() {
            showControlLoading(true);
            setTimeout(() => {
                loadData();
            }, 100);
        });
        
        // Center change handler - filter existing data (no API reload needed)
        document.getElementById('centerSelect').addEventListener('change', function() {
            if (currentStations.length > 0) {
                applyFiltersAndUpdate();
            } else {
                // If no data loaded yet, load it
                loadData();
            }
        });
        
        // Date change handler
        document.getElementById('dateSelect').addEventListener('change', loadData);
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
        if (isLoading) {
            return;
        }
        
        try {
            isLoading = true;
            showAllLoading(true);
            
            const territory = document.getElementById('regionSelect').value;
            const variable = document.getElementById('variableSelect').value;
            const date = document.getElementById('dateSelect').value;
            const period = document.getElementById('periodTypeSelect').value;
            
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
            
            const data = await fetchUpperAirData(territory, date, variable, period);
            
            if (data.stations && data.stations.length > 0) {
                currentStations = data.stations;
                
                // Apply all filters to the loaded data
                const filteredData = filterStationsByCurrentSelection(data.stations);
                
                // Update table header first
                updateTableHeader();
                
                // Update all displays with filtered data
                populateStationTable(filteredData);
                updateStatistics(filteredData);
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
                throw new Error('No upper air station data found for the selected parameters');
            }
            
        } catch (error) {
            showErrorMessage(`Error: ${error.message}`);
            
            // Clear displays
            document.getElementById('station-table-body').innerHTML = `
                <tr>
                    <td colspan="9" class="px-4 py-12 text-center text-gray-500">
                        <div>
                            <p class="text-lg mb-2 text-red-600">Failed to load upper air data</p>
                            <p class="text-sm text-gray-700">${error.message}</p>
                            <p class="text-xs text-gray-500 mt-2">Please check the console (F12) for more details or try different parameters.</p>
                        </div>
                    </td>
                </tr>
            `;
            
            // Reset statistics
            document.getElementById('totalStations').innerHTML = '<span class="text-gray-400">0</span>';
            document.getElementById('activeStations').innerHTML = '<span class="text-gray-400">0</span>';
            document.getElementById('dataAvailability').innerHTML = '<span class="text-gray-400">0.0%</span>';
            
            // Update last update time to show error
            document.getElementById('lastUpdateTime').textContent = 'Error loading data';
            
        } finally {
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
        
        const selectedCenter = document.getElementById('centerSelect').value;
        const stats = calculateCenterStatistics(stations, selectedCenter);
        
        let totalStations = 0;
        let operationalStations = 0;
        let totalAvailability = 0;
        let stationCount = 0;
        
        // Calculate overall statistics
        Object.keys(stats).forEach(center => {
            totalStations += stats[center].total;
            operationalStations += stats[center].complete;
        });
        
        // Calculate average availability
        stations.forEach(station => {
            if (station.dataCompleteness !== undefined) {
                totalAvailability += station.dataCompleteness;
                stationCount++;
            } else {
                // Calculate from center data
                let stationTotal = 0;
                let centerCount = 0;
                CENTERS.forEach(center => {
                    if (station.centers && station.centers[center]) {
                        const centerData = station.centers[center];
                        const coverage = centerData.expected > 0 ? (centerData.received / centerData.expected) * 100 : 0;
                        stationTotal += coverage;
                        centerCount++;
                    } else if (station[center] !== undefined) {
                        const coverage = station.expected > 0 ? (station[center] / station.expected) * 100 : 0;
                        stationTotal += coverage;
                        centerCount++;
                    }
                });
                if (centerCount > 0) {
                    totalAvailability += stationTotal / centerCount;
                    stationCount++;
                }
            }
        });
        
        const averageAvailability = stationCount > 0 ? (totalAvailability / stationCount) : 0;
        
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
        
        // Auto-load data on page load with error handling
        setTimeout(() => {
            loadData().catch(error => {
                showAllLoading(false);
            });
        }, 500);
        
        // Auto-refresh every 5 minutes (disabled by default, uncomment to enable)
        // setInterval(() => {
        //     loadData();
        // }, 300000);
    });
</script>

<?php include '../../includes/footer.php'; ?>