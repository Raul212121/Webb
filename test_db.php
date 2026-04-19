<?php
$mysqli = new mysqli(
    "81.180.202.241",
    "site_mt2",
    "yd7dy7uvf6xjn",
    "account",
    3306
);

if ($mysqli->connect_error) {
    die("ERROR: " . $mysqli->connect_error);
}

echo "DB CONNECT OK";
?>
