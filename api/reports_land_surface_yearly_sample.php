<?php
/**
 * Sample Data API for Country Comparison Charts
 * Returns pre-generated sample data for quick testing
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$year = isset($_GET['year']) ? intval($_GET['year']) : 2024;

// Sample data based on typical regional patterns
$month_labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

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

// Generate realistic sample data for each country
function generateSampleData($baseValue = 80, $variance = 15) {
    $data = [];
    for ($i = 0; $i < 12; $i++) {
        // Add some seasonal variation
        $seasonal = sin(($i / 12) * 2 * M_PI) * 5;
        $value = $baseValue + $seasonal + (rand(-$variance, $variance) / 2);
        $data[] = round(max(0, min(100, $value)), 1);
    }
    return $data;
}

// Base availability by country (approximate)
$countryBaseValues = [
    'BRN' => 30,  // Brunei - limited stations
    'IDN' => 75,  // Indonesia - good coverage
    'MYS' => 85,  // Malaysia - excellent
    'PHL' => 70,  // Philippines - good
    'PNG' => 45,  // PNG - developing
    'SGP' => 95,  // Singapore - excellent
    'TLS' => 25,  // East Timor - limited
    'USA' => 20   // USA Pacific - limited stations
];

// Variables
$variables = ['pressure', 'temperature', 'zonal_wind', 'meridional_wind', 'humidity'];

$chart_data = [];

foreach ($variables as $var) {
    $datasets = [];
    foreach ($countryBaseValues as $code => $baseValue) {
        // Slight variation between variables
        $varModifier = match($var) {
            'pressure' => 0,
            'temperature' => -5,
            'zonal_wind' => -10,
            'meridional_wind' => -10,
            'humidity' => -3,
            default => 0
        };
        
        $datasets[] = [
            'label' => $code,
            'fullName' => match($code) {
                'BRN' => 'Brunei',
                'IDN' => 'Indonesia',
                'MYS' => 'Malaysia',
                'PHL' => 'Philippines',
                'PNG' => 'Papua New Guinea',
                'SGP' => 'Singapore',
                'TLS' => 'East Timor',
                'USA' => 'USA',
                default => $code
            },
            'data' => generateSampleData($baseValue + $varModifier, 10),
            'borderColor' => $country_colors[$code],
            'backgroundColor' => $country_colors[$code] . '33',
            'fill' => false,
            'tension' => 0.3,
            'pointRadius' => 3,
            'pointHoverRadius' => 5
        ];
    }
    
    $chart_data[$var] = [
        'title' => match($var) {
            'pressure' => 'Availability of Surface Pressure Data',
            'temperature' => 'Availability of Temperature Data',
            'zonal_wind' => 'Availability of Zonal Wind Data',
            'meridional_wind' => 'Availability of Meridional Wind Data',
            'humidity' => 'Availability of Relative Humidity Data',
            default => ucfirst($var) . ' Data'
        },
        'labels' => $month_labels,
        'datasets' => $datasets
    ];
}

echo json_encode([
    'success' => true,
    'year' => $year,
    'data' => $chart_data,
    'is_sample' => true,
    'note' => 'This is sample data for testing. Use the main API for real data.'
], JSON_PRETTY_PRINT);
?>
