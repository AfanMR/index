<?php
$_GET['territory'] = 'IDN';
$_GET['year'] = '2026';
$_GET['month'] = '01';
$_GET['view'] = 'daily';
$_GET['days'] = '31';
$_GET['stations'] = '0-20000-0-97086'; // WIGOS ID for BANGGAI
include 'c:/xampp/htdocs/index/api/reports_land_surface_timeseries.php';
?>
