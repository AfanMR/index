<?php
// Disable error display for production
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Set up error handling
$log_file = __DIR__ . '/../logs/wmo_api.log';

try {
    // Valid parameters for NWP endpoint
    $valid_variables = [
        'pressure',
        'geopotential',
        'temperature',
        'zonal_wind',
        'meridional_wind',
        'humidity'
    ];

    $valid_periods = [
        'six_hour',
        'daily',
        'monthly',
        'alert'
    ];

    $valid_time_periods = ['00', '06', '12', '18'];
    
    $valid_monitoring_categories = [
        'availability',
        'quality',
        'timeliness'
    ];
    
    $valid_baselines = [
        'OSCAR',
        '2-daily'
    ];
    
    $valid_centers = [
        'DWD',
        'ECMWF',
        'JMA',
        'NCEP'
    ];

    // Get parameters from request with yesterday as default date
    $territory = isset($_GET['territory']) ? strtoupper(trim($_GET['territory'])) : 'SGP,IDN,BRN,PHL,TLS,PNG,MYS';
    $date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d', strtotime('-2 day'));
    $period = isset($_GET['period']) ? $_GET['period'] : 'six_hour';
    $time_period = isset($_GET['time_period']) ? $_GET['time_period'] : '18';
    $variable = isset($_GET['variable']) ? strtolower($_GET['variable']) : 'pressure';
    $monitoring_category = isset($_GET['monitoring_category']) ? strtolower($_GET['monitoring_category']) : 'availability';
    // Baseline is not used for quality monitoring
    $baseline = ($monitoring_category === 'quality') ? null : (isset($_GET['baseline']) ? strtoupper($_GET['baseline']) : 'OSCAR');
    $centers = isset($_GET['centers']) ? strtoupper($_GET['centers']) : 'DWD,ECMWF,JMA,NCEP';

    // Special case for ALL_COMBINED to fetch both Region V and USA territories
    $fetch_usa_territories = false;
    if ($territory === 'ALL_COMBINED') {
        $territory = 'SGP,IDN,BRN,PHL,TLS,PNG,MYS';
        $fetch_usa_territories = true;
    }

    // Special case for USA_PACIFIC to fetch all USA Pacific stations
    if ($territory === 'USA_PACIFIC') {
        $territory = 'LIH,HNL,OGG,ITO,GUM,PPG';
        $is_usa_territory = true;
    }

    // Check if we're requesting USA stations
    $usa_territories = ['LIH', 'HNL', 'OGG', 'ITO', 'GUM', 'PPG', 'USA', 'USA_PACIFIC'];
    $is_usa_territory = false;
    $territories = explode(',', $territory);
    foreach ($territories as $t) {
        if (in_array($t, $usa_territories)) {
            $is_usa_territory = true;
            break;
        }
    }

    // If ALL_COMBINED was requested, force USA territory fetch
    if ($fetch_usa_territories) {
        $is_usa_territory = true;
    }

    // Validate and correct parameters
    if (!in_array($variable, $valid_variables)) {
        $variable = 'pressure'; // Default to pressure if invalid
    }

    if (!in_array($period, $valid_periods)) {
        $period = 'six_hour'; // Default to six_hour if invalid
    }

    if (!in_array($time_period, $valid_time_periods)) {
        $time_period = '18'; // Default to 18 if invalid
    }
    
    if (!in_array($monitoring_category, $valid_monitoring_categories)) {
        $monitoring_category = 'availability'; // Default to availability if invalid
    }
    
    // Set baseline based on monitoring category
    if ($monitoring_category === 'quality' || $monitoring_category === 'timeliness') {
        $baseline = null; // No baseline for quality and timeliness
    } elseif (!in_array($baseline, $valid_baselines)) {
        $baseline = 'OSCAR'; // Default to OSCAR if invalid baseline
    }
    
    // Validate centers parameter
    $center_list = explode(',', $centers);
    $validated_centers = [];
    foreach ($center_list as $center) {
        $center = trim($center);
        if (in_array($center, $valid_centers)) {
            $validated_centers[] = $center;
        }
    }
    if (empty($validated_centers)) {
        $centers = 'DWD,ECMWF,JMA,NCEP'; // Default to all centers if invalid
    } else {
        $centers = implode(',', $validated_centers);
    }

    // Log file setup
    $log_message = date('Y-m-d H:i:s') . " - NWP Request started\n";
    $log_message .= "Territory: $territory\n";
    $log_message .= "Date: $date\n";
    $log_message .= "Period: $period\n";
    $log_message .= "Time Period: $time_period\n";
    $log_message .= "Variable: $variable\n";
    $log_message .= "Monitoring Category: $monitoring_category\n";
    $log_message .= "Baseline: $baseline\n";
    $log_message .= "Centers: $centers\n";

    // Initialize response variables
    $stations = [];
    $usa_stations = []; // Store USA stations separately
    $apiStatus = '';
    $stationDataByWigosId = []; // For Region V stations
    $usaStationDataByWigosId = []; // Separate array for USA stations
    
    // For timeliness monitoring, only use DWD and ECMWF centers
    if ($monitoring_category === 'timeliness') {
        $centers = 'DWD,ECMWF';
    }

    // If requesting USA territory, fetch from WMO API with Pacific region filter
    if ($is_usa_territory) {
        // Build USA API URL with appropriate parameters based on monitoring category
        if ($monitoring_category === 'quality') {
            // Quality monitoring requires variable parameter
            $usa_api_url = "https://wdqms.wmo.int/wdqmsapi/v1/download/nwp/temp/$period/$monitoring_category/?" . 
                           "date=$date&period=$time_period&variable=$variable&centers=$centers";
        } elseif ($monitoring_category === 'timeliness') {
            // Timeliness monitoring does not need variable or baseline
            $usa_api_url = "https://wdqms.wmo.int/wdqmsapi/v1/download/nwp/temp/$period/$monitoring_category/?" . 
                           "date=$date&period=$time_period&centers=$centers";
        } else {
            // Availability monitoring uses baseline
            $usa_api_url = "https://wdqms.wmo.int/wdqmsapi/v1/download/nwp/temp/$period/$monitoring_category/?" . 
                           "date=$date&period=$time_period&centers=$centers&baseline=$baseline";
        }
        
        $log_message .= "USA API URL: $usa_api_url\n";
        file_put_contents($log_file, $log_message, FILE_APPEND);
        
        // Initialize cURL for USA data
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $usa_api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if (!$error && $httpCode == 200) {
            // Process CSV response
            $lines = explode("\n", trim($response));
            if (count($lines) > 1) {
                $headers = str_getcsv($lines[0]);
                
                // Filter for specific Hawaii and Guam stations
                $target_stations = [
                    'LIHUE, KAUAI, HAWAII',
                    'HONOLULU, OAHU, HAWAII',
                    'KAHULUI AIRPORT, MAUI, HAWAII',
                    'HILO HI, HAWAII',
                    'WEATHER FORECAST OFFICE, GUAM, MARIANA IS.',
                    'PAGO PAGO/INT.AIRP. AMERICAN SAMOA'
                ];
                
                // Process all rows
                for ($i = 1; $i < count($lines); $i++) {
                    if (!empty(trim($lines[$i]))) {
                        $row = str_getcsv($lines[$i]);
                        if (count($row) >= count($headers)) {
                            $stationData = array_combine($headers, $row);
                            
                            // Only process target stations
                            if (in_array($stationData['name'], $target_stations)) {
                                $wigosId = $stationData['wigosid'];
                                $center = $stationData['center'] ?? 'Unknown';
                                
                                // Initialize station data if first time seeing this station
                                if (!isset($usaStationDataByWigosId[$wigosId])) {
                                    // Map station name to territory code
                                    $territoryCode = '';
                                    switch ($stationData['name']) {
                                        case 'LIHUE, KAUAI, HAWAII':
                                            $territoryCode = 'LIH';
                                            break;
                                        case 'HONOLULU, OAHU, HAWAII':
                                            $territoryCode = 'HNL';
                                            break;
                                        case 'KAHULUI AIRPORT, MAUI, HAWAII':
                                            $territoryCode = 'OGG';
                                            break;
                                        case 'HILO HI, HAWAII':
                                            $territoryCode = 'ITO';
                                            break;
                                        case 'WEATHER FORECAST OFFICE, GUAM, MARIANA IS.':
                                            $territoryCode = 'GUM';
                                            break;
                                        case 'PAGO PAGO/INT.AIRP. AMERICAN SAMOA':
                                            $territoryCode = 'PPG';
                                            break;
                                    }
                                    
                                    // Initialize centers based on monitoring category
                                    $centerInit = [];
                                    if ($monitoring_category === 'timeliness') {
                                        // Hanya DWD dan ECMWF untuk timeliness
                                        $centerInit = [
                                            'DWD' => ['timeliness' => null, 'nr_negative_timeliness' => 0, 'status' => 'not_received'],
                                            'ECMWF' => ['timeliness' => null, 'nr_negative_timeliness' => 0, 'status' => 'not_received']
                                        ];
                                    } elseif ($monitoring_category === 'quality') {
                                        $centerInit = [
                                            'DWD' => ['received' => 0, 'avg_bg_dep' => 0, 'status' => 'not_received'],
                                            'ECMWF' => ['received' => 0, 'avg_bg_dep' => 0, 'status' => 'not_received'],
                                            'JMA' => ['received' => 0, 'avg_bg_dep' => 0, 'status' => 'not_received'],
                                            'NCEP' => ['received' => 0, 'avg_bg_dep' => 0, 'status' => 'not_received']
                                        ];
                                    } else {
                                        $centerInit = [
                                            'DWD' => ['received' => 0, 'expected' => 0, 'status' => 'not_received'],
                                            'ECMWF' => ['received' => 0, 'expected' => 0, 'status' => 'not_received'],
                                            'JMA' => ['received' => 0, 'expected' => 0, 'status' => 'not_received'],
                                            'NCEP' => ['received' => 0, 'expected' => 0, 'status' => 'not_received']
                                        ];
                                    }
                                    
                                    $usaStationDataByWigosId[$wigosId] = [
                                        'name' => $stationData['name'],
                                        'wigosId' => $wigosId,
                                        'countryCode' => $stationData['country code'],
                                        'inOSCAR' => $stationData['in OSCAR'],
                                        'latitude' => (float)($stationData['latitude']),
                                        'longitude' => (float)($stationData['longitude']),
                                        'territory' => $territoryCode,
                                        'territoryCode' => $territoryCode,
                                        'stationTypeName' => 'TEMP',
                                        'variable' => $variable,
                                        'date' => $date,
                                        'centers' => $centerInit,
                                        'lastUpdated' => $date . ' ' . $time_period
                                    ];
                                }
                                
                                // Add center-specific data
                                if (isset($usaStationDataByWigosId[$wigosId]['centers'][$center])) {
                                    // Handle different monitoring categories
                                    $status = 'not_received';
                                    
                                    if ($monitoring_category === 'timeliness') {
                                        // For timeliness monitoring
                                        $timeliness = floatval($stationData['timeliness'] ?? $stationData['timeliness (sec)'] ?? 0);
                                        $nr_negative_timeliness = intval($stationData['nr_negative_timeliness'] ?? 0);
                                        
                                        // Status determination based on timeliness (seconds)
                                        // Negative = late, Positive = early/on-time
                                        if ($timeliness >= 3600) {
                                            $status = 'operational';    // ≥ 1 hour early
                                        } elseif ($timeliness >= 0) {
                                            $status = 'issues';         // 0 to 1 hour early
                                        } elseif ($timeliness >= -1800) {
                                            $status = 'critical';       // 0 to 30 min late
                                        } elseif ($timeliness >= -3600) {
                                            $status = 'not_received';   // 30 min to 1 hour late
                                        } else {
                                            $status = 'offline';        // > 1 hour late
                                        }
                                        
                                        $usaStationDataByWigosId[$wigosId]['centers'][$center] = [
                                            'timeliness' => $timeliness,
                                            'nr_negative_timeliness' => $nr_negative_timeliness,
                                            'status' => $status,
                                            'description' => $stationData['description'] ?? 'Timeliness monitoring data',
                                            'monitoring_type' => 'timeliness'
                                        ];
                                    } elseif ($monitoring_category === 'quality') {
                                        // For quality monitoring - check if monthly (RMS) or six-hour/daily (avg_bg_dep)
                                        $received = 0; // Not used for quality
                                        $color_code = 'red'; // Default color for quality
                                        
                                        if ($period === 'monthly') {
                                            // MONTHLY: Use RMS_bg_dep
                                            $rms_bg_dep = floatval($stationData['rms_bg_dep'] ?? $stationData['RMS_bg_dep'] ?? $stationData['rms'] ?? 0);
                                            $avg_bg_dep = floatval($stationData['avg_bg_dep'] ?? $stationData['Avg_bg_dep'] ?? 0);
                                            $centerValue = $rms_bg_dep > 0 ? $rms_bg_dep : $avg_bg_dep;
                                            
                                            // Status determination
                                            if ($centerValue <= 0.5) {
                                                $status = 'operational';
                                            } elseif ($centerValue <= 1.0) {
                                                $status = 'issues';
                                            } elseif ($centerValue <= 5.0) {
                                                $status = 'more-than-100';
                                            } elseif ($centerValue <= 10.0) {
                                                $status = 'critical';
                                            } elseif ($centerValue > 10.0) {
                                                $status = 'not_received';
                                            } else {
                                                $status = 'no-match';
                                            }
                                            
                                            $usaStationDataByWigosId[$wigosId]['centers'][$center] = [
                                                'received' => $received,
                                                'rms_bg_dep' => $rms_bg_dep,
                                                'avg_bg_dep' => $avg_bg_dep,
                                                'status' => $status,
                                                'color_code' => $color_code,
                                                'description' => $stationData['description'] ?? 'Quality monitoring data (monthly)',
                                                'monitoring_type' => 'quality_monthly'
                                            ];
                                        } else {
                                            // SIX-HOUR/DAILY: Use avg_bg_dep
                                            $avg_bg_dep = floatval($stationData['avg_bg_dep'] ?? $stationData['Avg_bg_dep'] ?? 0);
                                            
                                            // Quality status determination based on avg_bg_dep values (absolute hPa)
                                            if ($avg_bg_dep <= 0.5) {
                                                $status = 'operational';    // ≤ 0.5 hPa (Green)
                                            } elseif ($avg_bg_dep <= 1.0) {
                                                $status = 'issues';         // 0.5 < x ≤ 1 hPa (Orange)
                                            } elseif ($avg_bg_dep <= 5.0) {
                                                $status = 'more-than-100';  // 1 < x ≤ 5 hPa (Pink)
                                            } elseif ($avg_bg_dep <= 10.0) {
                                                $status = 'critical';       // 5 < x ≤ 10 hPa (Red)
                                            } elseif ($avg_bg_dep > 10.0) {
                                                $status = 'not_received';   // > 10 hPa (Black)
                                            } else {
                                                $status = 'no-match';       // No quality data available
                                            }
                                            
                                            $usaStationDataByWigosId[$wigosId]['centers'][$center] = [
                                                'received' => $received,
                                                'avg_bg_dep' => $avg_bg_dep,
                                                'status' => $status,
                                                'color_code' => $color_code,
                                                'description' => $stationData['description'] ?? 'Quality monitoring data',
                                                'monitoring_type' => 'quality'
                                            ];
                                        }
                                    } else {
                                        // For availability monitoring, different data structure
                                        $received = intval($stationData['#received'] ?? 0);
                                        $color_code = strtolower($stationData['color code'] ?? 'red');
                                        
                                        // For availability monitoring, use expected parameter (original logic)
                                        $expected = intval($stationData['#expected'] ?? $stationData['#expected (NWP)'] ?? 0);
                                        
                                        if ($expected == 0) {
                                            if ($received > 0) {
                                                $status = 'more-than-100';  // More than expected
                                            } else {
                                                $status = 'oscar-issue';    // OSCAR schedule issue
                                            }
                                        } else {
                                            $percentage = ($received / $expected) * 100;
                                            
                                            if ($percentage > 100) {
                                                $status = 'more-than-100';  // More than 100%
                                            } elseif ($percentage >= 80) {
                                                $status = 'operational';    // Normal (≥80%)
                                            } elseif ($percentage >= 30) {
                                                $status = 'issues';         // Availability issues (≥30%)
                                            } elseif ($percentage > 0) {
                                                $status = 'critical';       // Availability issues (< 30%)
                                            } else {
                                                $status = 'not-received';   // Not received in period
                                            }
                                        }
                                        
                                        $usaStationDataByWigosId[$wigosId]['centers'][$center] = [
                                            'received' => $received,
                                            'expected' => $expected,
                                            'status' => $status,
                                            'color_code' => $color_code,
                                            'description' => $stationData['description'] ?? 'Availability monitoring data',
                                            'monitoring_type' => 'availability'
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
                
                // Convert collected data to stations array
                foreach ($usaStationDataByWigosId as $wigosId => $stationData) {
                    if (in_array($stationData['territoryCode'], $territories) || $fetch_usa_territories) {
                        // Handle different monitoring categories for overall calculation
                        if ($monitoring_category === 'quality') {
                            // For quality monitoring, calculate average bg_dep across centers
                            $total_bg_dep = 0;
                            $valid_centers = 0;
                            
                            foreach ($stationData['centers'] as $center => $centerData) {
                                if (isset($centerData['avg_bg_dep']) && $centerData['avg_bg_dep'] > 0) {
                                    $total_bg_dep += $centerData['avg_bg_dep'];
                                    $valid_centers++;
                                }
                            }
                            
                            $avg_quality = $valid_centers > 0 ? ($total_bg_dep / $valid_centers) : 0;
                            
                            // Determine overall status based on average quality
                            $status = 'not_received';
                            $inOSCAR = $stationData['inOSCAR'] ?? 'No';
                            
                            if ($inOSCAR === 'No' || $inOSCAR === 'False' || strtolower($inOSCAR) === 'no') {
                                $status = 'no-match';           // No match in OSCAR/Surface
                            } elseif ($valid_centers == 0) {
                                $status = 'oscar-issue';        // No quality data available
                            } elseif ($avg_quality <= 0.5) {
                                $status = 'operational';        // Excellent quality (≤ 0.5 hPa)
                            } elseif ($avg_quality <= 1.0) {
                                $status = 'issues';             // Good quality (0.5 < x ≤ 1 hPa)
                            } elseif ($avg_quality <= 5.0) {
                                $status = 'critical';           // Fair quality (1 < x ≤ 5 hPa)
                            } elseif ($avg_quality <= 10.0) {
                                $status = 'not_received';       // Poor quality (5 < x ≤ 10 hPa)
                            } else {
                                $status = 'offline';            // Very poor quality (> 10 hPa)
                            }
                        } else {
                            // For availability monitoring (original logic)
                            // Step 1: Check if ANY center has received > expected
                            $hasMoreThan100 = false;
                            foreach ($stationData['centers'] as $center => $centerData) {
                                if (isset($centerData['expected']) && $centerData['expected'] > 0 && $centerData['received'] > $centerData['expected']) {
                                    $hasMoreThan100 = true;
                                    break;
                                }
                            }
                            
                            // Step 2: Calculate overall stats
                            $total_received = 0;
                            $total_expected = 0;
                            
                            foreach ($stationData['centers'] as $center => $centerData) {
                                if (isset($centerData['received'])) $total_received += $centerData['received'];
                                if (isset($centerData['expected'])) $total_expected += $centerData['expected'];
                            }
                            
                            // Calculate overall availability
                            $availability = $total_expected > 0 ? ($total_received / $total_expected) * 100 : 0;
                            
                            // Determine overall status based on NWP observations
                            $status = 'not_received';
                            
                            // Check for special OSCAR cases first
                            $inOSCAR = $stationData['inOSCAR'] ?? 'No';
                            if ($inOSCAR === 'No' || $inOSCAR === 'False' || strtolower($inOSCAR) === 'no') {
                                $status = 'no-match';           // No match in OSCAR/Surface
                            } elseif ($total_expected <= 0) {
                                $status = 'oscar-issue';        // OSCAR schedule issue
                            } elseif ($hasMoreThan100) {
                                $status = 'more-than-100';      // Any center has more than 100%
                            } elseif ($availability >= 80) {
                                $status = 'operational';        // Normal (≥80%)
                            } elseif ($availability >= 30) {
                                $status = 'issues';             // Availability issues (≥30%)
                            } elseif ($availability > 0) {
                                $status = 'critical';           // Availability issues (< 30%)
                            } else {
                                $status = 'not-received';       // Not received in period
                            }
                        }
                        
                                    // Create status description for summary
                        $statusDescription = '';
                        switch ($status) {
                            case 'more-than-100':
                                $statusDescription = 'More than 100%';
                                break;
                            case 'operational':
                                $statusDescription = 'Normal (≥ 80%)';
                                break;
                            case 'issues':
                                $statusDescription = 'Availability Issues (≥ 30%)';
                                break;
                            case 'critical':
                                $statusDescription = 'Availability Issues (< 30%)';
                                break;
                            case 'not_received':
                                $statusDescription = 'Not received in period';
                                break;
                            case 'oscar-issue':
                                $statusDescription = 'OSCAR schedule issue';
                                break;
                            case 'no-match':
                                $statusDescription = 'No match in OSCAR/Surface';
                                break;
                        }
                        
                        // Create summary in format: date_country_station_status_system
                        $summary = $stationData['date'] . '_' . $stationData['countryCode'] . '_' . 
                                  str_replace([' ', ',', '.', '(', ')', '/'], '_', $stationData['name']) . '_' . 
                                  $statusDescription . '_NWP ' . ucfirst($monitoring_category) . ' Surfaceland';

                        // Create station entry based on monitoring category
                        $station = [
                            'id' => $wigosId,
                            'name' => $stationData['name'],
                            'wigosId' => $wigosId,
                            'countryCode' => $stationData['countryCode'],
                            'inOSCAR' => $stationData['inOSCAR'],
                            'latitude' => $stationData['latitude'],
                            'longitude' => $stationData['longitude'],
                            'territory' => $stationData['territory'],
                            'territoryCode' => $stationData['territoryCode'],
                            'stationTypeName' => $stationData['stationTypeName'],
                            'stationStatusCode' => $status,
                            'variable' => $stationData['variable'],
                            'date' => $stationData['date'],
                            'lastUpdated' => $stationData['lastUpdated'],
                            'summary' => $summary,
                            'monitoring_category' => $monitoring_category,
                            'centers' => $stationData['centers']
                        ];
                        
                        if ($monitoring_category === 'quality') {
                            // Add quality-specific fields
                            $station['avg_quality'] = $avg_quality ?? 0;
                            $station['dataCompleteness'] = round($avg_quality, 2); // For consistency with frontend
                        } else {
                            // Add availability-specific fields
                            $station['dataCompleteness'] = $availability;
                            $station['received'] = $total_received;
                            $station['expected'] = $total_expected;
                            $station['DWD'] = $stationData['centers']['DWD']['received'] ?? 0;
                            $station['ECMWF'] = $stationData['centers']['ECMWF']['received'] ?? 0;
                            $station['JMA'] = $stationData['centers']['JMA']['received'] ?? 0;
                            $station['NCEP'] = $stationData['centers']['NCEP']['received'] ?? 0;
                        }
                        
                        $usa_stations[] = $station;
                    }
                }
                
                // If only USA territories were requested, return the data now
                if (!$fetch_usa_territories && $is_usa_territory) {
                    echo json_encode([
                        'stations' => $usa_stations,
                        'metadata' => [
                            'total_stations' => count($usa_stations),
                            'operational_count' => count(array_filter($usa_stations, function($s) { return $s['stationStatusCode'] === 'operational'; })),
                            'issues_count' => count(array_filter($usa_stations, function($s) { return $s['stationStatusCode'] === 'issues'; })),
                            'critical_count' => count(array_filter($usa_stations, function($s) { return $s['stationStatusCode'] === 'critical'; })),
                            'not_received_count' => count(array_filter($usa_stations, function($s) { return $s['stationStatusCode'] === 'not_received'; })),
                            'territory' => $territory,
                            'monitoring_category' => $monitoring_category,
                            'baseline' => $baseline,
                            'centers' => $centers,
                            'variable' => $variable,
                            'period' => $period,
                            'time_period' => $time_period,
                            'api_url' => $usa_api_url,
                            'api_status' => 'Data successfully retrieved from NWP API'
                        ]
                    ]);
                    exit;
                }
            }
        }
    }

    // If not USA territory or USA data not found, or if we need to combine with Region V data
    // Build WDQMS NWP API URL
    // Build API URL with appropriate parameters based on monitoring category
    if ($monitoring_category === 'quality') {
        // Quality monitoring requires variable parameter
        $url = "https://wdqms.wmo.int/wdqmsapi/v1/download/nwp/temp/$period/$monitoring_category/?" . 
               "date=$date&period=$time_period&variable=$variable&centers=$centers";
    } elseif ($monitoring_category === 'timeliness') {
        // Timeliness monitoring does not need variable or baseline
        $url = "https://wdqms.wmo.int/wdqmsapi/v1/download/nwp/temp/$period/$monitoring_category/?" . 
               "date=$date&period=$time_period&centers=$centers";
    } else {
        // Availability monitoring uses baseline
        // For daily period with OSCAR or 2-daily baseline, use variable=temperature
        if ($period === 'daily' && ($baseline === 'OSCAR' || $baseline === '2-daily')) {
            $url = "https://wdqms.wmo.int/wdqmsapi/v1/download/nwp/temp/$period/$monitoring_category/?" . 
                   "date=$date&variable=temperature&centers=$centers&baseline=$baseline";
        } else {
            $url = "https://wdqms.wmo.int/wdqmsapi/v1/download/nwp/temp/$period/$monitoring_category/?" . 
                   "date=$date&period=$time_period&centers=$centers&baseline=$baseline";
        }
    }

    $log_message .= "API URL: $url\n";
    file_put_contents($log_file, $log_message, FILE_APPEND);

    // Initialize cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    // Execute request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    // Log response info
    $log_message = date('Y-m-d H:i:s') . " - Response received\n";
    $log_message .= "HTTP Code: $httpCode\n";
    if ($error) {
        $log_message .= "CURL Error: $error\n";
    }
    $log_message .= "Response length: " . strlen($response) . " bytes\n";
    $log_message .= "Response (first 2000 chars): " . substr($response, 0, 2000) . "\n";
    file_put_contents($log_file, $log_message, FILE_APPEND);

    // Process response
    if ($error) {
        $apiStatus = "cURL Error: " . $error;
        echo json_encode(['error' => true, 'message' => $apiStatus]);
    } elseif ($httpCode == 200) {
        $apiStatus = "Data berhasil diambil (HTTP $httpCode)";
        
        // Check if response is empty
        if (empty(trim($response))) {
            $log_message = date('Y-m-d H:i:s') . " - Empty Response\n";
            file_put_contents($log_file, $log_message, FILE_APPEND);
            
            // Return empty but valid response
            echo json_encode([
                'stations' => [],
                'metadata' => [
                    'total_stations' => 0,
                    'operational_count' => 0,
                    'issues_count' => 0,
                    'critical_count' => 0,
                    'not_received_count' => 0,
                    'territory' => $territory,
                    'territory_name' => $territory,
                    'monitoring_category' => $monitoring_category,
                    'baseline' => $baseline,
                    'centers' => $centers,
                    'variable' => $variable,
                    'period' => $period,
                    'time_period' => $time_period,
                    'api_url' => $url,
                    'http_code' => $httpCode,
                    'api_status' => 'Empty response from WMO API'
                ]
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        // Try to parse JSON response
        $jsonData = json_decode($response, true);
        $jsonError = json_last_error();
        
        // JSON parsing (silent unless error)

        // Handle different response formats
        if ($jsonError === JSON_ERROR_NONE && $jsonData) {
            // Check various possible data structures
            if (isset($jsonData['data'])) {
                $stationData = $jsonData['data'];
            } elseif (isset($jsonData['stations'])) {
                $stationData = $jsonData['stations'];
            } elseif (is_array($jsonData) && !empty($jsonData)) {
                $stationData = $jsonData;
            } else {
                $stationData = [];
            }

            // Process station data
            foreach ($stationData as $station) {
                // Calculate status based on availability
                $availability = isset($station['availability']) ? floatval($station['availability']) : 0;
                
                if ($availability >= 80) {
                    $status = 'operational';
                } elseif ($availability >= 50) {
                    $status = 'issues';
                } elseif ($availability > 0) {
                    $status = 'critical';
                } else {
                    $status = 'not_received';
                }
                
                $stations[] = [
                    'id' => $station['stationId'] ?? $station['id'] ?? uniqid(),
                    'name' => $station['stationName'] ?? $station['name'] ?? 'Unknown Station',
                    'latitude' => (float)($station['latitude'] ?? $station['lat'] ?? 0),
                    'longitude' => (float)($station['longitude'] ?? $station['lon'] ?? 0),
                    'elevation' => (int)($station['elevation'] ?? $station['height'] ?? 0),
                    'territory' => $territory,
                    'territoryCode' => $territory,
                    'stationTypeName' => 'TEMP',
                    'wigosId' => $station['wigosId'] ?? $station['wigos_id'] ?? 'N/A',
                    'stationStatusCode' => $status,
                    'dataCompleteness' => $availability,
                    'lastUpdated' => $date . ' ' . $time_period . ''
                ];
            }
        }
        
        // If no stations found from JSON, try CSV
        if (empty($stations)) {
            $lines = explode("\n", trim($response));
            
            if (count($lines) > 1) {
                $headers = str_getcsv($lines[0]);
                
                // Process all rows to collect data by station and center
                for ($i = 1; $i < count($lines); $i++) {
                    if (!empty(trim($lines[$i]))) {
                        $row = str_getcsv($lines[$i]);
                        if (count($row) >= count($headers)) {
                            $stationData = array_combine($headers, $row);
                            
                            // Skip if no wigosid
                            if (empty($stationData['wigosid'])) {
                                continue;
                            }
                            
                            // Filter by country code for Region V territories
                            $countryCode = trim($stationData['country code'] ?? $stationData['country'] ?? '');
                            if (!in_array($countryCode, $territories)) {
                                continue;
                            }
                            
                            $wigosId = $stationData['wigosid'];
                            $center = $stationData['center'] ?? 'Unknown';
                            
                            // Initialize station data if first time seeing this station
                            if (!isset($stationDataByWigosId[$wigosId])) {
                                // Initialize centers based on monitoring category
                                $centerInit = [];
                                if ($monitoring_category === 'timeliness') {
                                    // Hanya DWD dan ECMWF untuk timeliness
                                    $centerInit = [
                                        'DWD' => ['timeliness' => null, 'nr_negative_timeliness' => 0, 'status' => 'not_received'],
                                        'ECMWF' => ['timeliness' => null, 'nr_negative_timeliness' => 0, 'status' => 'not_received']
                                    ];
                                } elseif ($monitoring_category === 'quality') {
                                    // For alert period, add daily_values field
                                    if ($period === 'alert') {
                                        $centerInit = [
                                            'DWD' => ['received' => 0, 'avg_bg_dep' => 0, 'daily_values' => 0, 'status' => 'not_received'],
                                            'ECMWF' => ['received' => 0, 'avg_bg_dep' => 0, 'daily_values' => 0, 'status' => 'not_received'],
                                            'JMA' => ['received' => 0, 'avg_bg_dep' => 0, 'daily_values' => 0, 'status' => 'not_received'],
                                            'NCEP' => ['received' => 0, 'avg_bg_dep' => 0, 'daily_values' => 0, 'status' => 'not_received']
                                        ];
                                    } else {
                                        $centerInit = [
                                            'DWD' => ['received' => 0, 'avg_bg_dep' => 0, 'status' => 'not_received'],
                                            'ECMWF' => ['received' => 0, 'avg_bg_dep' => 0, 'status' => 'not_received'],
                                            'JMA' => ['received' => 0, 'avg_bg_dep' => 0, 'status' => 'not_received'],
                                            'NCEP' => ['received' => 0, 'avg_bg_dep' => 0, 'status' => 'not_received']
                                        ];
                                    }
                                } else {
                                    $centerInit = [
                                        'DWD' => ['received' => 0, 'expected' => 0, 'status' => 'not_received'],
                                        'ECMWF' => ['received' => 0, 'expected' => 0, 'status' => 'not_received'],
                                        'JMA' => ['received' => 0, 'expected' => 0, 'status' => 'not_received'],
                                        'NCEP' => ['received' => 0, 'expected' => 0, 'status' => 'not_received']
                                    ];
                                }
                                
                                $stationDataByWigosId[$wigosId] = [
                                    'name' => $stationData['name'] ?? 'Unknown Station',
                                    'wigosId' => $wigosId,
                                    'countryCode' => $stationData['country code'] ?? $territory,
                                    'inOSCAR' => $stationData['in OSCAR'] ?? 'False',
                                    'latitude' => (float)($stationData['latitude'] ?? 0),
                                    'longitude' => (float)($stationData['longitude'] ?? 0),
                                    'territory' => $territory,
                                    'territoryCode' => $territory,
                                    'stationTypeName' => 'TEMP',
                                    'variable' => $stationData['variable'] ?? $variable,
                                    'date' => $stationData['date'] ?? $date,
                                    'centers' => $centerInit,
                                    'lastUpdated' => $date . ' ' . $time_period . ''
                                ];
                            }
                            
                            // Add center-specific data
                            if (isset($stationDataByWigosId[$wigosId]['centers'][$center])) {
                                $received = intval($stationData['#received'] ?? 0);
                                $color_code = strtolower($stationData['color code'] ?? 'black');
                                
                                // Handle different monitoring categories
                                $status = 'not_received';
                                $centerValue = 0; // For storing either expected or avg_bg_dep
                                
                                if ($monitoring_category === 'timeliness') {
                                    // For timeliness monitoring
                                    $timeliness = floatval($stationData['timeliness'] ?? $stationData['timeliness (sec)'] ?? 0);
                                    $nr_negative_timeliness = intval($stationData['nr_negative_timeliness'] ?? 0);
                                    
                                    // Status determination based on timeliness (seconds)
                                    // Negative = late, Positive = early/on-time
                                    if ($timeliness >= 3600) {
                                        $status = 'operational';    // ≥ 1 hour early
                                    } elseif ($timeliness >= 0) {
                                        $status = 'issues';         // 0 to 1 hour early
                                    } elseif ($timeliness >= -1800) {
                                        $status = 'critical';       // 0 to 30 min late
                                    } elseif ($timeliness >= -3600) {
                                        $status = 'not_received';   // 30 min to 1 hour late
                                    } else {
                                        $status = 'offline';        // > 1 hour late
                                    }
                                    
                                    $stationDataByWigosId[$wigosId]['centers'][$center] = [
                                        'timeliness' => $timeliness,
                                        'nr_negative_timeliness' => $nr_negative_timeliness,
                                        'status' => $status,
                                        'description' => $stationData['description'] ?? 'Timeliness monitoring data',
                                        'monitoring_type' => 'timeliness'
                                    ];
                                } elseif ($monitoring_category === 'quality') {
                                    // For quality monitoring, check if monthly period (use RMS) or six-hour/daily (use avg_bg_dep) or alert (use avg_bg_dep with daily values)
                                    if ($period === 'monthly') {
                                        // MONTHLY: Gunakan RMS_bg_dep
                                        $rms_bg_dep = floatval($stationData['rms_bg_dep'] ?? $stationData['RMS_bg_dep'] ?? $stationData['rms'] ?? 0);
                                        $avg_bg_dep = floatval($stationData['avg_bg_dep'] ?? $stationData['Avg_bg_dep'] ?? 0); // Keep as fallback
                                        $centerValue = $rms_bg_dep > 0 ? $rms_bg_dep : $avg_bg_dep;
                                        
                                        // Quality status determination for monthly RMS (absolute hPa)
                                        if ($centerValue <= 0.5) {
                                            $status = 'operational';
                                        } elseif ($centerValue <= 1.0) {
                                            $status = 'issues';
                                        } elseif ($centerValue <= 5.0) {
                                            $status = 'more-than-100';
                                        } elseif ($centerValue <= 10.0) {
                                            $status = 'critical';
                                        } elseif ($centerValue > 10.0) {
                                            $status = 'not_received';
                                        } else {
                                            $status = 'no-match';
                                        }
                                        
                                        $stationDataByWigosId[$wigosId]['centers'][$center] = [
                                            'received' => $received,
                                            'rms_bg_dep' => $rms_bg_dep,
                                            'avg_bg_dep' => $avg_bg_dep, // Keep for fallback
                                            'status' => $status,
                                            'color_code' => $color_code,
                                            'description' => $stationData['description'] ?? '',
                                            'monitoring_type' => 'quality_monthly'
                                        ];
                                    } elseif ($period === 'alert') {
                                        // ALERT: Gunakan avg_bg_dep dan #daily values
                                        $avg_bg_dep = floatval($stationData['avg_bg_dep'] ?? $stationData['Avg_bg_dep'] ?? 0);
                                        // Try different possible field names for daily values
                                        $daily_values = intval($stationData['#daily values'] ?? $stationData['daily values'] ?? $stationData['#daily_values'] ?? 0);
                                        $centerValue = $avg_bg_dep;
                                        
                                        // Status determination
                                        
                                        // Quality status determination based on avg_bg_dep (5-day moving average)
                                        // Color based on avg_bg_dep value, regardless of daily_values
                                        if ($avg_bg_dep <= 0.5) {
                                            $status = 'operational';    // ≤ 0.5 hPa (Dark Green)
                                        } elseif ($avg_bg_dep <= 1.0) {
                                            $status = 'issues';         // 0.5 < x ≤ 1 hPa (Light Green)
                                        } elseif ($avg_bg_dep <= 5.0) {
                                            $status = 'more-than-100';  // 1 < x ≤ 5 hPa (Yellow)
                                        } elseif ($avg_bg_dep <= 10.0) {
                                            $status = 'critical';       // 5 < x ≤ 10 hPa (Orange)
                                        } elseif ($avg_bg_dep > 10.0) {
                                            $status = 'not_received';   // > 10 hPa (Red)
                                        } else {
                                            $status = 'no-match';       // No quality data available
                                        }
                                        
                                        $stationDataByWigosId[$wigosId]['centers'][$center] = [
                                            'received' => $received,
                                            'avg_bg_dep' => $avg_bg_dep,
                                            'daily_values' => $daily_values,
                                            'status' => $status,
                                            'color_code' => $color_code,
                                            'description' => $stationData['description'] ?? '',
                                            'monitoring_type' => 'quality_alert'
                                        ];
                                    } else {
                                        // SIX-HOUR/DAILY: Gunakan avg_bg_dep
                                        $avg_bg_dep = floatval($stationData['avg_bg_dep'] ?? $stationData['Avg_bg_dep'] ?? 0);
                                        $centerValue = $avg_bg_dep;
                                        
                                        // Quality status determination based on avg_bg_dep values (absolute hPa)
                                        if ($avg_bg_dep <= 0.5) {
                                            $status = 'operational';    // ≤ 0.5 hPa (Green)
                                        } elseif ($avg_bg_dep <= 1.0) {
                                            $status = 'issues';         // 0.5 < x ≤ 1 hPa (Orange)
                                        } elseif ($avg_bg_dep <= 5.0) {
                                            $status = 'more-than-100';  // 1 < x ≤ 5 hPa (Pink)
                                        } elseif ($avg_bg_dep <= 10.0) {
                                            $status = 'critical';       // 5 < x ≤ 10 hPa (Red)
                                        } elseif ($avg_bg_dep > 10.0) {
                                            $status = 'not_received';   // > 10 hPa (Black)
                                        } else {
                                            $status = 'no-match';       // No quality data available
                                        }
                                        
                                        $stationDataByWigosId[$wigosId]['centers'][$center] = [
                                            'received' => $received,
                                            'avg_bg_dep' => $avg_bg_dep,
                                            'status' => $status,
                                            'color_code' => $color_code,
                                            'description' => $stationData['description'] ?? '',
                                            'monitoring_type' => 'quality'
                                        ];
                                    }
                                } else {
                                    // For availability monitoring, use expected parameter (original logic)
                                    $expected = intval($stationData['#expected'] ?? $stationData['#expected (NWP)'] ?? 0);
                                    $centerValue = $expected;
                                    
                                    if ($expected == 0) {
                                        if ($received > 0) {
                                            $status = 'more-than-100';  // More than expected
                                        } else {
                                            $status = 'oscar-issue';    // OSCAR schedule issue
                                        }
                                    } else {
                                        $percentage = ($received / $expected) * 100;
                                        
                                        if ($percentage > 100) {
                                            $status = 'more-than-100';  // More than 100%
                                        } elseif ($percentage >= 80) {
                                            $status = 'operational';    // Normal (≥80%)
                                        } elseif ($percentage >= 30) {
                                            $status = 'issues';         // Availability issues (≥30%)
                                        } elseif ($percentage > 0) {
                                            $status = 'critical';       // Availability issues (< 30%)
                                        } else {
                                            $status = 'not-received';   // Not received in period
                                        }
                                    }
                                    
                                    $stationDataByWigosId[$wigosId]['centers'][$center] = [
                                        'received' => $received,
                                        'expected' => $expected,
                                        'status' => $status,
                                        'color_code' => $color_code,
                                        'description' => $stationData['description'] ?? '',
                                        'monitoring_type' => 'availability'
                                    ];
                                }
                            }
                        }
                    }
                }
                
                // Convert collected data to stations array
                foreach ($stationDataByWigosId as $wigosId => $stationData) {
                    // Handle different monitoring categories for overall calculation
                    if ($monitoring_category === 'quality') {
                        // For quality monitoring, calculate average bg_dep across centers
                        $total_bg_dep = 0;
                        $valid_centers = 0;
                        
                        foreach ($stationData['centers'] as $center => $centerData) {
                            if (isset($centerData['avg_bg_dep']) && $centerData['avg_bg_dep'] > 0) {
                                $total_bg_dep += $centerData['avg_bg_dep'];
                                $valid_centers++;
                            }
                        }
                        
                        $avg_quality = $valid_centers > 0 ? ($total_bg_dep / $valid_centers) : 0;
                        
                        // Determine overall status based on average quality
                        $status = 'not_received';
                        $inOSCAR = $stationData['inOSCAR'] ?? 'No';
                        
                        if ($inOSCAR === 'No' || $inOSCAR === 'False' || strtolower($inOSCAR) === 'no') {
                            $status = 'no-match';           // No match in OSCAR/Surface
                        } elseif ($valid_centers == 0) {
                            $status = 'oscar-issue';        // No quality data available
                        } elseif ($avg_quality <= 1.0) {
                            $status = 'operational';        // Excellent quality (≤ 1.0)
                        } elseif ($avg_quality <= 2.0) {
                            $status = 'issues';             // Good quality (≤ 2.0)
                        } elseif ($avg_quality <= 3.0) {
                            $status = 'critical';           // Fair quality (≤ 3.0)
                        } else {
                            $status = 'not_received';       // Poor quality (> 3.0)
                        }
                    } else {
                        // For availability monitoring (original logic)
                        // Step 1: Check if ANY center has received > expected
                        $hasMoreThan100 = false;
                        foreach ($stationData['centers'] as $center => $centerData) {
                            if (isset($centerData['expected']) && $centerData['expected'] > 0 && $centerData['received'] > $centerData['expected']) {
                                $hasMoreThan100 = true;
                                break;
                            }
                        }
                        
                        // Step 2: Calculate overall stats
                        $total_received = 0;
                        $total_expected = 0;
                        
                        foreach ($stationData['centers'] as $center => $centerData) {
                            if (isset($centerData['received'])) $total_received += $centerData['received'];
                            if (isset($centerData['expected'])) $total_expected += $centerData['expected'];
                        }
                        
                        // Calculate overall availability
                        $availability = $total_expected > 0 ? ($total_received / $total_expected) * 100 : 0;
                        
                        // Determine overall status based on NWP observations
                        $status = 'not_received';
                        
                        // Check for special OSCAR cases first
                        $inOSCAR = $stationData['inOSCAR'] ?? 'No';
                        if ($inOSCAR === 'No' || $inOSCAR === 'False' || strtolower($inOSCAR) === 'no') {
                            $status = 'no-match';           // No match in OSCAR/Surface
                        } elseif ($total_expected <= 0) {
                            $status = 'oscar-issue';        // OSCAR schedule issue
                        } elseif ($hasMoreThan100) {
                            $status = 'more-than-100';      // Any center has more than 100%
                        } elseif ($availability >= 80) {
                            $status = 'operational';        // Normal (≥80%)
                        } elseif ($availability >= 30) {
                            $status = 'issues';             // Availability issues (≥30%)
                        } elseif ($availability > 0) {
                            $status = 'critical';           // Availability issues (< 30%)
                        } else {
                            $status = 'not_received';       // Not received in period
                        }
                    }
                    
                    // Determine overall color code based on status
                    $color_code = 'black';
                    switch ($status) {
                        case 'more-than-100':
                            $color_code = 'pink';
                            break;
                        case 'operational':
                            $color_code = 'green';
                            break;
                        case 'issues':
                            $color_code = 'orange';
                            break;
                        case 'critical':
                            $color_code = 'red';
                            break;
                        case 'oscar-issue':
                            $color_code = 'gray';
                            break;
                        case 'no-match':
                            $color_code = 'yellow';
                            break;
                        default:
                            $color_code = 'black';
                            break;
                    }
                    
                    // Create status description for summary
                    $statusDescription = '';
                    switch ($status) {
                        case 'more-than-100':
                            $statusDescription = 'More than 100%';
                            break;
                        case 'operational':
                            $statusDescription = 'Normal (≥ 80%)';
                            break;
                        case 'issues':
                            $statusDescription = 'Availability Issues (≥ 30%)';
                            break;
                        case 'critical':
                            $statusDescription = 'Availability Issues (< 30%)';
                            break;
                        case 'not_received':
                            $statusDescription = 'Not received in period';
                            break;
                        case 'oscar-issue':
                            $statusDescription = 'OSCAR schedule issue';
                            break;
                        case 'no-match':
                            $statusDescription = 'No match in OSCAR/Surface';
                            break;
                    }
                    
                    // Create summary in format: date_country_station_status_system
                    $summary = $stationData['date'] . '_' . $stationData['countryCode'] . '_' . 
                              str_replace([' ', ',', '.', '(', ')', '/'], '_', $stationData['name']) . '_' . 
                              $statusDescription . '_NWP ' . ucfirst($monitoring_category) . ' Surfaceland';

                    // Create station entry based on monitoring category
                    $station = [
                        'id' => $wigosId,
                        'name' => $stationData['name'],
                        'wigosId' => $wigosId,
                        'countryCode' => $stationData['countryCode'],
                        'inOSCAR' => $stationData['inOSCAR'],
                        'latitude' => $stationData['latitude'],
                        'longitude' => $stationData['longitude'],
                        'territory' => $stationData['territory'],
                        'territoryCode' => $stationData['territoryCode'],
                        'stationTypeName' => $stationData['stationTypeName'],
                        'stationStatusCode' => $status,
                        'variable' => $stationData['variable'],
                        'date' => $stationData['date'],
                        'lastUpdated' => $stationData['lastUpdated'],
                        'summary' => $summary,
                        'colorCode' => $color_code,
                        'monitoring_category' => $monitoring_category,
                        'centers' => $stationData['centers']
                    ];
                    
                    if ($monitoring_category === 'quality') {
                        // Add quality-specific fields
                        $station['avg_quality'] = $avg_quality ?? 0;
                        $station['dataCompleteness'] = round($avg_quality, 2); // For consistency with frontend
                    } else {
                        // Add availability-specific fields
                        $station['dataCompleteness'] = $availability;
                        $station['received'] = $total_received;
                        $station['expected'] = $total_expected;
                        $station['DWD'] = $stationData['centers']['DWD']['received'] ?? 0;
                        $station['ECMWF'] = $stationData['centers']['ECMWF']['received'] ?? 0;
                        $station['JMA'] = $stationData['centers']['JMA']['received'] ?? 0;
                        $station['NCEP'] = $stationData['centers']['NCEP']['received'] ?? 0;
                    }
                    
                    $stations[] = $station;
                }
            }
        }
        
        // Merge USA stations with Region V stations
        if (!empty($usa_stations)) {
            $stations = array_merge($usa_stations, $stations);
        }
        
        // If still no stations after CSV parsing, log warning
        if (empty($stations)) {
            $log_message = date('Y-m-d H:i:s') . " - Warning: No stations parsed from response\n";
            $log_message .= "Response type: " . (is_array(json_decode($response, true)) ? "JSON" : "CSV or other") . "\n";
            file_put_contents($log_file, $log_message, FILE_APPEND);
        }

        // Count stations by status
        $total = count($stations);
        $operational = 0;
        $issues = 0;
        $critical = 0;
        $not_received = 0;

        foreach ($stations as $station) {
            switch ($station['stationStatusCode']) {
                case 'operational':
                    $operational++;
                    break;
                case 'issues':
                    $issues++;
                    break;
                case 'critical':
                    $critical++;
                    break;
                case 'not_received':
                    $not_received++;
                    break;
            }
        }

        // Log final station count
        $log_message = date('Y-m-d H:i:s') . " - Final Results\n";
        $log_message .= "Total stations: $total\n";
        $log_message .= "Operational: $operational\n";
        $log_message .= "Issues: $issues\n";
        $log_message .= "Critical: $critical\n";
        $log_message .= "Not received: $not_received\n";
        $log_message .= "StationDataByWigosId count: " . count($stationDataByWigosId) . "\n";
        file_put_contents($log_file, $log_message, FILE_APPEND);

        // Output success response
        echo json_encode([
            'stations' => $stations,
            'metadata' => [
                'total_stations' => $total,
                'operational_count' => $operational,
                'issues_count' => $issues,
                'critical_count' => $critical,
                'not_received_count' => $not_received,
                'territory' => $territory,
                'territory_name' => $territory,
                'monitoring_category' => $monitoring_category,
                'baseline' => $baseline,
                'centers' => $centers,
                'variable' => $variable,
                'period' => $period,
                'time_period' => $time_period,
                'api_url' => $url,
                'http_code' => $httpCode,
                'api_status' => $apiStatus
            ]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } else {
        $apiStatus = "HTTP Error: $httpCode";
        
        // Log error details
        $log_message = date('Y-m-d H:i:s') . " - Error Response\n";
        $log_message .= "Status: $apiStatus\n";
        $log_message .= "Response: " . substr($response, 0, 1000) . "\n";
        file_put_contents($log_file, $log_message, FILE_APPEND);

        echo json_encode([
            'error' => true, 
            'message' => $apiStatus,
            'debug_info' => [
                'url' => $url,
                'http_code' => $httpCode,
                'response' => substr($response, 0, 1000)
            ]
        ]);
    }
} catch (Exception $e) {
    // Log the error
    $error_message = date('Y-m-d H:i:s') . " - Uncaught Exception\n";
    $error_message .= "Message: " . $e->getMessage() . "\n";
    $error_message .= "File: " . $e->getFile() . "\n";
    $error_message .= "Line: " . $e->getLine() . "\n";
    file_put_contents($log_file, $error_message, FILE_APPEND);

    // Return a clean error response
    echo json_encode([
        'error' => true,
        'message' => 'An unexpected error occurred. Please try again later.'
    ]);
}
?> 