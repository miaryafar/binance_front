<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
?>


<?php
echo fmod(10/14);
$symbol = "BTCUSDT";
include "config.php";
$conn = new mysqli($servername, $username, $password, $dbname);
$sql = "SELECT `id`,`time`,`pricemax` FROM `".strtolower($symbol)."` ORDER BY `id` DESC LIMIT ".$_GET['long']." OFFSET ".$_GET['start']."";
$results = $conn->query($sql);
$dataPoints = array();

$interval = 60;
$i=$_GET['long'];
while ( $row = $results->fetch_assoc()){
	if(fmod($i,$interval) == 0){
		array_unshift($dataPoints, array("x" => $row['id'],"label" => substr($row['time'],8,8) ,"y" => $row['pricemax']));
	}
	$i=$i-1;
	$smallestID = $row['id'];
}  




$sql = "SELECT * FROM `order` WHERE symbol = '$symbol' ORDER BY `status` DESC ,`price` DESC ";
$results = $conn->query($sql);
$sell_c = 0;
$sell_c2 = 0;
$buy_c = 0;
$buy_c2 = 0;
while ( $row = $results->fetch_assoc()){
	
	$id_create = $row['id_create'];
	if ($row['id_create'] < $smallestID){
		$id_create = $smallestID;
	}
	$id_init = $row['id_init'];
	if ($row['id_init'] < $smallestID){
		$id_init = $smallestID;
	}
	$id_fill = $row['id_fill'];
	if ($row['id_fill'] < $smallestID){
		$id_fill = $smallestID;
	}
	
	
			
	
	
	if ($row['status'] == 0 ){
		if ($row['buy'] ==0){
			${'datasell'.$sell_c} = array();
			array_unshift(${'datasell'.$sell_c}, array("x" => $id_create,"y" => $row['price']));
			array_unshift(${'datasell'.$sell_c}, array("x" => $smallestID +$_GET['long'],"y" => $row['price']));
			$sell_c=$sell_c+1;
		
		}else{
			${'databuy'.$buy_c} = array();
			array_unshift(${'databuy'.$buy_c}, array("x" => $id_create,"y" => $row['price']));
			array_unshift(${'databuy'.$buy_c}, array("x" => $smallestID +$_GET['long'],"y" => $row['price']));				
			//array_unshift($dataStat0, array("x" => $row['id_create'],"y" => $row['price']));		
			$buy_c=$buy_c+1;
		}
		
	}elseif ($row['status'] < 4 && $row['id_fill'] > $smallestID){
		if ($row['buy'] ==0){
			${'datasell2'.$sell_c2} = array();
			array_unshift(${'datasell2'.$sell_c2}, array("label" => $row['id'],"x" => $id_create,"y" => $row['price']));
			array_unshift(${'datasell2'.$sell_c2}, array("label" => $row['delta'],"x" => $id_init,"y" => $row['price']));
			if($row['id_fill'] > 0){
				array_unshift(${'datasell2'.$sell_c2}, array("label" => $row['status'],"x" => $id_fill,"y" => $row['fillsprice']));
			}else{
				array_unshift(${'datasell2'.$sell_c2}, array("label" => $row['status'],"x" => $smallestID +$_GET['long'],"y" => $row['price']));
			}
			$sell_c2=$sell_c2+1;
		
		}else{
			${'databuy2'.$buy_c2} = array();
			array_unshift(${'databuy2'.$buy_c2}, array("label" => $row['id'],"x" => $id_create,"y" => $row['price']));
			array_unshift(${'databuy2'.$buy_c2}, array("label" => $row['delta'],"x" => $id_init,"y" => $row['price']));				
			if($row['id_fill'] > 0){
				array_unshift(${'databuy2'.$buy_c2}, array("label" => $row['status'],"x" => $id_fill,"y" => $row['fillsprice']));				
			}else{
				array_unshift(${'databuy2'.$buy_c2}, array("label" => $row['status'],"x" => $smallestID +$_GET['long'],"y" => $row['price']));				
			}
			$buy_c2=$buy_c2+1;
		}
		
	}
	
}
 
?>
<!DOCTYPE HTML>
<html>
<head> 
<script>
window.onload = function () {
 
var chart = new CanvasJS.Chart("chartContainer", {
	theme: "light1", // "light1", "light2", "dark1", "dark2"
	animationEnabled: false,
	zoomEnabled: true,
	axisY: {
		includeZero: false,
		lineThickness: 1
	},
	title: {
		text: "Try Zooming and Panning"
	},
	data: [{
		type: "line",     
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}
	
	<?php
	$j=0;
	while (isset(${'datasell'.$j})){
		echo "
			,{
			type: 'line',  
			color: '#ff0066',
			dataPoints:"
			.json_encode(${'datasell'.$j}, JSON_NUMERIC_CHECK).
			"}";
		$j = $j+1;
	}
	
	$j=0;
	while (isset(${'databuy'.$j})){
		echo "
			,{
			type: 'line',  
			color: '#003300',
			dataPoints:"
			.json_encode(${'databuy'.$j}, JSON_NUMERIC_CHECK).
			"}";
		$j = $j+1;
	}
	
	$j=0;
	while (isset(${'datasell2'.$j})){
		echo "
			,{
			type: 'line',  
			color: 'red',
			dataPoints:"
			.json_encode(${'datasell2'.$j}, JSON_NUMERIC_CHECK).
			"}";
		$j = $j+1;
	}
	
	$j=0;
	while (isset(${'databuy2'.$j})){
		echo "
			,{
			type: 'line',  
			color: 'green',
			dataPoints:"
			.json_encode(${'databuy2'.$j}, JSON_NUMERIC_CHECK).
			"}";
		$j = $j+1;
	}
	?>
		
	]
});
chart.render();
 
}
</script>
</head>
<body>
<div id="chartContainer" style="height: 600px; width: 100%;"></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>                 