<?php
include 'config.php';
$conn = new mysqli($servername, $username, $password, $dbname);


$sql = "SELECT `id`,`depth`,`time` FROM `totalbtc` WHERE `depth` != '' ";
$result = $conn->query($sql);
echo json_encode($result->fetch_all());
//print_r ($result);






?>