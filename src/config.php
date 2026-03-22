<?php

define('DB_HOST', 'mysql');
define('DB_USER', 'ciberseguridad');
define('DB_PASS', 'Admin123');
define('DB_NAME', 'ciberseguridad');

function getConnection(): mysqli {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
    return $conn;
}
