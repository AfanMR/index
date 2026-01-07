<?php
require_once '../../config/config.php';
$pageTitle = "NWP Observations - Surface Land Monitoring";
include '../../includes/header.php';
include '../../includes/navigation.php';
?>


<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>NWP Surface Land Monitoring</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.fullscreen/1.6.0/Control.FullScreen.css" />
<link rel="stylesheet" href="<?= asset('css/monitoring.css') ?>" />

<style>
/* Fullscreen control positioning */
.leaflet-control-fullscreen {
    margin-top: 10px !important;
}

.leaflet-control-fullscreen a {
    background: #fff;
    border-bottom: 1px solid #ccc;
    width: 30px;
    height: 30px;
    line-height: 30px;
    display: block;
    text-align: center;
    text-decoration: none;
    color: black;
    border-radius: 4px;
}

.leaflet-control-fullscreen a:hover {
    background-color: #f4f4f4;
}

/* Position below zoom controls */
.leaflet-control-fullscreen.leaflet-control {
    margin-top: 45px !important;
}

/* Fullscreen mode adjustments */
.leaflet-pseudo-fullscreen {
    position: fixed !important;
    width: 100% !important;
    height: 100% !important;
    top: 0 !important;
    left: 0 !important;
    z-index: 99999;
}

.leaflet-container:-webkit-full-screen {
    width: 100% !important;
    height: 100% !important;
}

.leaflet-container:-ms-fullscreen {
    width: 100% !important;
    height: 100% !important;
}

.leaflet-container:full-screen {
    width: 100% !important;
    height: 100% !important;
}

/* Map locked overlay */
.map-locked-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(255, 255, 255, 0.75);
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(2px);
}

.map-locked-message {
    text-align: center;
    padding: 30px 40px;
    background: rgba(255, 255, 255, 0.95);
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    max-width: 450px;
}

.map-locked-message i {
    display: block;
}

.map-locked-message p {
    line-height: 1.5;
}

/* Disabled select styling */
.select:disabled {
    background-color: #f3f4f6;
    color: #9ca3af;
    cursor: not-allowed;
    opacity: 0.6;
}
</style>
</head>


<div class="header">
<h1 class="title">
<i class="fas fa-satellite-dish"></i>
NWP Observations - Surface Land Monitoring
</h1>

<div class="controls">
<div class="control">
<label class="label"><i class="fas fa-clock"></i> Period Type</label>
<select class="select" id="periodType">
<option value="six-hour">Six_hour</option>
<option value="daily">Daily</option>
<option value="monthly">Monthly</option>
<option value="alert">Alert</option>
</select>
</div>

<div class="control">
<label class="label"><i class="fas fa-chart-line"></i> Monitoring Category</label>
<select class="select" id="monitoringCategory">
<option value="availability" selected>Availability</option>
<option value="quality">Quality</option>
<option value="timeliness">Timeliness</option>
</select>
</div>

<div class="control">
<label class="label"><i class="fas fa-thermometer-half"></i> Variable</label>
<select class="select" id="variableType">
<option value="pressure">Surface Pressure</option>
<option value="geopotential">Geopotential</option>
<option value="temperature">2m Temperature</option>
<option value="zonal_wind">10m Zonal Wind</option>
<option value="meridional_wind">10m Meridional Wind</option>
<option value="humidity">2m Relative Humidity</option>
</select>
</div>

<div class="control">
<label class="label"><i class="fas fa-server"></i> Monitoring Centre</label>
<select class="select" id="monitoringCentre">
<option value="DWD,ECMWF,JMA,NCEP" selected>All Centers</option>
<option value="DWD">DWD</option>
<option value="ECMWF">ECMWF</option>
<option value="JMA">JMA</option>
<option value="NCEP">NCEP</option>
</select>
</div>

<div class="control">
<label class="label"><i class="fas fa-globe-asia"></i> Region</label>
<select class="select" id="regionSelect">
<option value="ALL_COMBINED" selected>All Stations</option>
<optgroup label="Regional WMO V">
<option value="IDN">Indonesia (IDN)</option>
<option value="MYS">Malaysia (MYS)</option>
<option value="SGP">Singapore (SGP)</option>
<option value="PHL">Philippines (PHL)</option>
<option value="BRN">Brunei (BRN)</option>
<option value="TLS">Timor Leste (TLS)</option>
<option value="PNG">Papua New Guinea (PNG)</option>
</optgroup>
<optgroup label="Regional USA (PASIFIC)">
<option value="USA_PACIFIC">USA Stations (Pacific Region)</option>
</optgroup>
</select>
</div>

<div class="control">
<label class="label"><i class="fas fa-calendar-alt"></i> Date</label>
<input type="date" class="date-input" id="observationDate">
</div>

<div class="control" id="baselineControl">
<label class="label"><i class="fas fa-database"></i> Baseline</label>
<select class="select" id="baseline">
<option value="OSCAR" selected>OSCAR</option>
<option value="hourly">Hourly</option>
</select>
</div>

<div class="control">
<label class="label"><i class="fas fa-clock"></i> Six-hour period</label>
<div class="time-periods">
<button class="time-btn" data-period="00">00</button>
<button class="time-btn" data-period="06">06</button>
<button class="time-btn" data-period="12">12</button>
<button class="time-btn active" data-period="18">18</button>
</div>
</div>
</div>
</div>

<div class="map-container">
    <div class="loading" id="loading">
        <div class="spinner"></div>
        <div>Loading NWP stations...</div>
    </div>
    
    <div class="map-locked-overlay" id="mapLockedOverlay" style="display: none;">
        <div class="map-locked-message">
            <i class="fas fa-lock" style="font-size: 48px; color: #6b7280; margin-bottom: 20px;"></i>
            <p style="margin: 0; color: #1f2937; font-size: 18px; font-weight: 600;" id="mapLockedText">Timeliness is not yet available for this period.</p>
        </div>
    </div>

<div class="map-controls">
<button class="ctrl-btn" onclick="resetView()" title="Reset View">
<i class="fas fa-home"></i>
</button>
<button class="ctrl-btn" onclick="refresh()" title="Refresh">
<i class="fas fa-sync-alt"></i>
</button>
<button class="ctrl-btn" onclick="saveData()" title="Export Data">
<i class="fas fa-save"></i>
</button>
<button class="ctrl-btn" onclick="toggleLegends()" title="Toggle Legends">
<i class="fas fa-eye"></i>
</button>
</div>

<div id="map"></div>

<div class="panel legend">
<div class="panel-title">
<i class="fas fa-info-circle"></i>
<span id="observationTitle">Received Observations</span>
</div>
<div class="legend-item clickable availability-only" data-status="more-than-100">
<div class="dot more-than-100" style="background-color: #e91e63;"></div>
<span>More than 100%</span>
</div>
<div class="legend-item clickable availability-only" data-status="operational">
<div class="dot operational" style="background-color: #22c55e;"></div>
<span>Normal (≥ 80%)</span>
</div>
<div class="legend-item clickable availability-only" data-status="issues">
<div class="dot issues" style="background-color: #f97316;"></div>
<span>Availability Issues (≥ 30%)</span>
</div>
<div class="legend-item clickable availability-only" data-status="critical">
<div class="dot critical" style="background-color: #ef4444;"></div>
<span>Availability Issues (< 30%)</span>
</div>
<div class="legend-item clickable availability-only" data-status="not-received">
<div class="dot not-received" style="background-color: #1f2937;"></div>
<span>Not received in period</span>
</div>
<div class="legend-item clickable quality-only" data-status="offline" style="display: none;">
<div class="dot offline" style="background-color: #dc2626;"></div>
<span>> 10</span>
</div>
<div class="legend-item clickable quality-only" data-status="not_received" style="display: none;">
<div class="dot not_received" style="background-color: #f97316;"></div>
<span>5 < x ≤ 10</span>
</div>
<div class="legend-item clickable quality-only" data-status="critical" style="display: none;">
<div class="dot critical" style="background-color: #eab308;"></div>
<span>1 < x ≤ 5</span>
</div>
<div class="legend-item clickable quality-only" data-status="issues" style="display: none;">
<div class="dot issues" style="background-color: #22c55e;"></div>
<span>0.5 < x ≤ 1</span>
</div>
<div class="legend-item clickable quality-only" data-status="operational" style="display: none;">
<div class="dot operational" style="background-color: #166534;"></div>
<span>≤ 0.5</span>
</div>
<div class="legend-item quality-only" data-status="less-than-5" style="display: none; opacity: 0.6; cursor: default;">
<div class="dot less-than-5" style="background-color: #9ca3af;"></div>
<span>Less than 5 values</span>
</div>
<div class="legend-item clickable timeliness-only" data-status="lt-15" style="display: none;">
<div class="dot" style="background-color: #4A90E2;"></div>
<span>Less than 15 minutes</span>
</div>
<div class="legend-item clickable timeliness-only" data-status="15-30" style="display: none;">
<div class="dot" style="background-color: #87CEEB;"></div>
<span>Between 15 and 30 minutes</span>
</div>
<div class="legend-item clickable timeliness-only" data-status="30-120" style="display: none;">
<div class="dot" style="background-color: #FFA500;"></div>
<span>Between 30 and 120 minutes</span>
</div>
<div class="legend-item clickable timeliness-only" data-status="gt-120" style="display: none;">
<div class="dot" style="background-color: #FF6B6B;"></div>
<span>Greater than 120 minutes</span>
</div>
<div class="legend-item clickable availability-only" data-status="oscar-issue">
<div class="dot oscar-issue" style="background-color: #9ca3af;"></div>
<span>OSCAR schedule issue <i class="fas fa-info-circle" style="color: #6b7280;"></i></span>
</div>
<div class="legend-item clickable availability-only" data-status="no-match">
<div class="dot no-match" style="background-color: #eab308;"></div>
<span>No match in OSCAR/Surface <i class="fas fa-info-circle" style="color: #6b7280;"></i></span>
</div>
</div>

<div class="panel status">
<div class="panel-title">
<i class="fas fa-chart-bar"></i>
Statistics Observations
<span class="panel-subtitle" id="territoryStats"></span>
</div>
<div class="status-grid">
<div class="stat-item">
<span class="stat-count" id="totalStations">-</span>
<span class="stat-label">Total Stations</span>
</div>
<div class="stat-item">
<span class="stat-count" id="issuesReports">-</span>
<span class="stat-label">Issues</span>
<span class="stat-percent" id="issuesPercent">-%</span>
</div>
</div>
</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/leaflet.markercluster.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.fullscreen/1.6.0/Control.FullScreen.min.js"></script>
<script>
// Add this before including land_surface.js
document.getElementById('periodType').addEventListener('change', function() {
    const title = document.getElementById('observationTitle');
    const value = this.value;
    const monitoringCategorySelect = document.getElementById('monitoringCategory');
    
    // If alert is selected, force quality monitoring and disable the dropdown
    if (value === 'alert') {
        monitoringCategorySelect.value = 'quality';
        monitoringCategorySelect.disabled = true;
        
        // Trigger the change event to update other components
        monitoringCategorySelect.dispatchEvent(new Event('change'));
    } else {
        // Re-enable monitoring category selection for other period types
        monitoringCategorySelect.disabled = false;
    }
    
    const monitoringCategory = monitoringCategorySelect.value;
    
    // Update title and legend when period type changes
    // The updateLegendForMonitoringCategory function will handle title updates based on variable
    if (typeof updateLegendForMonitoringCategory === 'function') {
        updateLegendForMonitoringCategory();
    }
    
    // Set alert message based on period type
    let alertMessage = '';
    if (value === 'six-hour') {
        alertMessage = 'Six-hour period selected. Please select a time period (00, 06, 12, or 18).';
    } else if (value === 'daily') {
        alertMessage = 'Daily period selected. Data will be aggregated for the entire day.';
    } else if (value === 'monthly') {
        alertMessage = 'Monthly period selected. Data will be aggregated for the entire month.';
    } else if (value === 'alert') {
        alertMessage = 'Alert period selected. Monitoring category automatically set to Quality.';
    }
    
    if (alertMessage && typeof showAlert === 'function') {
        showAlert('info', alertMessage);
    }
});
</script>
<script src="<?= asset('js/config.js') ?>"></script>
<script src="<?= asset('js/territories_center.js') ?>"></script>
<script src="<?= asset('js/territories.js') ?>"></script>
<script src="<?= asset('js/map.js') ?>"></script>
<script src="<?= asset('js/utility.js') ?>"></script>
<script src="<?= asset('js/nwp_land_surface.js') ?>"></script>

<script>
// Toggle baseline control visibility based on monitoring category
function toggleBaselineControl() {
    const monitoringCategory = document.getElementById('monitoringCategory').value;
    const baselineControl = document.getElementById('baselineControl');
    
    if (monitoringCategory === 'quality' || monitoringCategory === 'timeliness') {
        baselineControl.style.display = 'none';
    } else {
        baselineControl.style.display = 'block';
    }
}

// Toggle geopotential variable visibility based on monitoring category
function toggleGeopotentialVariable() {
    const monitoringCategory = document.getElementById('monitoringCategory').value;
    const variableSelect = document.getElementById('variableType');
    const geopotentialOption = variableSelect.querySelector('option[value="geopotential"]');
    
    if (monitoringCategory === 'quality') {
        // Show geopotential for quality monitoring
        if (geopotentialOption) {
            geopotentialOption.style.display = 'block';
        }
    } else {
        // Hide geopotential for other monitoring categories
        if (geopotentialOption) {
            geopotentialOption.style.display = 'none';
        }
        
        // If geopotential is currently selected, change to pressure
        if (variableSelect.value === 'geopotential') {
            variableSelect.value = 'pressure';
            // Trigger change event to reload data
            variableSelect.dispatchEvent(new Event('change'));
        }
    }
}

// Check and enforce alert period restrictions
function enforceAlertPeriodRestrictions() {
    const periodType = document.getElementById('periodType').value;
    const monitoringCategorySelect = document.getElementById('monitoringCategory');
    
    if (periodType === 'alert') {
        monitoringCategorySelect.value = 'quality';
        monitoringCategorySelect.disabled = true;
    } else {
        monitoringCategorySelect.disabled = false;
    }
}

// Initialize monitoring category legend on page load
document.addEventListener('DOMContentLoaded', function() {
    // Enforce alert period restrictions on page load
    enforceAlertPeriodRestrictions();
    
    // Make sure the function is available before calling
    if (typeof updateLegendForMonitoringCategory === 'function') {
        updateLegendForMonitoringCategory();
    }
    
    // Initialize baseline control visibility
    toggleBaselineControl();
    
    // Initialize geopotential variable visibility
    toggleGeopotentialVariable();
    
    // Add event listener for monitoring category changes
    const monitoringCategorySelect = document.getElementById('monitoringCategory');
    if (monitoringCategorySelect) {
        monitoringCategorySelect.addEventListener('change', function() {
            toggleBaselineControl();
            toggleGeopotentialVariable();
            if (typeof updateLegendForMonitoringCategory === 'function') {
                updateLegendForMonitoringCategory();
            }
        });
    }
});
</script>

<?php include '../../includes/footer.php'; ?> 