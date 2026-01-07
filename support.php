<?php
// pages/support.php
require_once 'config/config.php';
include 'includes/header.php';
include 'includes/navigation.php';
?>

<!-- Main Content -->
<main class="container mx-auto px-4 py-8 lg:py-12 max-w-7xl">
    <!-- Header Section -->
    <div class="text-center mb-12">
        <h1 class="text-3xl lg:text-4xl text-bmkg-blue font-bold mb-4">
            Technical Support
        </h1>
        <div class="w-24 h-1 bg-bmkg-blue mx-auto mb-6"></div>
        <p class="text-lg text-gray-600 max-w-3xl mx-auto leading-relaxed">
            Get help and support for RWC Indonesian Dashboard operations and technical issues
        </p>
    </div>

    <!-- Support Options -->
    <div class="grid md:grid-cols-3 gap-8 mb-12">
        <div class="bg-white rounded-lg shadow-lg p-8 border-t-4 border-bmkg-blue text-center">
            <div class="bg-bmkg-blue text-white p-4 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Email Support</h3>
            <p class="text-gray-600 mb-4">Send us your technical questions and issues via email</p>
            <div class="text-bmkg-blue font-semibold">rwc.support@bmkg.go.id</div>
            <div class="text-sm text-gray-500 mt-2">Response within 24 hours</div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8 border-t-4 border-purple-500 text-center">
            <div class="bg-purple-500 text-white p-4 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Phone Support</h3>
            <p class="text-gray-600 mb-4">Call our technical support hotline for urgent issues</p>
            <div class="text-purple-600 font-semibold">+62-21-4246321</div>
            <div class="text-sm text-gray-500 mt-2">24/7 Available</div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8 border-t-4 border-green-500 text-center">
            <div class="bg-green-500 text-white p-4 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.485 3.488"/>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-4">WhatsApp Support</h3>
            <p class="text-gray-600 mb-4">Chat with our support team via WhatsApp</p>
            <a href="https://wa.me/6282246321?text=Hello,%20I%20need%20technical%20support%20for%20RWC%20Indonesian%20Dashboard" 
               target="_blank"
               class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition-colors duration-300 inline-block">
                Chat on WhatsApp
            </a>
            <div class="text-sm text-gray-500 mt-2">Mon-Fri 08:00-17:00 WIB</div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="bg-white rounded-xl shadow-lg p-8 mb-12">
        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-8">Frequently Asked Questions</h2>
        <div class="space-y-6">
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">How do I access the dashboard?</h3>
                <p class="text-gray-600">You can access the RWC Asia Pacific Dashboard through your web browser using the provided URL. Make sure you have valid credentials and are connected to the authorized network.</p>
            </div>
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">What should I do if data is not updating?</h3>
                <p class="text-gray-600">First, check your internet connection and refresh the page. If the issue persists, verify that the data sources are operational and contact technical support if needed.</p>
            </div>
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">How often is the data updated?</h3>
                <p class="text-gray-600">The dashboard displays real-time data that is updated continuously. Most meteorological data is refreshed every few minutes, while some specialized data may have different update intervals.</p>
            </div>
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Can I export dashboard data?</h3>
                <p class="text-gray-600">Yes, the dashboard provides various export options including CSV, Excel, and PDF formats. Look for the export buttons in each data section or contact support for custom export requirements.</p>
            </div>
            <div class="pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">What browsers are supported?</h3>
                <p class="text-gray-600">The dashboard is optimized for modern browsers including Chrome, Firefox, Safari, and Edge. We recommend using the latest version of your preferred browser for the best experience.</p>
            </div>
        </div>
    </div>

    <!-- Troubleshooting Guide -->
    <div class="grid md:grid-cols-2 gap-8 mb-12">
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-8">
            <h2 class="text-xl font-semibold text-bmkg-blue mb-6">Common Issues</h2>
            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="bg-red-100 text-red-600 p-2 rounded-full mr-4 mt-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L3.232 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">Dashboard Not Loading</h4>
                        <p class="text-sm text-gray-600 mt-1">Check network connection and clear browser cache</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="bg-yellow-100 text-yellow-600 p-2 rounded-full mr-4 mt-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">Slow Performance</h4>
                        <p class="text-sm text-gray-600 mt-1">Close unnecessary browser tabs and check system resources</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="bg-orange-100 text-orange-600 p-2 rounded-full mr-4 mt-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">Display Issues</h4>
                        <p class="text-sm text-gray-600 mt-1">Adjust browser zoom level and check screen resolution</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-8">
            <h2 class="text-xl font-semibold text-green-700 mb-6">Quick Solutions</h2>
            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="bg-green-100 text-green-600 p-2 rounded-full mr-4 mt-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">Refresh Page</h4>
                        <p class="text-sm text-gray-600 mt-1">Press F5 or Ctrl+R to reload the dashboard</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="bg-blue-100 text-blue-600 p-2 rounded-full mr-4 mt-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">Clear Cache</h4>
                        <p class="text-sm text-gray-600 mt-1">Clear browser cache and cookies for the site</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="bg-purple-100 text-purple-600 p-2 rounded-full mr-4 mt-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">Check Settings</h4>
                        <p class="text-sm text-gray-600 mt-1">Verify browser settings and disable ad blockers</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Guide Section -->
    <div class="bg-white rounded-xl shadow-lg p-8 mb-12">
        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-8">User Guide & Documentation</h2>
        
        <!-- Quick Start Guide -->
        <div class="mb-8">
            <h3 class="text-xl font-semibold text-bmkg-blue mb-4">Quick Start Guide</h3>
            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-6">
                    <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                        <svg class="w-5 h-5 text-bmkg-blue mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Getting Started
                    </h4>
                    <ul class="text-sm text-gray-600 space-y-2">
                        <li>• Access dashboard through your web browser</li>
                        <li>• Choose your monitoring module (GBON, NWP, GCOS)</li>
                        <li>• Data loads automatically within 3-5 seconds</li>
                        <li>• Use responsive design on any device</li>
                    </ul>
                </div>
                <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-6">
                    <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        System Requirements
                    </h4>
                    <ul class="text-sm text-gray-600 space-y-2">
                        <li>• Chrome 80+, Firefox 75+, Safari 13+, Edge 80+</li>
                        <li>• Desktop/Laptop (1024x768 minimum)</li>
                        <li>• Tablet (8 inch screen minimum)</li>
                        <li>• Mobile (5 inch screen minimum)</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Dashboard Modules -->
        <div class="mb-8">
            <h3 class="text-xl font-semibold text-bmkg-blue mb-4">Dashboard Modules</h3>
            <div class="grid gap-4">
                <!-- GBON Module -->
                <div class="border rounded-lg p-6">
                    <div class="flex items-center mb-3">
                        <div class="bg-bmkg-blue text-white p-2 rounded-full mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-800">GBON Monitoring</h4>
                    </div>
                    <p class="text-gray-600 mb-4">Monitor Global Basic Observing Network stations for meteorological data availability and quality.</p>
                    
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <h5 class="font-semibold text-gray-700 mb-2">Control Parameters:</h5>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• <strong>Period Type:</strong> 6-Hour, Daily, Monthly</li>
                                <li>• <strong>Variables:</strong> Pressure, Temperature, Wind, Humidity</li>
                                <li>• <strong>Regions:</strong> WMO Region V, USA Pacific, All Combined</li>
                                <li>• <strong>Centers:</strong> DWD, ECMWF, JMA, NCEP</li>
                                <li>• <strong>Date/Time:</strong> Date picker + UTC time periods</li>
                            </ul>
                        </div>
                        <div>
                            <h5 class="font-semibold text-gray-700 mb-2">Features:</h5>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Real-time station status monitoring</li>
                                <li>• Interactive charts and statistics</li>
                                <li>• Paginated station information table</li>
                                <li>• Auto-refresh every 5 minutes</li>
                                <li>• Color-coded status indicators</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- System Monitoring Overview -->
                <div class="border rounded-lg p-6 bg-gray-50">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">System Monitoring Overview</h4>
                    <div class="grid md:grid-cols-3 gap-6">
                        <!-- GBON Module -->
                        <div class="bg-white rounded-lg p-4">
                            <div class="flex items-center mb-3">
                                <div class="bg-blue-500 text-white p-2 rounded-full mr-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h5 class="font-semibold text-blue-700">GBON Network</h5>
                            </div>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Surface land observations</li>
                                <li>• Upper-air measurements</li>
                                <li>• Real-time data monitoring</li>
                                <li>• Multi-center data processing</li>
                            </ul>
                        </div>
                        
                        <!-- NWP Module -->
                        <div class="bg-white rounded-lg p-4">
                            <div class="flex items-center mb-3">
                                <div class="bg-purple-500 text-white p-2 rounded-full mr-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <h5 class="font-semibold text-purple-700">NWP System</h5>
                            </div>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Numerical weather prediction</li>
                                <li>• Model data availability</li>
                                <li>• Quality assessment</li>
                                <li>• Performance metrics</li>
                            </ul>
                        </div>
                        
                        <!-- GCOS Module -->
                        <div class="bg-white rounded-lg p-4">
                            <div class="flex items-center mb-3">
                                <div class="bg-green-500 text-white p-2 rounded-full mr-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
                                    </svg>
                                </div>
                                <h5 class="font-semibold text-green-700">GCOS Climate</h5>
                            </div>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Climate observing system</li>
                                <li>• Long-term monitoring</li>
                                <li>• Data quality control</li>
                                <li>• Climate indicators</li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Key Features -->
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <h5 class="font-semibold text-gray-800 mb-3">Key System Features</h5>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <h6 class="font-medium text-gray-700 mb-2">Data Processing</h6>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li>• Real-time data ingestion</li>
                                    <li>• Multi-source data integration</li>
                                    <li>• Automated quality checks</li>
                                    <li>• Performance analytics</li>
                                </ul>
                            </div>
                            <div>
                                <h6 class="font-medium text-gray-700 mb-2">User Interface</h6>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li>• Interactive dashboards</li>
                                    <li>• Responsive design</li>
                                    <li>• Export capabilities</li>
                                    <li>• Mobile optimization</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Guide -->
        <div class="mb-8">
            <h3 class="text-xl font-semibold text-bmkg-blue mb-4">Navigation Guide</h3>
            <div class="grid md:grid-cols-3 gap-4">
                <div class="border rounded-lg p-4">
                    <h4 class="font-semibold text-gray-800 mb-2">Main Navigation</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• <strong>Home:</strong> Dashboard overview</li>
                        <li>• <strong>GBON:</strong> Surface & Upper Air</li>
                        <li>• <strong>NWP:</strong> Numerical weather prediction</li>
                        <li>• <strong>About:</strong> System information</li>
                        <li>• <strong>Reports:</strong> Monitoring reports</li>
                    </ul>
                </div>
                <div class="border rounded-lg p-4">
                    <h4 class="font-semibold text-gray-800 mb-2">Table Features</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• <strong>Pagination:</strong> 10/25/50/100 entries</li>
                        <li>• <strong>Navigation:</strong> Previous/Next buttons</li>
                        <li>• <strong>Page Numbers:</strong> Direct page access</li>
                        <li>• <strong>Info Display:</strong> Entry count information</li>
                        <li>• <strong>Smart Controls:</strong> Ellipsis for many pages</li>
                    </ul>
                </div>
                <div class="border rounded-lg p-4">
                    <h4 class="font-semibold text-gray-800 mb-2">Mobile Features</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• <strong>Responsive:</strong> Auto-adapt to screen size</li>
                        <li>• <strong>Touch-optimized:</strong> Finger-friendly buttons</li>
                        <li>• <strong>Hamburger Menu:</strong> Collapsible navigation</li>
                        <li>• <strong>Swipe Support:</strong> Table horizontal scroll</li>
                        <li>• <strong>Single Column:</strong> Vertical layout</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Tips & Best Practices -->
        <div>
            <h3 class="text-xl font-semibold text-bmkg-blue mb-4">Tips & Best Practices</h3>
            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-blue-50 rounded-lg p-4">
                    <h4 class="font-semibold text-blue-800 mb-3">For Meteorologists</h4>
                    <ul class="text-sm text-blue-700 space-y-2">
                        <li>• Monitor data availability for weather prediction</li>
                        <li>• Analyze data quality patterns across regions</li>
                        <li>• Identify regional data gaps for coverage improvement</li>
                        <li>• Use auto-refresh for real-time monitoring</li>
                    </ul>
                </div>
                <div class="bg-green-50 rounded-lg p-4">
                    <h4 class="font-semibold text-green-800 mb-3">For Technicians</h4>
                    <ul class="text-sm text-green-700 space-y-2">
                        <li>• Monitor operational status of stations</li>
                        <li>• Identify problematic stations needing attention</li>
                        <li>• Plan preventive maintenance schedules</li>
                        <li>• Track performance trends over time</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Form -->
    <div class="bg-white rounded-xl shadow-lg p-8">
        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-8">Submit Support Request</h2>
        <form class="max-w-2xl mx-auto">
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input type="text" id="name" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-bmkg-blue focus:border-transparent" required>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" id="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-bmkg-blue focus:border-transparent" required>
                </div>
            </div>
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="organization" class="block text-sm font-medium text-gray-700 mb-2">Organization</label>
                    <input type="text" id="organization" name="organization" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-bmkg-blue focus:border-transparent">
                </div>
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority Level</label>
                    <select id="priority" name="priority" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-bmkg-blue focus:border-transparent">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>
            </div>
            <div class="mb-6">
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                <input type="text" id="subject" name="subject" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-bmkg-blue focus:border-transparent" required>
            </div>
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Problem Description</label>
                <textarea id="description" name="description" rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-bmkg-blue focus:border-transparent" placeholder="Please describe your issue in detail..." required></textarea>
            </div>
            <div class="text-center">
                <button type="submit" class="bg-bmkg-blue text-white px-8 py-3 rounded-lg hover:bg-blue-600 transition-colors duration-300 shadow-lg">
                    Submit Support Request
                </button>
            </div>
        </form>
    </div>
</main>

<?php include 'includes/footer.php'; ?>