<?php
$territory = 'IDN';
$date = '2026-01-17';
$variable = 'pressure';
$url = "http://localhost/api/reports_land_surface.php?territory=$territory&date=$date&variable=$variable";
// Actually I'll just call the script file directly with $_GET set
$_GET['territory'] = 'IDN';
$_GET['date'] = '2026-01-17';
$_GET['variable'] = 'pressure';
include 'c:/xampp/htdocs/index/api/reports_land_surface.php';
?>
