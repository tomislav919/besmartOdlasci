<?php

$servername = "127.0.0.1";
$username = "besoftmail";
$password = "5891ranGo";

try {
    $conn = new PDO("mysql:host=$servername;dbname=besoftmail_besmartOdlasci", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }

$userId = 14;


$sql = "INSERT INTO checkin_checkouts (userId, arrival) VALUES (?,NOW())";
$stmt= $conn->prepare($sql);
$stmt->execute([$userId]);

?>
