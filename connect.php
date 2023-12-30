<?php
$dsn = 'mysql:host=localhost;dbname=khantopia2';
$user = 'root';
$pass = '';
$options = [
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
];

try {
    $con = new PDO($dsn, $user, $pass, $options);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo 'You are connected to the database';
} catch (PDOException $e) {
    echo 'Failed to connect to the database: ' . $e->getMessage();
}
?>
