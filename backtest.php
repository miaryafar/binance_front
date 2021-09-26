<?php
include 'config.php';
$conn = new mysqli($servername, $username, $password, $dbname);

$sql = "SELECT `pricemax` FROM (SELECT `id`,`pricemax` FROM `btcusdt` ORDER BY `id` DESC LIMIT 300000 OFFSET 0) AS AAA ORDER BY `id` ASC";
$results = $conn->query($sql);

$mv3=100;
$mv2 = 1000;
$mv1 = 10000;

$priceArray = Array();
$priceArray = array_fill(0,$mv1+1,0);


$sum1 = 0;
$sum2 = 0;
$sum3 = 0;

$an = 5;
$a1 = Array();
$a2 = Array();
$a3 = Array();


$i=0;
while ( $row = $results->fetch_assoc() and $i < $mv1){
	$i += 1;
	array_unshift($priceArray , $row['pricemax']);
	$priceArray = array_slice($priceArray, 0, $mv1 + 1);
	$sum1 = $sum1 + $priceArray[0] - $priceArray[$mv1];
	$sum2 = $sum2 + $priceArray[0] - $priceArray[$mv2];
	$sum3 = $sum3 + $priceArray[0] - $priceArray[$mv3];
	
	array_unshift($a1,$sum1/$mv1);
	array_unshift($a2,$sum2/$mv2);
	array_unshift($a3,$sum3/$mv3);
	$a1 = array_slice($a1,0,$an);
	$a2 = array_slice($a2,0,$an);
	$a3 = array_slice($a3,0,$an);
	
}

$lastparam = 1;
$position = 0;
$i =0 ;
$prices = Array();

$max =  array();

while ( $row = $results->fetch_assoc()){
	if ($position ==1){
		$position=0;
		$prices[$i]=$row['pricemax'];
		$i += 1;
	}
	
	array_unshift($priceArray , $row['pricemax']);
	$priceArray = array_slice($priceArray, 0, $mv1 + 1);
	$sum1 = $sum1 + $priceArray[0] - $priceArray[$mv1];
	$sum2 = $sum2 + $priceArray[0] - $priceArray[$mv2];
	$sum3 = $sum3 + $priceArray[0] - $priceArray[$mv3];
	
	array_unshift($a1,$sum1/$mv1);
	array_unshift($a2,$sum2/$mv2);
	array_unshift($a3,$sum3/$mv3);
	$a1 = array_slice($a1,0,$an);
	$a2 = array_slice($a2,0,$an);
	$a3 = array_slice($a3,0,$an);
	
	$param = ($a1[0]-$a1[1]) + ($a2[0]-$a2[1]) + ($a3[0]-$a3[1]) + ($a3[0]-$a1[0])/400 + ($a3[0]-$a2[0])/150;
	
	
	array_push($max,($a3[0]-$a2[0]));
	if ($param*$lastparam < 0) $position = 1;
	$lastparam = $param;
	
}

$sum = 0;
$j = $i;
$i=0;
while ($i+1 < $j){
	echo ($prices[$i])." ";
	$sum = $prices[$i]-$prices[$i+1];
	$i = $i +2;
}
echo "sum:".$sum;
	
	
	
	




?>