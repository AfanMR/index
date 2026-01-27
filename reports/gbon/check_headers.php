<?php
$date = '2026-01-17';
$variable = 'pressure';
$url = "https://wdqms.wmo.int/wdqmsapi/v1/download/gbon/synop/daily/availability/?" . 
       "date=$date&variable=$variable&centers=DWD,ECMWF,JMA,NCEP";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
$response = curl_exec($ch);
curl_close($ch);

if ($response) {
    echo substr($response, 0, 1000);
} else {
    echo "No response";
}
?>
