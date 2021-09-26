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


if($qty > 0 ){
	
	for ($i = 0;$i < ($_POST['numinter']);$i++){
		$sql = "INSERT INTO `orders`(`delay`,`percentage`,`symbol`,`buy`, `top`,`status`,`id_create`) VALUES ('".(1+$_POST['firstdelay']+$_POST['delay']*$i)."','".$qty."','".$symbol."','".$sellOrBuy."','-1','0','".$id_c."')";
		$conn->query($sql);
	}
	echo "done!";
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
    <option value="2" selected>2%</option>
    <option value="5">5%</option>
    <option value="7">7%</option>
    <option value="10" >10%</option>
  </select>
  <input type="text" name="qtya" class="form-control"   value="">
        </div>
  <br>

  <div class="form-group">
 <label for="qtyid">number of interval</label>
 <input type="text" name="numinter" class="form-control"   value="2">
        </div>
  <br>

  <div class="form-group">
 <label for="qtyid">delay of interval </label>
 <input type="text" name="delay" class="form-control"   value="30">
        </div>
		
  <br>

  <div class="form-group">
 <label for="qtyid">first delay </label>
 <input type="text" name="firstdelay" class="form-control"   value="0">
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
							
							while (  $i<16){
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
								echo "<tr><td>".$row[$j-1][0]."</td><td>".$qtyq."</td><td>".round($sum/$qtyt,3)."</td></tr>";
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