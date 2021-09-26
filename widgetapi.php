<?php
include 'config.php';
$conn = new mysqli($servername, $username, $password, $dbname);


$sql = "SELECT `pricemin` FROM `zenusdt` ORDER BY `zenusdt`.`id`  DESC LIMIT 1";
$result = $conn->query($sql);
echo ( $result->fetch_all()[0][0]);
//print_r ($result);

//echo json_encode($result->fetch_all());






?>