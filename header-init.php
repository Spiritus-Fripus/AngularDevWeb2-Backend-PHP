<?php

$db = new PDO('mysql:host=localhost;dbname=angular_devweb2', 'root', '');

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT, OPTIONS");


// répondre immédiatement aux requètes OPTIONS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    header("HTTP/1.1 204 No Content");
    exit;
}
