// Valid parameters
const VALID_VARIABLES = {
    'pressure': 'Pressure',
    'geopotential': 'Geopotential',
    'temperature': 'Temperature',
    'zonal_wind': 'Zonal Wind',
    'meridional_wind': 'Meridional Wind',
    'humidity': 'Humidity'
};

const VALID_PERIODS = {
    'six_hour': '6 Hours',
    'daily': 'Daily',
    'monthly': 'Monthly',
    'alert': 'Alert'
};

const VALID_TIME_PERIODS = {
    '00': '00:00',
    '06': '06:00',
    '12': '12:00',
    '18': '18:00'
};

// Get quality thresholds and legend configuration based on variable
function getQualityConfig(variable) {
    const configs = {
        'pressure': {
            unit: 'hPa',
            title: 'Observation and model differences<br><small>Absolute values (hPa)</small>',
            ranges: [
                { status: 'offline', label: '> 10', color: '#dc2626', min: 10, max: Infinity },
                { status: 'not_received', label: '5 < x ≤ 10', color: '#f97316', min: 5, max: 10 },
                { status: 'critical', label: '1 < x ≤ 5', color: '#eab308', min: 1, max: 5 },
                { status: 'issues', label: '0.5 < x ≤ 1', color: '#22c55e', min: 0.5, max: 1 },
                { status: 'operational', label: '≤ 0.5', color: '#166534', min: 0, max: 0.5 }
            ]
        },
        'geopotential': {
            unit: 'm',
            title: 'Observation and model differences<br><small>Absolute values (m)</small>',
            ranges: [
                { status: 'offline', label: '> 100', color: '#dc2626', min: 100, max: Infinity },
                { status: 'not_received', label: '40 < x ≤ 100', color: '#f97316', min: 40, max: 100 },
                { status: 'critical', label: '30 < x ≤ 40', color: '#eab308', min: 30, max: 40 },
                { status: 'operational', label: '≤ 30', color: '#166534', min: 0, max: 30 }
            ]
        },
        'temperature': {
            unit: 'K',
            title: 'Observation and model differences<br><small>Absolute values (K)</small>',
            ranges: [
                { status: 'offline', label: '> 10', color: '#dc2626', min: 10, max: Infinity },
                { status: 'not_received', label: '5 < x ≤ 10', color: '#f97316', min: 5, max: 10 },
                { status: 'critical', label: '2 < x ≤ 5', color: '#eab308', min: 2, max: 5 },
                { status: 'issues-high', label: '1 < x ≤ 2', color: '#84cc16', min: 1, max: 2 },
                { status: 'issues', label: '0.5 < x ≤ 1', color: '#22c55e', min: 0.5, max: 1 },
                { status: 'operational', label: '≤ 0.5', color: '#166534', min: 0, max: 0.5 }
            ]
        },
        'zonal_wind': {
            unit: 'm/s',
            title: 'Observation and model differences<br><small>Absolute values (m/s)</small>',
            ranges: [
                { status: 'offline', label: '> 15', color: '#dc2626', min: 15, max: Infinity },
                { status: 'not_received', label: '5 < x ≤ 15', color: '#f97316', min: 5, max: 15 },
                { status: 'critical', label: '3 < x ≤ 5', color: '#eab308', min: 3, max: 5 },
                { status: 'issues-high', label: '2 < x ≤ 3', color: '#84cc16', min: 2, max: 3 },
                { status: 'issues', label: '0.5 < x ≤ 2', color: '#22c55e', min: 0.5, max: 2 },
                { status: 'operational', label: '≤ 0.5', color: '#166534', min: 0, max: 0.5 }
            ]
        },
        'meridional_wind': {
            unit: 'm/s',
            title: 'Observation and model differences<br><small>Absolute values (m/s)</small>',
            ranges: [
                { status: 'offline', label: '> 15', color: '#dc2626', min: 15, max: Infinity },
                { status: 'not_received', label: '5 < x ≤ 15', color: '#f97316', min: 5, max: 15 },
                { status: 'critical', label: '3 < x ≤ 5', color: '#eab308', min: 3, max: 5 },
                { status: 'issues-high', label: '2 < x ≤ 3', color: '#84cc16', min: 2, max: 3 },
                { status: 'issues', label: '0.5 < x ≤ 2', color: '#22c55e', min: 0.5, max: 2 },
                { status: 'operational', label: '≤ 0.5', color: '#166534', min: 0, max: 0.5 }
            ]
        },
        'humidity': {
            unit: '%',
            title: 'Observation and model differences<br><small>Absolute values (%)</small>',
            ranges: [
                { status: 'offline', label: '> 30', color: '#dc2626', min: 30, max: Infinity },
                { status: 'not_received', label: '15 < x ≤ 30', color: '#f97316', min: 15, max: 30 },
                { status: 'critical', label: '10 < x ≤ 15', color: '#eab308', min: 10, max: 15 },
                { status: 'issues-high', label: '5 < x ≤ 10', color: '#84cc16', min: 5, max: 10 },
                { status: 'issues', label: '2 < x ≤ 5', color: '#22c55e', min: 2, max: 5 },
                { status: 'operational', label: '≤ 2', color: '#166534', min: 0, max: 2 }
            ]
        }
    };
    
    return configs[variable] || configs['pressure']; // Default to pressure if variable not found
}

// Lock/Unlock map functions
function lockMap(message = 'Timeliness is not yet available for this period.') {
    const overlay = document.getElementById('mapLockedOverlay');
    const messageText = document.getElementById('mapLockedText');
    
    if (overlay) {
        if (messageText) {
            messageText.textContent = message;
        }
        overlay.style.display = 'flex';
        
        // Disable map interactions
        if (map) {
            map.dragging.disable();
            map.touchZoom.disable();
            map.doubleClickZoom.disable();
            map.scrollWheelZoom.disable();
            map.boxZoom.disable();
            map.keyboard.disable();
            if (map.tap) map.tap.disable();
        }
    }
}

function unlockMap() {
    const overlay = document.getElementById('mapLockedOverlay');
    
    if (overlay) {
        overlay.style.display = 'none';
        
        // Re-enable map interactions
        if (map) {
            map.dragging.enable();
            map.touchZoom.enable();
            map.doubleClickZoom.enable();
            map.scrollWheelZoom.enable();
            map.boxZoom.enable();
            map.keyboard.enable();
            if (map.tap) map.tap.enable();
        }
    }
}

// Check if current configuration should lock the map
function shouldLockMap() {
    const monitoringCategory = document.getElementById('monitoringCategory').value;
    const periodType = document.getElementById('periodType').value;
    
    // Lock map for timeliness + monthly period
    return monitoringCategory === 'timeliness' && periodType === 'monthly';
}

// Initialize control event listeners
function initializeControls() {
    // Initialize legend controls
    initializeLegendControls();
    
    // Initialize status filters
    initializeStatusFilters();
    
    // Region selector
    document.getElementById('regionSelect').addEventListener('change', function() {
        const territory = this.value;
        updateMapCenterForTerritory(territory);
        loadStationData();
    });

    // Period type selector
    const periodTypeSelect = document.getElementById('periodType');
    if (periodTypeSelect) {
        periodTypeSelect.addEventListener('change', function() {
            const value = this.value;
            const dateInput = document.getElementById('observationDate');
            
            // Show alert based on selected period
            let alertMessage = '';
            if (value === 'six-hour') {
                alertMessage = 'Six-hour period selected. Please select a time period (00, 06, 12, or 18).';
            } else if (value === 'daily') {
                alertMessage = 'Daily period selected. Data will be aggregated for the entire day.';
            } else if (value === 'monthly') {
                alertMessage = 'Monthly period selected. Data will be aggregated for the entire month.';
            } else if (value === 'alert') {
                alertMessage = 'Alert period selected. Showing alert-based observations.';
            }
            
            if (alertMessage) {
                showAlert('info', alertMessage);
            }
            
            // Update legend title if in quality mode
            const monitoringCategory = document.getElementById('monitoringCategory').value;
            if (monitoringCategory === 'quality') {
                updateLegendForMonitoringCategory();
            }
            
            // Show/hide time period buttons based on selected period
            const timePeriodContainer = document.querySelector('.time-periods').parentElement;
            if (timePeriodContainer) {
                timePeriodContainer.style.display = value === 'six-hour' ? 'block' : 'none';
            }
            // Change date input type and value based on period
            if (value === 'monthly') {
                dateInput.type = 'month';
                // Set date to previous month
                const previousMonth = new Date();
                previousMonth.setMonth(previousMonth.getMonth() - 1);
                const monthStr = previousMonth.toISOString().split('T')[0].substring(0, 7);
                dateInput.value = monthStr;
            } else {
                dateInput.type = 'date';
                // Set date to 2 days ago
                const twoDaysAgo = new Date();
                twoDaysAgo.setDate(twoDaysAgo.getDate() - 2);
                const dateStr = twoDaysAgo.toISOString().split('T')[0];
                dateInput.value = dateStr;
            }
            loadStationData();
        });
    }

    // Time period buttons
    const timeButtons = document.querySelectorAll('.time-periods .time-btn');
    if (timeButtons.length > 0) {
        timeButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                timeButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                if (periodTypeSelect) {
                    periodTypeSelect.value = 'six-hour';
                }
                loadStationData();
            });
        });
    }

    // Variable type selector
    const variableSelect = document.getElementById('variableType');
    if (variableSelect) {
        variableSelect.addEventListener('change', function() {
            // Update legend if in quality mode
            const monitoringCategory = document.getElementById('monitoringCategory').value;
            if (monitoringCategory === 'quality') {
                updateLegendForMonitoringCategory();
            }
            loadStationData();
        });
    }

    // Monitoring category selector
    const monitoringCategorySelect = document.getElementById('monitoringCategory');
    if (monitoringCategorySelect) {
        monitoringCategorySelect.addEventListener('change', function() {
            updateLegendForMonitoringCategory();
            updateMonitoringCentreOptions();
            loadStationData();
        });
    }

    // Monitoring centre selector
    const monitoringCentreSelect = document.getElementById('monitoringCentre');
    if (monitoringCentreSelect) {
        monitoringCentreSelect.addEventListener('change', function() {
            loadStationData();
        });
    }

    // Baseline selector
    const baselineSelect = document.getElementById('baseline');
    if (baselineSelect) {
        baselineSelect.addEventListener('change', function() {
            loadStationData();
        });
    }

    // Date control
    document.getElementById('observationDate').addEventListener('change', loadStationData);
}

// Set default values
function initializeDefaults() {
    // Set default date based on period type
    const dateInput = document.getElementById('observationDate');
    const periodType = document.getElementById('periodType');
    
    // Always set period type to six-hour
    periodType.value = 'six-hour';
    
    // Show time period buttons
    const timePeriodContainer = document.querySelector('.time-periods').parentElement;
    if (timePeriodContainer) {
        timePeriodContainer.style.display = 'block';
    }
    
    // Set default date to 2 days ago
    dateInput.type = 'date';
    const twoDaysAgo = new Date();
    twoDaysAgo.setDate(twoDaysAgo.getDate() - 2);
    const dateStr = twoDaysAgo.toISOString().split('T')[0];
    dateInput.value = dateStr;
    
    // Set default region
    document.getElementById('regionSelect').value = DEFAULT_REGION;
    
    // Set default time period button (00)
    const timeButtons = document.querySelectorAll('.time-periods .time-btn');
    if (timeButtons.length > 0) {
        timeButtons.forEach(btn => btn.classList.remove('active'));
        const defaultTimeBtn = document.querySelector('.time-periods .time-btn[data-period="00"]');
        if (defaultTimeBtn) {
            defaultTimeBtn.classList.add('active');
        }
    }
    
    // Initialize legend for default monitoring category
    updateLegendForMonitoringCategory();
    
    // Initialize monitoring centre options based on category
    updateMonitoringCentreOptions();
}

// Check if API is accessible
async function checkApiAccess() {
    try {
        const territory = document.getElementById('regionSelect').value;
        const date = document.getElementById('observationDate').value;
        const period = document.querySelector('.time-btn.active')?.dataset.period || 'six_hour';
        const time_period = document.querySelector('.time-period-btn.active')?.dataset.time_period || '00';
        const variable = document.getElementById('variableType').value;
        const monitoringCategory = document.getElementById('monitoringCategory').value;
        const monitoringCentre = document.getElementById('monitoringCentre').value;
        const baseline = document.getElementById('baseline').value;
        
        const response = await fetch(`${API_ENDPOINT}land_surface_nwp.php?territory=${territory}&date=${date}&period=${period}&time_period=${time_period}&variable=${variable}&monitoring_category=${monitoringCategory}&centers=${monitoringCentre}&baseline=${baseline}`);
        return response.ok;
    } catch (error) {
        console.error('API access check failed:', error);
        return false;
    }
}

// Function to update URL parameters
function updateURLParameters(params) {
    const url = new URL(window.location.href);
    Object.entries(params).forEach(([key, value]) => {
        if (value) {
            url.searchParams.set(key, value);
        } else {
            url.searchParams.delete(key);
        }
    });
    window.history.replaceState({}, '', url);
}

// Function to get URL parameters
function getURLParameters() {
    const params = new URLSearchParams(window.location.search);
    return {
        territory: params.get('territory'),
        variable: params.get('variable'),
        date: params.get('date'),
        period: params.get('period'),
        time_period: params.get('time_period'),
        monitoring_category: params.get('monitoring_category'),
        centers: params.get('centers'),
        baseline: params.get('baseline')
    };
}

// Update legend based on monitoring category
function updateLegendForMonitoringCategory() {
    const monitoringCategory = document.getElementById('monitoringCategory').value;
    const periodType = document.getElementById('periodType').value;
    const variable = document.getElementById('variableType').value;
    const observationTitle = document.getElementById('observationTitle');
    const legendItems = document.querySelectorAll('.legend-item.clickable');
    const allLegendItems = document.querySelectorAll('.legend-item'); // Include non-clickable items
    
    if (monitoringCategory === 'timeliness') {
        // Update title for timeliness monitoring
        observationTitle.innerHTML = 'Timeliness<br><small>Reporting negative timeliness</small>';
        
        // Add quality mode class (reuse for timeliness)
        document.querySelector('.legend').classList.add('quality-mode');
        
        // Hide all availability and quality items, show only timeliness items
        allLegendItems.forEach(item => {
            if (item.classList.contains('timeliness-only')) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    } else if (monitoringCategory === 'quality') {
        // Get quality configuration for current variable
        const qualityConfig = getQualityConfig(variable);
        
        // Update title based on period type and variable
        if (periodType === 'monthly') {
            const unit = qualityConfig.unit;
            observationTitle.innerHTML = `Observation and model differences<br><small>Root Mean Square Error (${unit})</small>`;
        } else if (periodType === 'alert') {
            const unit = qualityConfig.unit;
            observationTitle.innerHTML = `Observation and model differences<br><small>5-day moving average<br>Absolute values (${unit})</small>`;
        } else {
            observationTitle.innerHTML = qualityConfig.title;
        }
        
        // Add quality mode class
        document.querySelector('.legend').classList.add('quality-mode');
        
        // Hide all standard legend items
        allLegendItems.forEach(item => {
            item.style.display = 'none';
        });
        
        // Create dynamic quality legend items
        const legendContainer = document.querySelector('.legend');
        const titleElement = legendContainer.querySelector('.panel-title');
        
        // Remove any existing dynamic quality items
        const existingDynamicItems = legendContainer.querySelectorAll('.legend-item.dynamic-quality');
        existingDynamicItems.forEach(item => item.remove());
        
        // Add new dynamic quality items based on variable configuration
        qualityConfig.ranges.forEach(range => {
            const legendItem = document.createElement('div');
            legendItem.className = 'legend-item clickable dynamic-quality quality-only';
            legendItem.setAttribute('data-status', range.status);
            legendItem.style.display = 'flex';
            
            legendItem.innerHTML = `
                <div class="dot ${range.status}" style="background-color: ${range.color};"></div>
                <span>${range.label}</span>
            `;
            
            // Insert after title
            titleElement.insertAdjacentElement('afterend', legendItem);
        });
        
        // Add "Less than 5 values" item at the end
        const lessThan5Item = document.createElement('div');
        lessThan5Item.className = 'legend-item dynamic-quality quality-only';
        lessThan5Item.setAttribute('data-status', 'less-than-5');
        lessThan5Item.style.display = 'flex';
        lessThan5Item.style.opacity = '0.6';
        lessThan5Item.style.cursor = 'default';
        
        lessThan5Item.innerHTML = `
            <div class="dot less-than-5" style="background-color: #9ca3af;"></div>
            <span>Less than 5 values</span>
        `;
        
        // Add it after all range items
        const lastRangeItem = legendContainer.querySelector('.legend-item.dynamic-quality:last-of-type');
        if (lastRangeItem) {
            lastRangeItem.insertAdjacentElement('afterend', lessThan5Item);
        }
        
        // Re-initialize legend controls for new items
        if (typeof initializeLegendControls === 'function') {
            initializeLegendControls();
        }
    } else {
        // Reset to availability monitoring
        const periodType = document.getElementById('periodType').value;
        if (periodType === 'monthly') {
            observationTitle.textContent = 'Monthly Received Observations';
        } else {
            observationTitle.textContent = 'Received Observations';
        }
        
        // Remove quality mode class
        document.querySelector('.legend').classList.remove('quality-mode');
        
        // Show availability items, hide quality-only and timeliness-only items
        allLegendItems.forEach(item => {
            if (item.classList.contains('quality-only') || item.classList.contains('timeliness-only')) {
                item.style.display = 'none';
            } else {
                item.style.display = 'flex';
            }
        });
    }
}

// Update Monitoring Centre dropdown options based on monitoring category
function updateMonitoringCentreOptions() {
    const monitoringCategory = document.getElementById('monitoringCategory').value;
    const monitoringCentreSelect = document.getElementById('monitoringCentre');
    
    if (!monitoringCentreSelect) return;
    
    // Store current value
    const currentValue = monitoringCentreSelect.value;
    
    if (monitoringCategory === 'timeliness') {
        // Untuk timeliness, hanya tampilkan DWD dan ECMWF
        monitoringCentreSelect.innerHTML = `
            <option value="DWD,ECMWF" selected>All Centers (DWD, ECMWF)</option>
            <option value="DWD">DWD</option>
            <option value="ECMWF">ECMWF</option>
        `;
        
        // Set to "All Centers" for timeliness (DWD,ECMWF)
        monitoringCentreSelect.value = 'DWD,ECMWF';
    } else {
        // Untuk availability dan quality, tampilkan semua centers
        monitoringCentreSelect.innerHTML = `
            <option value="DWD,ECMWF,JMA,NCEP" selected>All Centers</option>
            <option value="DWD">DWD</option>
            <option value="ECMWF">ECMWF</option>
            <option value="JMA">JMA</option>
            <option value="NCEP">NCEP</option>
        `;
        
        // Restore previous value if it was valid
        if (currentValue && monitoringCentreSelect.querySelector(`option[value="${currentValue}"]`)) {
            monitoringCentreSelect.value = currentValue;
        } else {
            monitoringCentreSelect.value = 'DWD,ECMWF,JMA,NCEP';
        }
    }
}

// Initialize controls from URL parameters
function initializeFromURL() {
    const params = getURLParameters();
    
    // Set region
    if (params.territory) {
        const regionSelect = document.getElementById('regionSelect');
        if (regionSelect && regionSelect.querySelector(`option[value="${params.territory}"]`)) {
            regionSelect.value = params.territory;
        }
    }
    
    // Set variable
    if (params.variable) {
        const variableSelect = document.getElementById('variableType');
        if (variableSelect && variableSelect.querySelector(`option[value="${params.variable}"]`)) {
            variableSelect.value = params.variable;
        }
    }
    
    // Set monitoring category
    if (params.monitoring_category) {
        const monitoringCategorySelect = document.getElementById('monitoringCategory');
        if (monitoringCategorySelect && monitoringCategorySelect.querySelector(`option[value="${params.monitoring_category}"]`)) {
            monitoringCategorySelect.value = params.monitoring_category;
        }
    }
    
    // Update legend for current monitoring category
    updateLegendForMonitoringCategory();
    
    // Update monitoring centre options based on category
    updateMonitoringCentreOptions();
    
    // Set monitoring centre
    if (params.centers) {
        const monitoringCentreSelect = document.getElementById('monitoringCentre');
        if (monitoringCentreSelect && monitoringCentreSelect.querySelector(`option[value="${params.centers}"]`)) {
            monitoringCentreSelect.value = params.centers;
        }
    }
    
    // Set baseline
    if (params.baseline) {
        const baselineSelect = document.getElementById('baseline');
        if (baselineSelect && baselineSelect.querySelector(`option[value="${params.baseline}"]`)) {
            baselineSelect.value = params.baseline;
        }
    }
    
    // Set date
    if (params.date) {
        const dateInput = document.getElementById('observationDate');
        if (dateInput) {
            dateInput.value = params.date;
        }
    }
    
    // Always set period type to six-hour and show time period buttons
    const periodSelect = document.getElementById('periodType');
    if (periodSelect) {
        periodSelect.value = 'six-hour';
        const timePeriodContainer = document.querySelector('.time-periods').parentElement;
        if (timePeriodContainer) {
            timePeriodContainer.style.display = 'block';
        }
    }
    
    // Set time period
    const timeButtons = document.querySelectorAll('.time-periods .time-btn');
    if (timeButtons.length > 0) {
        timeButtons.forEach(btn => btn.classList.remove('active'));
        const defaultTimeBtn = document.querySelector('.time-periods .time-btn[data-period="00"]');
        if (defaultTimeBtn) {
            defaultTimeBtn.classList.add('active');
        }
    }
}

// Update loadStationData to handle URL parameters
async function loadStationData() {
    if (isLoading) {
        return;
    }
    
    let territory = document.getElementById('regionSelect').value;
    const variable = document.getElementById('variableType').value;
    const date = document.getElementById('observationDate').value;
    const periodType = document.getElementById('periodType').value;
    const timePeriod = document.querySelector('.time-periods .time-btn.active')?.dataset.period;
    const monitoringCategory = document.getElementById('monitoringCategory').value;
    const monitoringCentre = document.getElementById('monitoringCentre').value;
    const baseline = document.getElementById('baseline').value;
    
    // Check if map should be locked (timeliness + monthly)
    if (shouldLockMap()) {
        // Lock the map and clear current stations
        lockMap('Timeliness is not yet available for this period.');
        currentStations = [];
        displayStations(currentStations);
        updateStatistics({ total_stations: 0, operational_count: 0, issues_count: 0, critical_count: 0, not_received_count: 0 });
        return;
    } else {
        // Unlock the map if it was locked
        unlockMap();
    }
    
    // Update URL parameters - exclude baseline for quality monitoring
    const urlParams = {
        territory: territory,
        variable: variable,
        date: date,
        period: periodType,
        time_period: periodType === 'six-hour' ? timePeriod : null,
        monitoring_category: monitoringCategory,
        centers: monitoringCentre
    };
    
    // Only include baseline parameter if not quality monitoring
    if (monitoringCategory !== 'quality') {
        urlParams.baseline = baseline;
    }
    
    updateURLParameters(urlParams);
    
    isLoading = true;
    showLoading(true);
    
    try {
        // Validate required parameters
        if (!territory || !variable || !date || !periodType) {
            throw new Error('Missing required parameters');
        }
        
        // For six-hour period, ensure we have a time period selected
        if (periodType === 'six-hour' && !timePeriod) {
            // If no time period is selected, select the default (00)
            const defaultTimeBtn = document.querySelector('.time-periods .time-btn[data-period="00"]');
            if (defaultTimeBtn) {
                defaultTimeBtn.classList.add('active');
            }
            throw new Error('Please select a time period (00, 06, 12, or 18)');
        }
        
        // Build the API URL
        // Convert period type from kebab-case to snake_case for API
        const apiPeriodType = periodType.replace('-', '_'); // 'six-hour' -> 'six_hour'
        
        let apiUrl;
        if (periodType === 'monthly') {
            // For monthly, format the date as YYYY-MM
            const monthDate = date.substring(0, 7);
            apiUrl = `${API_ENDPOINT}land_surface_nwp.php?territory=${territory}&variable=${variable}&date=${monthDate}&period=${apiPeriodType}&monitoring_category=${monitoringCategory}&centers=${monitoringCentre}&baseline=${baseline}`;
        } else {
            // Build API URL - exclude baseline parameter for quality monitoring
            if (monitoringCategory === 'quality') {
                apiUrl = `${API_ENDPOINT}land_surface_nwp.php?territory=${territory}&variable=${variable}&date=${date}&period=${apiPeriodType}&monitoring_category=${monitoringCategory}&centers=${monitoringCentre}`;
            } else {
                apiUrl = `${API_ENDPOINT}land_surface_nwp.php?territory=${territory}&variable=${variable}&date=${date}&period=${apiPeriodType}&monitoring_category=${monitoringCategory}&centers=${monitoringCentre}&baseline=${baseline}`;
            }
            // Only add time_period parameter for six-hour period
            if (periodType === 'six-hour' && timePeriod) {
                apiUrl += `&time_period=${timePeriod}`;
            }
        }
        
        const response = await fetch(apiUrl);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        let data;
        try {
            data = await response.json();
        } catch (jsonError) {
            console.error('JSON parsing error:', jsonError);
            throw new Error('Failed to parse API response as JSON. Please contact support.');
        }
        
        if (!data.stations || !Array.isArray(data.stations)) {
            throw new Error('Invalid data format received from API');
        }
        
        // Check if stations array is empty and we're in timeliness mode
        if (data.stations.length === 0 && monitoringCategory === 'timeliness') {
            lockMap('Timeliness is not yet available for this period.');
            currentStations = [];
            displayStations(currentStations);
            updateStatistics({ total_stations: 0, operational_count: 0, issues_count: 0, critical_count: 0, not_received_count: 0 });
            showLoading(false);
            isLoading = false;
            return;
        }
        
        currentStations = data.stations;
        displayStations(currentStations);
        updateStatistics(data.metadata);
        
        showLoading(false);
        isLoading = false;
        
    } catch (error) {
        console.error('Error loading station data:', error);
        showAlert('error', `Failed to load station data: ${error.message}`);
        showLoading(false);
        isLoading = false;
    }
}

function refresh() {
    loadStationData();
}

// Update station status visualization
function updateStationStatus() {
    const period = document.querySelector('.time-btn.active')?.dataset.period;
    const variable = document.getElementById('variableType').value;
    const date = document.getElementById('observationDate').value;

    // For major parameter changes, reload data from API
    if (document.getElementById('variableType').dataset.lastValue !== variable ||
        document.getElementById('observationDate').dataset.lastValue !== date) {
        
        // Store current values for comparison on next change
        document.getElementById('variableType').dataset.lastValue = variable;
        document.getElementById('observationDate').dataset.lastValue = date;
        
        // Reload data from API with updated parameters
        loadStationData();
        return;
    }
    
    // For minor changes (like time period), just update the visualization
    const monitoringCategory = document.getElementById('monitoringCategory').value;
    
    stationMarkers.forEach((marker, index) => {
        const station = currentStations[index];
        if (station) {
            // Always recalculate status using our corrected logic for ALL monitoring categories
            const newStatus = determineStationStatus(station);
            
            // Get color mapping based on monitoring category
            let colors;
            if (monitoringCategory === 'timeliness') {
                // Timeliness monitoring colors
                colors = {
                    'lt-15': '#4A90E2',          // Dark blue - Less than 15 minutes
                    '15-30': '#87CEEB',          // Light blue - Between 15 and 30 minutes
                    '30-120': '#FFA500',         // Orange - Between 30 and 120 minutes
                    'gt-120': '#FF6B6B'          // Red - Greater than 120 minutes
                };
            } else if (monitoringCategory === 'quality') {
                // HANYA 5 WARNA untuk quality monitoring (NO GRAY!)
                colors = {
                    'offline': '#dc2626',         // red - >10 hPa + No data
                    'not_received': '#f97316',    // orange - 5<x≤10 hPa
                    'critical': '#eab308',        // yellow - 1<x≤5 hPa
                    'issues': '#22c55e',          // light green - 0.5<x≤1 hPa
                    'operational': '#166534'      // dark green - ≤0.5 hPa
                };
            } else {
                colors = {
                    'more-than-100': '#e91e63',   // pink - More than 100%
                    'operational': '#22c55e',     // green - Normal (≥ 80%)
                    'issues': '#f97316',          // orange - Availability Issues (≥ 30%)
                    'critical': '#ef4444',        // red - Availability Issues (< 30%)
                    'not-received': '#1f2937',    // black - Not received in period
                    'oscar-issue': '#9ca3af',     // gray - OSCAR schedule issue
                    'no-match': '#eab308'         // yellow - No match in OSCAR/Surface
                };
            }
            
            const markerColor = colors[newStatus] || '#1f2937';
            marker.setStyle({ fillColor: markerColor });
            
            // Update popup content
            const popupContent = createPopupContent(station, newStatus);
            marker.getPopup()?.setContent(popupContent);
        }
    });

    updateStatistics();
}

// Update map center based on selected territory
function updateMapCenterForTerritory(territory) {
    // Get territory settings or use default
    const settings = territoryCenters[territory] || territoryCenters['IDN'];
    
    // Update map view - use bounds if available for better fit
    if (settings.bounds) {
        map.fitBounds(settings.bounds);
    } else {
        map.setView(settings.center, settings.zoom);
    }
}

// Calculate statistics from current stations
function calculateStatistics() {
    let total = 0;
    let issues = 0;
    
    const monitoringCategory = document.getElementById('monitoringCategory').value;

    // Count only visible markers
    stationMarkers.forEach(markerArray => {
        if (Array.isArray(markerArray)) {
            const station = currentStations[stationMarkers.indexOf(markerArray)];
            // Check if marker is visible (not hidden)
            if (station && markerArray[0] && map.hasLayer(markerArray[0])) {
                total++;
                
                // Always recalculate status using our corrected logic for ALL monitoring categories
                const status = determineStationStatus(station);
                
                // Determine if station has issues based on monitoring category
                if (monitoringCategory === 'timeliness') {
                    // For timeliness: only 'lt-15' is NOT an issue
                    if (status !== 'lt-15') {
                        issues++;
                    }
                } else if (monitoringCategory === 'quality') {
                    // For quality: only 'operational' is NOT an issue
                    if (status !== 'operational') {
                        issues++;
                    }
                } else {
                    // For availability: operational and more-than-100 are NOT issues
                    if (status !== 'operational' && status !== 'more-than-100') {
                        issues++;
                    }
                }
            }
        }
    });

    return { total, issues };
}

// Update statistics panel
function updateStatistics() {
    const stats = calculateStatistics();
    
    // Update the statistics in the UI
    document.getElementById('totalStations').textContent = stats.total;
    document.getElementById('issuesReports').textContent = stats.issues;
    
    // Update percentage if there are stations
    const issuesPercentElement = document.getElementById('issuesPercent');
    if (stats.total > 0) {
        const percentage = Math.round((stats.issues / stats.total) * 100);
        issuesPercentElement.textContent = `${percentage}%`;
    } else {
        issuesPercentElement.textContent = '0%';
    }
}

// Determine station status based on data completeness
function determineStationStatus(station) {
    const centers = ['DWD', 'ECMWF', 'JMA', 'NCEP'];
    const monitoringCategory = document.getElementById('monitoringCategory').value;
    const periodType = document.getElementById('periodType').value;
    
    // Handle different monitoring categories
    if (monitoringCategory === 'timeliness') {
        // TIMELINESS MONITORING: Menggunakan timeliness (sec) dan nr_negative_timeliness
        // Hanya DWD dan ECMWF yang punya data timeliness
        const timelinessCenters = ['DWD', 'ECMWF'];
        let validTimelinessValues = [];
        
        // Ambil semua nilai timeliness yang valid dari DWD dan ECMWF saja
        timelinessCenters.forEach(center => {
            if (station.centers && station.centers[center]) {
                const timeliness = station.centers[center].timeliness;
                if (timeliness !== null && timeliness !== undefined && timeliness !== 'No data' && !isNaN(timeliness)) {
                    validTimelinessValues.push(parseFloat(timeliness));
                }
            }
        });
        
        // Jika tidak ada data valid
        if (validTimelinessValues.length === 0) {
            return 'gt-120'; // No timeliness data
        }
        
        // Cari nilai MINIMUM timeliness (lebih kecil = lebih baik/lebih cepat)
        const minTimeliness = Math.min(...validTimelinessValues);
        
        // Convert to absolute minutes for threshold comparison
        const absMinutes = Math.abs(minTimeliness) / 60;
        
        // Threshold berdasarkan menit (absolute value)
        if (absMinutes < 15) {
            return 'lt-15';          // Less than 15 minutes → Light Blue
        } else if (absMinutes < 30) {
            return '15-30';          // Between 15 and 30 minutes → Blue
        } else if (absMinutes < 120) {
            return '30-120';         // Between 30 and 120 minutes → Pink
        } else {
            return 'gt-120';         // Greater than 120 minutes → Red
        }
    } else if (monitoringCategory === 'quality') {
        // Cek apakah period type adalah monthly
        if (periodType === 'monthly') {
            // MONTHLY QUALITY: MENGGUNAKAN RMS (Root Mean Square) dengan threshold dinamis
            const variable = document.getElementById('variableType').value;
            const qualityConfig = getQualityConfig(variable);
            let validRmsValues = [];
            
            // Ambil semua nilai RMS yang valid
            centers.forEach(center => {
                if (station.centers && station.centers[center]) {
                    // Cek apakah ada field rms_bg_dep untuk monthly
                    const rmsValue = station.centers[center].rms_bg_dep || station.centers[center].rms;
                    if (rmsValue !== null && rmsValue !== undefined && rmsValue !== 'No data' && !isNaN(rmsValue)) {
                        validRmsValues.push(Math.abs(parseFloat(rmsValue)));
                    }
                }
            });
            
            // Jika tidak ada RMS data, coba fallback ke avg_bg_dep
            if (validRmsValues.length === 0) {
                centers.forEach(center => {
                    if (station.centers && station.centers[center] && station.centers[center].avg_bg_dep) {
                        const avgBgDep = station.centers[center].avg_bg_dep;
                        if (avgBgDep !== null && avgBgDep !== undefined && avgBgDep !== 'No data' && !isNaN(avgBgDep)) {
                            validRmsValues.push(Math.abs(parseFloat(avgBgDep)));
                        }
                    }
                });
            }
            
            // Jika tidak ada data valid sama sekali
            if (validRmsValues.length === 0) {
                return 'offline'; // No quality data → Merah
            }
            
            // Cari nilai MINIMUM dari RMS values
            const minRms = Math.min(...validRmsValues);
            
            // Determine status based on MINIMUM RMS value using dynamic thresholds
            for (let i = 0; i < qualityConfig.ranges.length; i++) {
                const range = qualityConfig.ranges[i];
                if (minRms > range.min && (minRms <= range.max || range.max === Infinity)) {
                    return range.status;
                }
            }
            
            // Default to best status if value is below all thresholds
            return qualityConfig.ranges[qualityConfig.ranges.length - 1].status;
        } else if (periodType === 'alert') {
            // ALERT QUALITY: MENGGUNAKAN AVG_BG_DEP dengan threshold dinamis
            const variable = document.getElementById('variableType').value;
            const qualityConfig = getQualityConfig(variable);
            let validValues = [];
            
            // Ambil semua nilai yang valid
            centers.forEach(center => {
                if (station.centers && station.centers[center] && station.centers[center].avg_bg_dep) {
                    const avgBgDep = station.centers[center].avg_bg_dep;
                    
                    // Cek apakah nilai valid (bukan "No data" atau NaN)
                    if (avgBgDep !== null && avgBgDep !== undefined && avgBgDep !== 'No data' && !isNaN(avgBgDep)) {
                        // Gunakan nilai absolut untuk perhitungan
                        validValues.push(Math.abs(parseFloat(avgBgDep)));
                    }
                }
            });
            
            // Jika tidak ada data valid sama sekali - treat as worst case (offline/merah)
            if (validValues.length === 0) {
                return 'offline'; // No quality data available → Merah (worst case)
            }
            
            // Cari nilai MINIMUM dari semua nilai yang valid
            const minQuality = Math.min(...validValues);
            
            // Determine status based on MINIMUM quality value using dynamic thresholds
            for (let i = 0; i < qualityConfig.ranges.length; i++) {
                const range = qualityConfig.ranges[i];
                if (minQuality > range.min && (minQuality <= range.max || range.max === Infinity)) {
                    return range.status;
                }
            }
            
            // Default to best status if value is below all thresholds
            return qualityConfig.ranges[qualityConfig.ranges.length - 1].status;
        } else {
            // SIX-HOUR / DAILY QUALITY: MENGGUNAKAN AVG_BG_DEP MINIMUM dengan threshold dinamis
            const variable = document.getElementById('variableType').value;
            const qualityConfig = getQualityConfig(variable);
            let validValues = [];
            
            // Ambil semua nilai yang valid (bukan "No data", null, undefined, atau NaN)
            centers.forEach(center => {
                if (station.centers && station.centers[center] && station.centers[center].avg_bg_dep) {
                    const avgBgDep = station.centers[center].avg_bg_dep;
                    // Cek apakah nilai valid (bukan "No data" atau NaN)
                    if (avgBgDep !== null && avgBgDep !== undefined && avgBgDep !== 'No data' && !isNaN(avgBgDep)) {
                        // Gunakan nilai absolut untuk perhitungan
                        validValues.push(Math.abs(parseFloat(avgBgDep)));
                    }
                }
            });
            
            // Jika tidak ada data valid sama sekali - treat as worst case (offline/merah)
            if (validValues.length === 0) {
                return 'offline'; // No quality data available → Merah (worst case)
            }
            
            // Cari nilai MINIMUM dari semua nilai yang valid
            const minQuality = Math.min(...validValues);
            
            // Determine status based on MINIMUM quality value using dynamic thresholds
            // Loop through ranges from worst to best
            for (let i = 0; i < qualityConfig.ranges.length; i++) {
                const range = qualityConfig.ranges[i];
                if (minQuality > range.min && (minQuality <= range.max || range.max === Infinity)) {
                    return range.status;
                }
            }
            
            // Default to best status if value is below all thresholds
            return qualityConfig.ranges[qualityConfig.ranges.length - 1].status;
        }
    } else {
        // Availability monitoring logic (original)
        // Step 1: Check if any center has received > expected (More than 100%)
        for (const center of centers) {
            if (station.centers && station.centers[center]) {
                const received = station.centers[center].received || 0;
                const expected = station.centers[center].expected || 0;
                if (expected > 0 && received > expected) {
                    return 'more-than-100'; // More than 100%
                }
            }
        }
        
        // Step 2: Check if at least one center has 100% coverage (WDQMS logic)
        let hasFullCoverage = false;
        let hasPartialData = false;
        
        // Debug logging
        console.log(`🔍 AVAILABILITY DEBUG for ${station.name || 'Unknown'}:`);
        console.log(`   Raw station data:`, station.centers);
        
        for (const center of centers) {
            if (station.centers && station.centers[center]) {
                const received = station.centers[center].received || 0;
                const expected = station.centers[center].expected || 0;
                
                if (expected > 0) {
                    const coverage = (received / expected) * 100;
                    console.log(`   ${center}: ${received}/${expected} = ${coverage.toFixed(1)}%`);
                    
                    if (coverage >= 100) {
                        hasFullCoverage = true;
                    }
                    if (coverage > 0) {
                        hasPartialData = true;
                    }
                }
            }
        }
        
        // Step 3: Determine status based on WDQMS logic
        let finalStatus;
        if (hasFullCoverage) {
            finalStatus = 'operational'; // Green - At least one center has 100% coverage
        } else if (hasPartialData) {
            finalStatus = 'issues';      // Orange - Has some data but no full coverage
        } else {
            finalStatus = 'not_received'; // Black - No data received
        }
        
        console.log(`   ✅ Final Status: ${finalStatus} (hasFullCoverage: ${hasFullCoverage}, hasPartialData: ${hasPartialData})`);
        return finalStatus;
    }
}

// Create station marker
function createStationMarker(station, status) {
    // Get current monitoring category to determine color scheme
    const monitoringCategory = document.getElementById('monitoringCategory').value;
    
    let colors;
    if (monitoringCategory === 'timeliness') {
        // Timeliness monitoring colors
        colors = {
            'lt-15': '#4A90E2',          // Dark blue - Less than 15 minutes (best)
            '15-30': '#87CEEB',          // Light blue - Between 15 and 30 minutes
            '30-120': '#FFA500',         // Orange - Between 30 and 120 minutes
            'gt-120': '#FF6B6B'          // Red - Greater than 120 minutes (worst)
        };
    } else if (monitoringCategory === 'quality') {
        // Quality monitoring colors - Build dynamically from current variable config
        const variable = document.getElementById('variableType').value;
        const qualityConfig = getQualityConfig(variable);
        colors = {};
        qualityConfig.ranges.forEach(range => {
            colors[range.status] = range.color;
        });
        // Add gray for less than 5 values
        colors['less-than-5'] = '#9ca3af';
    } else {
        // Availability monitoring colors (original)
        colors = {
            'more-than-100': '#e91e63',   // pink - More than 100%
            'operational': '#22c55e',     // green - Normal (≥ 80%)
            'issues': '#f97316',          // orange - Availability Issues (≥ 30%)
            'critical': '#ef4444',        // red - Availability Issues (< 30%)
            'not-received': '#1f2937',    // black - Not received in period
            'oscar-issue': '#9ca3af',     // gray - OSCAR schedule issue
            'no-match': '#eab308'         // yellow - No match in OSCAR/Surface
        };
    }
    
    // Use default color if status not found
    const markerColor = colors[status] || '#1f2937';

    // Ensure coordinates are valid numbers
    const lat = parseFloat(station.latitude);
    const lng = parseFloat(station.longitude);
    
    if (isNaN(lat) || isNaN(lng)) {
        console.error('Invalid coordinates for station:', station.name, station.latitude, station.longitude);
        return null;
    }

    try {
        // Create markers that wrap around the world
        const markers = [];
        // Create main marker
        const marker = L.marker([lat, lng], {
            icon: L.divIcon({
                className: `station-marker status-${status}`,
                html: `<div style="background-color: ${markerColor}; border: 2px solid white; width: 16px; height: 16px; border-radius: 50%; box-shadow: 0 0 4px rgba(0,0,0,0.5);"></div>`,
                iconSize: [20, 20],
                iconAnchor: [10, 10]
            })
        });

        // Create wrapped markers for date line crossing
        const wrappedMarkerEast = L.marker([lat, lng + 360], {
            icon: L.divIcon({
                className: `station-marker status-${status}`,
                html: `<div style="background-color: ${markerColor}; border: 2px solid white; width: 16px; height: 16px; border-radius: 50%; box-shadow: 0 0 4px rgba(0,0,0,0.5);"></div>`,
                iconSize: [20, 20],
                iconAnchor: [10, 10]
            })
        });

        const wrappedMarkerWest = L.marker([lat, lng - 360], {
            icon: L.divIcon({
                className: `station-marker status-${status}`,
                html: `<div style="background-color: ${markerColor}; border: 2px solid white; width: 16px; height: 16px; border-radius: 50%; box-shadow: 0 0 4px rgba(0,0,0,0.5);"></div>`,
                iconSize: [20, 20],
                iconAnchor: [10, 10]
            })
        });

        // Create popup content
        const popupContent = createPopupContent(station, status);
        
        // Bind popup and tooltip to all markers
        [marker, wrappedMarkerEast, wrappedMarkerWest].forEach(m => {
            m.bindPopup(popupContent, {
            maxWidth: 400,
            className: 'custom-popup',
            autoPan: true,
            closeButton: true
        });
        
            m.bindTooltip(`${station.name} (${status})`, {
            direction: 'top',
            offset: [0, -10],
            opacity: 0.9,
            className: `status-tooltip status-${status}`
        });
        
            m.on('click', function() {
            updateInfoPanel(station, status);
            });
        });

        return [marker, wrappedMarkerEast, wrappedMarkerWest];
    } catch (error) {
        console.error('Error creating marker:', error);
        showAlert('error', 'Error creating marker: ' + error.message);
        return null;
    }
}

// Create popup content for station
function createPopupContent(station, status) {
    // Build center-specific data rows if available
    let centerDataHTML = '';
    const monitoringCategory = document.getElementById('monitoringCategory').value;
    
    // Check if we have centers data from API
    if (station.centers && Object.keys(station.centers).length > 0) {
        const centers = ['DWD', 'ECMWF', 'JMA', 'NCEP'];
        let totalReceived = 0;
        let totalExpected = 0;
        let totalBgDep = 0;
        let validCenters = 0;
        
        if (monitoringCategory === 'timeliness') {
            // Timeliness monitoring popup - hanya DWD dan ECMWF yang punya data
            const timelinessCenters = ['DWD', 'ECMWF'];
            centerDataHTML = `
                <div class="info-row center-data-header">
                    <span class="info-label">Center Timeliness Data:</span>
                </div>
                <div class="info-row center-data">
                    <table class="center-table">
                        <tr>
                            <th>Center</th>
                            <th>Timeliness (sec)</th>
                            <th>Timeliness (Min)</th>
                        </tr>`;
            
            let validTimelinessValues = [];
            
            // First pass: collect all valid timeliness values
            timelinessCenters.forEach(center => {
                if (station.centers[center]) {
                    let timeliness = station.centers[center].timeliness;
                    if (timeliness !== null && timeliness !== undefined && timeliness !== 'No data' && !isNaN(timeliness)) {
                        validTimelinessValues.push(parseFloat(timeliness));
                    }
                }
            });
            
            // Hitung nilai MINIMUM timeliness (terkecil) - ini yang menentukan warna
            const minTimeliness = validTimelinessValues.length > 0 ? Math.min(...validTimelinessValues) : null;
            
            // Tentukan warna berdasarkan nilai MINIMUM dalam menit (absolute value)
            let statusColor = '#9ca3af'; // Default gray
            if (minTimeliness !== null) {
                const absMinutesMin = Math.abs(minTimeliness) / 60;
                if (absMinutesMin < 15) {
                    statusColor = '#4A90E2'; // Dark blue
                } else if (absMinutesMin < 30) {
                    statusColor = '#87CEEB'; // Light blue
                } else if (absMinutesMin < 120) {
                    statusColor = '#FFA500'; // Orange
                } else {
                    statusColor = '#FF6B6B'; // Red
                }
            }
            
            // Second pass: display each center with the SAME color (based on minimum)
            timelinessCenters.forEach(center => {
                if (station.centers[center]) {
                    let timeliness = station.centers[center].timeliness;
                    let timelinessSecFormatted = 'N/A';
                    let timelinessMinFormatted = 'N/A';
                    
                    // Cek apakah nilai valid
                    if (timeliness !== null && timeliness !== undefined && timeliness !== 'No data' && !isNaN(timeliness)) {
                        const timelinessValue = parseFloat(timeliness);
                        
                        // Format untuk kolom seconds
                        timelinessSecFormatted = timelinessValue.toFixed(0);
                        
                        // Format untuk kolom minutes: convert to "Xm Ys" format
                        const absSeconds = Math.abs(timelinessValue);
                        const minutes = Math.floor(absSeconds / 60);
                        const seconds = Math.floor(absSeconds % 60);
                        const sign = timelinessValue < 0 ? '-' : '';
                        timelinessMinFormatted = `${sign}${minutes}m ${seconds}s`;
                    }
                    
                    centerDataHTML += `
                        <tr>
                            <td>${center}</td>
                            <td>${timelinessSecFormatted}</td>
                            <td><span style="display: inline-block; width: 12px; height: 12px; background-color: ${statusColor}; border-radius: 50%; margin-right: 5px;"></span>${timelinessMinFormatted}</td>
                        </tr>`;
                }
            });
            
            // Format minimum timeliness untuk display
            let minTimelinessFormatted = 'N/A';
            if (minTimeliness !== null) {
                const absSeconds = Math.abs(minTimeliness);
                const minutes = Math.floor(absSeconds / 60);
                const seconds = Math.floor(absSeconds % 60);
                const sign = minTimeliness < 0 ? '-' : '';
                minTimelinessFormatted = `${minTimeliness.toFixed(0)} sec (${sign}${minutes}m ${seconds}s)`;
            }
            
            centerDataHTML += `
                    </table>
                </div>
                <div class="info-row">
                    <span class="info-label">Minimum Timeliness (Best):</span>
                    <span class="info-value">${minTimelinessFormatted}</span>
                </div>
            `;
        } else if (monitoringCategory === 'quality') {
            // Quality monitoring popup
            const periodType = document.getElementById('periodType').value;
            const isMonthly = periodType === 'monthly';
            const isAlert = periodType === 'alert';
            
            // Column header berbeda untuk monthly (RMS) vs alert (Avg Bg Dep + Daily Values) vs six-hour/daily (Avg Bg Dep)
            const valueHeader = isMonthly ? 'RMS Bg Dep' : 'Avg Bg Dep';
            const extraColumnHeader = isAlert ? '<th>Daily Values</th>' : '';
            
            centerDataHTML = `
                <div class="info-row center-data-header">
                    <span class="info-label">Center Quality Data:</span>
                </div>
                <div class="info-row center-data">
                    <table class="center-table">
                        <tr>
                            <th>Center</th>
                            <th>${valueHeader}</th>
                            ${extraColumnHeader}
                            <th>Quality</th>
                        </tr>`;
            
            let validValues = [];
            
            centers.forEach(center => {
                if (station.centers[center]) {
                    let value, qualityText = 'No Data', valueFormatted = 'N/A', dailyValuesFormatted = '';
                    let dailyValues = null;
                    
                    if (isMonthly) {
                        // MONTHLY: Gunakan RMS value
                        value = station.centers[center].rms_bg_dep || station.centers[center].rms || station.centers[center].avg_bg_dep;
                    } else if (isAlert) {
                        // ALERT: Gunakan avg_bg_dep dan daily_values
                        value = station.centers[center].avg_bg_dep;
                        dailyValues = station.centers[center].daily_values;
                        
                        if (dailyValues !== null && dailyValues !== undefined) {
                            dailyValuesFormatted = `<td>${dailyValues}</td>`;
                        } else {
                            dailyValuesFormatted = '<td>N/A</td>';
                        }
                    } else {
                        // SIX-HOUR/DAILY: Gunakan avg_bg_dep
                        value = station.centers[center].avg_bg_dep;
                    }
                    
                    // Cek apakah nilai valid
                    if (value !== null && value !== undefined && value !== 'No data' && !isNaN(value)) {
                        // Format number dengan nilai absolut
                        const absValue = Math.abs(parseFloat(value));
                        valueFormatted = absValue.toFixed(3);
                        validValues.push(absValue);
                        
                        // Determine quality based on thresholds (using absolute value)
                        if (absValue <= 0.5) {
                            qualityText = 'Excellent';      // ≤ 0.5 hPa
                        } else if (absValue <= 1.0) {
                            qualityText = 'Very Good';      // 0.5 < x ≤ 1 hPa
                        } else if (absValue <= 5.0) {
                            qualityText = 'Good';           // 1 < x ≤ 5 hPa
                        } else if (absValue <= 10.0) {
                            qualityText = 'Fair';           // 5 < x ≤ 10 hPa
                        } else {
                            qualityText = 'Poor';           // > 10 hPa
                        }
                        
                        // Add note if daily values < 5 for alert period
                        if (isAlert && dailyValues !== null && dailyValues < 5) {
                            qualityText += ' (< 5 values)';
                        }
                    }
                    
                    centerDataHTML += `
                        <tr>
                            <td>${center}</td>
                            <td>${valueFormatted}</td>
                            ${isAlert ? dailyValuesFormatted : ''}
                            <td>${qualityText}</td>
                        </tr>`;
                }
            });
            
            // Hitung nilai MINIMUM
            const minQuality = validValues.length > 0 ? Math.min(...validValues) : null;
            const metricLabel = isMonthly ? 'Minimum RMS (Best):' : 'Minimum Quality (Best):';
            
            centerDataHTML += `
                    </table>
                </div>
                <div class="info-row">
                    <span class="info-label">${metricLabel}</span>
                    <span class="info-value">${minQuality !== null ? minQuality.toFixed(3) + ' hPa' : 'N/A'}</span>
                </div>
            `;
        } else {
            // Availability monitoring popup (original)
            centerDataHTML = `
                <div class="info-row center-data-header">
                    <span class="info-label">Center Data:</span>
                </div>
                <div class="info-row center-data">
                    <table class="center-table">
                        <tr>
                            <th>Center</th>
                            <th>Received</th>
                            <th>Expected</th>
                            <th>Coverage</th>
                        </tr>`;
            
            centers.forEach(center => {
                if (station.centers[center]) {
                    const received = station.centers[center].received || 0;
                    const expected = station.centers[center].expected || 0;
                    const coverage = expected > 0 ? Math.round((received / expected) * 100) : 0;
                    
                    totalReceived += received;
                    totalExpected += expected;
                    
                    centerDataHTML += `
                        <tr>
                            <td>${center}</td>
                            <td>${received}</td>
                            <td>${expected}</td>
                            <td>${coverage}%</td>
                        </tr>`;
                }
            });
            
            centerDataHTML += `
                    </table>
                </div>
                <div class="info-row">
                    <span class="info-label">Total Data Received:</span>
                    <span class="info-value">${totalExpected > 0 ? Math.round((totalReceived / totalExpected) * 100) : 0}%</span>
                </div>
            `;
        }
    } else if (station.DWD !== undefined || station.ECMWF !== undefined || 
               station.JMA !== undefined || station.NCEP !== undefined) {
        // Fallback: use old format (all centers have same expected value)
        const expected = station.expected || 6;
        let totalReceived = 0;
        
        centerDataHTML = `
            <div class="info-row center-data-header">
                <span class="info-label">Center Data:</span>
            </div>
            <div class="info-row center-data">
                <table class="center-table">
                    <tr>
                        <th>Center</th>
                        <th>Received</th>
                        <th>Expected</th>
                        <th>Coverage</th>
                    </tr>
                    <tr>
                        <td>DWD</td>
                        <td>${station.DWD !== undefined ? station.DWD : 'N/A'}</td>
                        <td>${expected}</td>
                        <td>${station.DWD !== undefined ? Math.round((station.DWD / expected) * 100) + '%' : 'N/A'}</td>
                    </tr>
                    <tr>
                        <td>ECMWF</td>
                        <td>${station.ECMWF !== undefined ? station.ECMWF : 'N/A'}</td>
                        <td>${expected}</td>
                        <td>${station.ECMWF !== undefined ? Math.round((station.ECMWF / expected) * 100) + '%' : 'N/A'}</td>
                    </tr>
                    <tr>
                        <td>JMA</td>
                        <td>${station.JMA !== undefined ? station.JMA : 'N/A'}</td>
                        <td>${expected}</td>
                        <td>${station.JMA !== undefined ? Math.round((station.JMA / expected) * 100) + '%' : 'N/A'}</td>
                    </tr>
                    <tr>
                        <td>NCEP</td>
                        <td>${station.NCEP !== undefined ? station.NCEP : 'N/A'}</td>
                        <td>${expected}</td>
                        <td>${station.NCEP !== undefined ? Math.round((station.NCEP / expected) * 100) + '%' : 'N/A'}</td>
                    </tr>
                </table>
            </div>
            <div class="info-row">
                <span class="info-label">Total Data Received:</span>
                <span class="info-value">${calculateTotalCoverage(station)}%</span>
            </div>
        `;
    }

    return `
    <div class="popup">
        <div class="popup-header">
            <h3><i class="fas fa-broadcast-tower"></i> ${station.name}</h3>
        </div>
        <div class="popup-body">
            <div class="info-row">
                <span class="info-label">WIGOS ID:</span>
                <span class="info-value">${station.wigosId || 'N/A'}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Country:</span>
                <span class="info-value">${station.countryCode}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Coordinates:</span>
                <span class="info-value">${station.latitude.toFixed(4)}, ${station.longitude.toFixed(4)}</span>
            </div>
            ${centerDataHTML}
            ${station.variable ? `
            <div class="info-row">
                <span class="info-label">Variable:</span>
                <span class="info-value">${station.variable}</span>
            </div>
            ` : ''}
            ${station.inOSCAR ? `
            <div class="info-row">
                <span class="info-label">In OSCAR:</span>
                <span class="info-value">
                    <a href="https://oscar.wmo.int/surface/index.html#/search/station/stationReportDetails/${station.wigosId}" target="_blank" class="oscar-link">${station.inOSCAR} <i class="fas fa-external-link-alt"></i></a>
                </span>
            </div>
            ` : ''}
            ${station.summary ? `
            <div class="info-row">
                <span class="info-label">Summary:</span>
                <span class="info-value" style="font-family: monospace; font-size: 12px; word-break: break-all;">${station.summary}</span>
            </div>
            ` : ''}
            <div class="info-row">
                <span class="info-label">Last Updated:</span>
                <span class="info-value">${station.lastUpdated || ''}</span>
            </div>
        </div>
    </div>
    `;
}

// Calculate total coverage for a station
function calculateTotalCoverage(station) {
    const centers = ['DWD', 'ECMWF', 'JMA', 'NCEP'];
    let totalReceived = 0;
    let centerCount = 0;

    centers.forEach(center => {
        if (station[center] !== undefined) {
            totalReceived += station[center];
            centerCount++;
        }
    });

    // Use the expected from station data (already the total expected, not per center)
    const totalExpected = station.expected || 0;

    return totalExpected > 0 ? Math.round((totalReceived / totalExpected) * 100) : 0;
}

// Display stations on map
function displayStations(stations) {
    try {
        // Clear existing markers
        stationMarkers.forEach(markerArray => {
            if (Array.isArray(markerArray)) {
                markerArray.forEach(marker => {
                    if (marker) marker.remove();
                });
            } else if (markerArray) {
                markerArray.remove();
            }
        });
        stationMarkers = [];

        // Add new markers
        stations.forEach(station => {
            // Always recalculate status using our corrected logic for ALL monitoring categories
            const monitoringCategory = document.getElementById('monitoringCategory').value;
            const status = determineStationStatus(station);
            
            const markers = createStationMarker(station, status);
            if (markers) {
                // Always show markers initially since all statuses are active by default
                markers.forEach(marker => {
                    if (marker) marker.addTo(map);
                });
                stationMarkers.push(markers);
            }
        });

        // Store current stations for filtering
        currentStations = stations;

        // Update statistics after displaying stations
        updateStatistics({
            total: stations.length,
            stations: stations
        });
    } catch (error) {
        console.error('Error displaying stations:', error);
        showAlert('error', 'Error displaying stations: ' + error.message);
    }
}
