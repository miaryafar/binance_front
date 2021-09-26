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
echo $_POST['action']."-".$_POST['submit']."-".$_POST['symbol']."-".$_POST['qty']."-".$_POST['qtya']."-".$_POST['symbola'];



if(isset($_POST['action'])){
	// Create connection
include 'config.php';
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

require 'php-binance-api.php';
$api = new Binance\API("WqZ7dNTAXpfcAHhuGrLHvGzyEGyxsujnzc9ONpBl7xkPPIeTcd4qcjbv0Cvt7oyo","pbvwTRsbMPJCBfcIy6Aab7jfRhTZM1760Jfe1RGFZjrIyI3hr0PTQXPI1mb84h7N");
$api->useServerTime();

if($_POST['qtya']==""){
	$qty = $_POST['qty'];
}else{
	$qty = $_POST['qtya'];
}
if($_POST['symbola']==""){
	$symbol = $_POST['symbol'];
}else{
	$symbol = $_POST['symbola'];
}
if($_POST['action']=="buy"){
	$sellOrBuy=1;
}else{
	$sellOrBuy=0;
}

$pricenow = $api->price($symbol);
if ($pricenow>1000){
	$rnd = 3;
}elseif($pricenow>1){
	$rnd = 2;
}else{
	$rnd = 0;
}

if($qty > 100){
	
	$quantity = $qty/$pricenow;
	$quantity = round($quantity , $rnd );
}elseif($qty > 0){
	$percentage = $qty;
	$account = $api->account();
	if($sellOrBuy == 1){
		
		foreach($account['balances'] as $acrow){
			if ($acrow['asset'] == substr($symbol,3) or $acrow['asset'] == substr($symbol,4)){
				$quantity = $acrow['free']*$percentage/100/$pricenow*0.99;
				$quantity = round($quantity , $rnd ) ;
				break;
			}
		}
		
	}else{
		foreach($account['balances'] as $acrow){
			if ($acrow['asset'] == substr($symbol,0,3) or $acrow['asset'] == substr($symbol,0,4)){
				$quantity = $acrow['free']*$percentage/100*0.99;
				$quantity = round($quantity , $rnd ) ;
				break;
			}
		}
	}
		
}

if($quantity > 0 ){
	if($_POST['action']=="buy"){
		$order = $api->buy($symbol, $quantity, 0, "MARKET");
	}else{
		$order = $api->sell($symbol, $quantity, 0, "MARKET");
	}
	
	$sql = "SELECT * FROM `".strtolower($symbol)."` ORDER BY `id`  DESC LIMIT 1";
	if($re = $conn->query($sql)){
		
		$id_c = $re->fetch_assoc()['id'];
	}else{
		$id_c = 0;
	}
	
	if (isset($order['fills']['0']['price'])){
		$sql = "INSERT INTO `orders`(`percentage`,`symbol`,`buy`, `comment`,`status`,`price`,`id_create`,`id_fill`,`origqty`,`fillsprice`,`qty`,`orderId`,`result`) VALUES ('".$quantity."','".$symbol."','".$sellOrBuy."','ORDERNOW','3','".$pricenow."','".$id_c."','".$id_c."','".$quantity."','".$order['fills']['0']['price']."','".$order['fills']['0']['qty']."','".$order['orderId']."','".serialize($order)."')";
	}elseif (isset($order['orderId'])){
		$sql = "INSERT INTO `orders`(`percentage`,`symbol`,`buy`, `comment`,`status`,`price`,`id_create`,`id_fill`,`origqty`,`orderId`,`result`) VALUES ('".$quantity."','".$symbol."','".$sellOrBuy."','ORDERNOW','12','".$pricenow."','".$id_c."','".$id_c."','".$quantity."','".$order['orderId']."','".serialize($order)."')";
	
	}elseif (isset($order['code'])){
		$sql = "INSERT INTO `orders`(`percentage`,`symbol`,`buy`, `comment`,`status`,`price`,`id_create`,`id_fill`,`origqty`,`result`) VALUES ('".$quantity."','".$symbol."','".$sellOrBuy."','ORDERNOW','12','".$pricenow."','".$id_c."','".$id_c."','".$quantity."','".str_replace("'",'"',serialize($order))."')";
	}else{
		$sql = "INSERT INTO `orders`(`percentage`,`symbol`,`buy`, `comment`,`status`,`price`,`id_create`,`id_fill`,`origqty`,`result`) VALUES ('".$quantity."','".$symbol."','".$sellOrBuy."','ORDERNOW','12','".$pricenow."','".$id_c."','".$id_c."','".$quantity."','".str_replace("'",'"',serialize($order))."')";
	}
	
	
	$conn->query($sql);
	echo $sql;
}else{
	echo "quantity is 0";
}

//	include "config.php";
//	$conn = new mysqli($servername, $username, $password, $dbname);
//	
//	$sqlresult = $conn->query("SELECT * FROM `".strtolower($symbol)."` ORDER BY `btcusdt`.`id` DESC LIMIT 1")->fetch_assoc();
//	$lastidid = $sqlresult['id'];
//	$lastprice = $sqlresult['pricemax'];
//	
//	if ( $_POST['action']=='buy'){
//		$sql = "INSERT INTO `orders`(`id_create`,`percentage`,`symbol`,`buy`, `top`, `price`, `delta`, `comment`) VALUES ('".$lastidid."','".$qty."','$symbol','1','1','".($lastprice - 100)."','0','order by ORDERNOW')";
//		$conn->query($sql);	
//	}else if ($_POST['action']=='sell'){
//		$sql = "INSERT INTO `orders`(`id_create`,`percentage`,`symbol`,`buy`, `top`, `price`, `delta`, `comment`) VALUES ('".$lastidid."','".$qty."','$symbol','0','0','".($lastprice + 100)."','0','order by ORDERNOW')";
//		$conn->query($sql);
//}
//$conn->close();	
}

?>



<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Nucleo Icons -->
  <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link href="assets/css/black-dashboard.css?v=1.0.0" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="assets/demo/demo.css" rel="stylesheet" />
</head>

<body>
<div class="wrapper">
<?php include('sidebar.php');?>


<div class="main-panel">
<div class="content">


<div class="col-md-4">
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
<div class="form-group">
                        
<label for="symid">Choose a Pair:</label>
  <select id="symid" name="symbol">
    <option value="BTCUSDT" selected>BTCUSDT</option>
    <option value="ETHUSDT">ETHUSDT</option>
    <option value="ATOMUSDT">ATOMUSDT</option>
    <option value="ETHBTC">ETHBTC</option>
  </select>
  <label>Symbol</label>
                        <input type="text" name="symbola" class="form-control"   value="">
        </div>
  <br>
  <div class="form-group">
 <label for="qtyid">Select quantity</label>
  <select id="qtyid" name="qty">
    <option value="25">25%</option>
    <option value="50">50%</option>
    <option value="75">75%</option>
    <option value="100" selected>100%</option>
  </select>
  <input type="text" name="qtya" class="form-control"   value="">
        </div>
  <br>
  <br>
  <br>
  <br>

<input type="submit" name="action" value="buy" class="btn btn-fill btn-primary">
<input type="submit" name="action" value="sell" class="btn btn-fill btn-primary">
</form>
</div>
<div class="row">
<div class="col-md-4">
            <div class="card ">
              <div class="card-header">
                <h4 class="card-title"> Accoount info</h4>
              </div>
              <div class="card-body">
                <div class="table-responsive ps">
                  <table class="table tablesorter " id="">
                    <thead class=" text-primary">
                      <tr>
                        <th>
                          Symbol
                        </th>
                        <th>
                          Balance
                        </th>
                        
                      </tr>
                    </thead>
                    <tbody>
						<?php
							
							require 'php-binance-api.php';
							$api = new Binance\API("WqZ7dNTAXpfcAHhuGrLHvGzyEGyxsujnzc9ONpBl7xkPPIeTcd4qcjbv0Cvt7oyo","pbvwTRsbMPJCBfcIy6Aab7jfRhTZM1760Jfe1RGFZjrIyI3hr0PTQXPI1mb84h7N");
							$api->useServerTime();
							$account = $api->account();
					
							foreach($account['balances'] as $row){
								if ($row[free] > 0 ){
									echo "<tr><td>".$row['asset']."</td><td>".$row['free']."</td></tr>";
									// ezafe kardan total
									if($row['asset'] == "BTC"){ $rowbtc = $row;}
									if($row['asset'] == "USDT"){ $rowusdt = $row;}
								}
							}
						?>
                      
                      
                    </tbody>
                  </table>

                <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></div>
              </div>
            </div>
			</div>
<div class="col-md-4">
            <div class="card ">
              <div class="card-header">
                <h4 class="card-title"> Last Orders</h4>
              </div>
              <div class="card-body">
                <div class="table-responsive ps">
                  <table class="table tablesorter " id="">
                    <thead class=" text-primary">
                      <tr>
                        <th>
                          Symbol
                        </th>
                        <th>
                          qty
                        </th>
						<th>
                          price
                        </th>
                        <th>
                          total
                        </th>
                      </tr>
                    </thead>
                    <tbody>
						<?php
							include 'config.php';
							$conn = new mysqli($servername, $username, $password, $dbname);
							// Check connection
							if ($conn->connect_error) {
							die("Connection failed: " . $conn->connect_error);
							}
							$sql = "SELECT `symbol`,`buy`,`origqty`,`percentage`,`fillsprice` FROM `orders` WHERE `status` = 3 ORDER BY `orders`.`id` DESC";
							
							$results = $conn->query($sql);
							$row = $results->fetch_all();
							$i=0;
							$j=0;
							
							while (  $i<40){
								$qtyt = 0;
								$sum = 0;
								$k=0;
								do{
									$k+=1;
									if ($row[$j][1] == 1){ 
										$qtyt += $row[$j][2];
										$sum += $row[$j][2]*$row[$j][4];
										
									}else{
										$qtyt -= $row[$j][2];
										$sum -= $row[$j][2]*$row[$j][4];	
									}
									$j+=1;
								}while($row[$j-1][1]==$row[$j][1] and $row[$j-1][0]==$row[$j][0]);
								$qtyq = $qtyt." (".$k.":".$row[$j-1][3].")";
								echo "<tr><td>".$row[$j-1][0]."</td><td>".$qtyq."</td><td>".round($sum/$qtyt,6)."</td><td>".round($qtyt*round($sum/$qtyt,3),2)."</td></tr>";
								$i+=1;
							}
						?>
                      
                      
                    </tbody>
                  </table>

                <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></div>
              </div>
            </div>
			</div>
<div class="col-md-4">
            <div class="card ">
              <div class="card-header">
                <h4 class="card-title"> Last Orders</h4>
              </div>
              <div class="card-body">
                <div class="table-responsive ps">
                  <table class="table tablesorter " id="">
                    <thead class=" text-primary">
                      <tr>
                        <th>
                          Symbol
                        </th>
                        <th>
                          qty
                        </th>
						<th>
                          price
                        </th>
                        
                      </tr>
                    </thead>
                    <tbody>
						<?php
							$sql = "SELECT `symbol`,`buy`,`origqty`,`percentage`,`fillsprice` FROM `orders` WHERE `status` = 3 ORDER BY `orders`.`id` DESC LIMIT 16";
							
							$results = $conn->query($sql);
							while ( $row = $results->fetch_assoc() ){
								if ($row['buy'] == 1){ 
									$qtyq = $row['origqty']." (".$row['percentage'].")";
								}else{
									$qtyq = "-".$row['origqty']." (".$row['percentage'].")";
								}
								echo "<tr><td>".$row['symbol']."</td><td>".$qtyq."</td><td>".$row['fillsprice']."</td></tr>";
								
							}
						?>
                      
                      
                    </tbody>
                  </table>

                <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></div>
              </div>
            </div>
			</div>
</div>
</div>
</div>
</div>

</body>

</html lang="en">