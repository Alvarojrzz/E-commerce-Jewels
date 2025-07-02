<?php
$conn = mysqli_connect("localhost", "root", "", "web");

define('BASE_URL', 'http://localhost/web/');
define('ROOT_PATH', __DIR__ . '/');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

?>