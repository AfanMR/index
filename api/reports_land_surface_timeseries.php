<?php
/**
 * Time-Series API for GBON Land Surface Data
 * Returns per-station data for time-series charts
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

set_time_limit(300);

// Helper function to get days in month
function getDaysInMonth($month, $year) {
    return (int)date('t', mktime(0, 0, 0, $month, 1, $year));
}

try {
    // Get parameters
    $territory = isset($_GET['territory']) ? strtoupper(trim($_GET['territory'])) : 'IDN';
    $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
    $month = isset($_GET['month']) ? intval($_GET['month']) : date('m');
    $variable = isset($_GET['variable']) ? strtolower($_GET['variable']) : 'pressure';
    $view = isset($_GET['view']) ? strtolower($_GET['view']) : 'daily';
    $selected_stations = isset($_GET['stations']) ? explode(',', $_GET['stations']) : [];
    $days = isset($_GET['days']) ? intval($_GET['days']) : 7; // 7, 14, or 30 days for daily view
    
    $territories = explode(',', $territory);
    
    // Calculate date range based on view
    $dates = [];
    $labels = [];
    
    if ($view === 'daily') {
        $days_in_month = getDaysInMonth($month, $year);
        $num_days = min($days, $days_in_month); // Use requested days, max is days in month
        $start_day = max(1, $days_in_month - $num_days + 1);
        for ($day = $start_day; $day <= $days_in_month; $day++) {
            $dates[] = sprintf('%04d-%02d-%02d', $year, $month, $day);
            $labels[] = $day;
        }
    } elseif ($view === 'weekly') {
        $days_in_month = getDaysInMonth($month, $year);
        for ($week = 1; $week <= 4; $week++) {
            $mid_day = min(($week * 7) - 3, $days_in_month);
            $dates[] = sprintf('%04d-%02d-%02d', $year, $month, $mid_day);
            $labels[] = "Minggu $week";
        }
    } elseif ($view === 'monthly') {
        for ($i = 5; $i >= 0; $i--) {
            $m = $month - $i;
            $y = $year;
            if ($m <= 0) { $m += 12; $y--; }
            $dates[] = sprintf('%04d-%02d-15', $y, $m);
            $month_names = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $labels[] = $month_names[$m - 1];
        }
    } elseif ($view === 'yearly') {
        for ($y = $year - 2; $y <= $year; $y++) {
            $dates[] = sprintf('%04d-06-15', $y);
            $labels[] = $y;
        }
    }
    
    // Track data per station per date
    $station_data = []; // [wigosId => ['name' => '', 'data' => [values per date]]]
    $aggregate_data = array_fill(0, count($dates), 0);
    
    // Fetch data for each date
    foreach ($dates as $date_index => $date) {
        $url = "https://wdqms.wmo.int/wdqmsapi/v1/download/gbon/synop/daily/availability/?" . 
               "date=$date&variable=$variable&centers=DWD,ECMWF,JMA,NCEP";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode == 200 && $response) {
            $lines = explode("\n", trim($response));
            if (count($lines) > 1) {
                $headers = str_getcsv($lines[0]);
                
                for ($i = 1; $i < count($lines); $i++) {
                    if (!empty(trim($lines[$i]))) {
                        $row = str_getcsv($lines[$i]);
                        if (count($row) >= count($headers)) {
                            $rowData = array_combine($headers, $row);
                            
                            $countryCode = $rowData['country code'] ?? '';
                            if (!in_array($countryCode, $territories)) {
                                continue;
                            }
                            
                            $wigosId = $rowData['wigosid'] ?? '';
                            $name = $rowData['name'] ?? 'Unknown';
                            $received = intval($rowData['#received'] ?? 0);
                            
                            // Filter by selected stations if specified
                            if (!empty($selected_stations) && !in_array($wigosId, $selected_stations) && !in_array($name, $selected_stations)) {
                                continue;
                            }
                            
                            // Initialize station if first time
                            if (!isset($station_data[$wigosId])) {
                                $station_data[$wigosId] = [
                                    'name' => $name,
                                    'data' => array_fill(0, count($dates), 0)
                                ];
                            }
                            
                            // Add received data
                            $station_data[$wigosId]['data'][$date_index] += $received;
                            $aggregate_data[$date_index] += $received;
                        }
                    }
                }
            }
        }
        
        usleep(50000); // 50ms delay
    }
    
    // Convert station_data to array for JSON
    $stations_list = [];
    foreach ($station_data as $wigosId => $info) {
        $stations_list[] = [
            'name' => $info['name'],
            'wigosId' => $wigosId,
            'data' => $info['data']
        ];
    }
    
    // Sort by total received (descending)
    usort($stations_list, function($a, $b) {
        return array_sum($b['data']) - array_sum($a['data']);
    });
    
    // Only limit to top 10 if no specific stations were selected
    if (empty($selected_stations)) {
        $output_stations = array_slice($stations_list, 0, 10);
    } else {
        $output_stations = $stations_list; // Show all selected stations
    }
    
    echo json_encode([
        'success' => true,
        'labels' => $labels,
        'dates' => $dates,
        'view' => $view,
        'aggregate' => $aggregate_data,
        'stations' => $output_stations,
        'metadata' => [
            'territory' => $territory,
            'total_stations' => count($station_data),
            'selected_count' => count($output_stations),
            'date_count' => count($dates)
        ]
    ], JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
