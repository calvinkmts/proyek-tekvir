#!/usr/bin/php -f
<?php

$out = "/tmp/std.out";
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "http://api.openweathermap.org/data/2.5/weather?q=Surabaya&appid=d3ac15bb2e05fb45b4b50a1402ce80cf");

curl_setopt($ch, CURLOPT_PROXY, "http://proxy.petra.ac.id:8080");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
file_put_contents($out, curl_exec($ch));
file_put_contents($out, "\n", FILE_APPEND);

curl_close($ch);      

?>