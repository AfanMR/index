<?php
/**
 * Optimized Yearly Availability API for GBON Land Surface Data
 * Uses parallel curl requests for faster loading
 * Returns monthly availability data per country for a full year
 */

ini_set('display_errors', 0);
error_reporting(0);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

set_time_limit(120); // 2 minutes timeout

// Cache configuration
$cacheDir = __DIR__ . '/../cache';
if (!is_dir($cacheDir)) {
    mkdir($cacheDir, 0755, true);
}

// Get parameters
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$variable = isset($_GET['variable']) ? strtolower($_GET['variable']) : 'all';

// Check cache first (cache for 6 hours)
$cacheFile = "$cacheDir/yearly_optimized_{$year}_{$variable}.json";
$cacheExpiry = 6 * 60 * 60;

if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheExpiry) {
    echo file_get_contents($cacheFile);
    exit;
}

// Region V countries
$countries = [
    'BRN' => 'Brunei',
    'IDN' => 'Indonesia', 
    'MYS' => 'Malaysia',
    'PHL' => 'Philippines',
    'PNG' => 'Papua New Guinea',
    'SGP' => 'Singapore',
    'TLS' => 'East Timor',
    'USA' => 'USA'
];

$country_colors = [
    'BRN' => '#8B5CF6',
    'IDN' => '#3B82F6',
    'MYS' => '#10B981',
    'PHL' => '#EC4899',
    'PNG' => '#F59E0B',
    'SGP' => '#06B6D4',
    'TLS' => '#EF4444',
    'USA' => '#F97316'
];

// Variables to fetch
$variables = ($variable === 'all') 
    ? ['pressure', 'temperature', 'zonal_wind', 'meridional_wind', 'humidity']
    : [$variable];

$month_labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

// Calculate which months to fetch (don't fetch future months)
$current_year = intval(date('Y'));
$current_month = intval(date('m'));
$max_month = ($year < $current_year) ? 12 : (($year == $current_year) ? $current_month : 0);

if ($max_month == 0) {
    // Future year, return empty data
    echo json_encode([
        'success' => true,
        'year' => $year,
        'data' => [],
        'note' => 'No data available for future years'
    ]);
    exit;
}

// Build list of all URLs to fetch
$urls = [];
$url_map = []; // Map URL to [variable, month]

foreach ($variables as $var) {
    for ($month = 1; $month <= $max_month; $month++) {
        $date = sprintf('%04d-%02d-15', $year, $month);
        $url = "https://wdqms.wmo.int/wdqmsapi/v1/download/gbon/synop/monthly/availability/?date=$date&variable=$var&centers=DWD,ECMWF,JMA,NCEP";
        $urls[] = $url;
        $url_map[$url] = ['var' => $var, 'month' => $month];
    }
}

// Initialize result structure
$result_data = [];
foreach ($variables as $var) {
    $result_data[$var] = [
        'title' => getVariableTitle($var),
        'countries' => []
    ];
    foreach ($countries as $code => $name) {
        $result_data[$var]['countries'][$code] = [
            'name' => $name,
            'code' => $code,
            'color' => $country_colors[$code],
            'data' => array_fill(0, 12, null)
        ];
    }
}

// Parallel fetch using curl_multi
$responses = parallelCurlFetch($urls);

// Process responses
foreach ($responses as $url => $response) {
    if ($response === false) continue;
    
    $info = $url_map[$url];
    $var = $info['var'];
    $month = $info['month'];
    
    $lines = explode("\n", trim($response));
    if (count($lines) <= 1) continue;
    
    $headers = str_getcsv($lines[0]);
    
    // Group data by country
    $country_totals = [];
    foreach (array_keys($countries) as $code) {
        $country_totals[$code] = ['received' => 0, 'expected' => 0];
    }
    
    for ($i = 1; $i < count($lines); $i++) {
        if (empty(trim($lines[$i]))) continue;
        
        $row = str_getcsv($lines[$i]);
        if (count($row) < count($headers)) continue;
        
        $rowData = array_combine($headers, $row);
        $countryCode = $rowData['country code'] ?? '';
        
        // Map US Pacific territories to USA
        if (in_array($countryCode, ['GUM', 'MNP', 'ASM', 'FSM', 'PLW', 'MHL'])) {
            $countryCode = 'USA';
        }
        
        if (isset($country_totals[$countryCode])) {
            $country_totals[$countryCode]['received'] += intval($rowData['#received'] ?? 0);
            $country_totals[$countryCode]['expected'] += intval($rowData['#expected'] ?? 0);
        }
    }
    
    // Calculate percentages
    foreach ($country_totals as $code => $data) {
        if ($data['expected'] > 0) {
            $percentage = round(($data['received'] / $data['expected']) * 100, 1);
            $result_data[$var]['countries'][$code]['data'][$month - 1] = $percentage;
        }
    }
}

// Format output for Chart.js
$chart_data = [];
foreach ($result_data as $var => $varData) {
    $datasets = [];
    foreach ($varData['countries'] as $code => $countryData) {
        $datasets[] = [
            'label' => $code,
            'fullName' => $countryData['name'],
            'data' => $countryData['data'],
            'borderColor' => $countryData['color'],
            'backgroundColor' => $countryData['color'] . '33',
            'fill' => false,
            'tension' => 0.3,
            'pointRadius' => 3,
            'pointHoverRadius' => 5
        ];
    }
    
    $chart_data[$var] = [
        'title' => $varData['title'],
        'labels' => $month_labels,
        'datasets' => $datasets
    ];
}

$output = json_encode([
    'success' => true,
    'year' => $year,
    'data' => $chart_data,
    'countries' => $countries,
    'colors' => $country_colors,
    'fetched_months' => $max_month,
    'cached_at' => date('Y-m-d H:i:s')
], JSON_PRETTY_PRINT);

// Save to cache
file_put_contents($cacheFile, $output);

echo $output;

// ============ Helper Functions ============

function parallelCurlFetch($urls, $timeout = 20) {
    $multi_handle = curl_multi_init();
    $curl_handles = [];
    $responses = [];
    
    // Initialize all curl handles
    foreach ($urls as $url) {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_CONNECTTIMEOUT => 10
        ]);
        
        curl_multi_add_handle($multi_handle, $ch);
        $curl_handles[$url] = $ch;
    }
    
    // Execute all handles in parallel
    $running = null;
    do {
        curl_multi_exec($multi_handle, $running);
        curl_multi_select($multi_handle);
    } while ($running > 0);
    
    // Collect responses
    foreach ($curl_handles as $url => $ch) {
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode == 200) {
            $responses[$url] = curl_multi_getcontent($ch);
        } else {
            $responses[$url] = false;
        }
        curl_multi_remove_handle($multi_handle, $ch);
        curl_close($ch);
    }
    
    curl_multi_close($multi_handle);
    
    return $responses;
}

function getVariableTitle($var) {
    $titles = [
        'pressure' => 'Availability of Surface Pressure Data',
        'temperature' => 'Availability of Temperature Data',
        'zonal_wind' => 'Availability of Zonal Wind Data',
        'meridional_wind' => 'Availability of Meridional Wind Data',
        'humidity' => 'Availability of Relative Humidity Data'
    ];
    return $titles[$var] ?? ucfirst(str_replace('_', ' ', $var)) . ' Data';
}
?>
