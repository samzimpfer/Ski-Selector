<?php
$databaseName = 'SZIMPFER_cs2450labs';
$dsn = 'mysql:host=webdb.uvm.edu;dbname=' . $databaseName;
$username = 'szimpfer_writer';
$password = 'dU)ky6c:e5f-Tg6rVu!H';

$pdo = new PDO($dsn, $username, $password);
?>