<?php
$servername = "localhost";
$username   = "root";
$password   = "26042005";
$dbname     = "QuanLyDuLich";


mysqli_report(MYSQLI_REPORT_OFF); 

global $conn;
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
   throw new Exception("DB connect error: ".$conn->connect_error);
}


$conn->set_charset("utf8mb4");





