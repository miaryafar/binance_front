<!-- login test -->
<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
?>


<!-- $_Post form and del action -->
<?php
$symbol = "ATOMUSDT";
include "config.php";
$conn = new mysqli($servername, $username, $password, $dbname);
$lastidresulat = $conn->query("SELECT max(id) AS 'id' FROM `orders` WHERE `symbol` = '$symbol';");
while ( $lastid = $lastidresulat->fetch_assoc()){$lastidorder = $lastid['id'];}
$lastidresulat = $conn->query("SELECT id , pricemax FROM `".strtolower($symbol)."` ORDER BY id DESC LIMIT 1");
while ( $lastid = $lastidresulat->fetch_assoc()){$lastidid = $lastid['id']; $lastPrice = $lastid['pricemax'];}
if(isset($_POST['action'])){
	if($_POST['action']=='form'){ //check if form was submitted
		if ( ($_POST['buy'] == 0 or $_POST['buy'] == 1) and ($_POST['top'] == 0 or $_POST['top'] == 1)){
			
			if($_POST['after'] == 0 ){
				$sql = "INSERT INTO `orders`(`id_create`,`percentage`,`symbol`,`buy`, `top`, `price`, `delta`, `comment`) VALUES ('".$lastidid."','".$_POST['qty']."','$symbol','".$_POST['buy']."','".$_POST['top']."','".$_POST['price']."','".$_POST['delta']."','".$_POST['comment']."')";
				$conn->query($sql);
			}else{
				$sql = "INSERT INTO `orders`(`id_create`,`percentage`,`symbol`,`buy`, `top`, `pricedelta`, `delta`, `comment`,`status`, `after`, `gafter`) VALUES ('".$lastidid."','".$_POST['qty']."','$symbol','".$_POST['buy']."','".$_POST['top']."','".$_POST['price']."','".$_POST['delta']."','".$_POST['comment']."','-1','".$_POST['after']."','".$_POST['gafter']."')";
				$conn->query($sql);
			}
			$last_idd = $conn->insert_id;
			echo '<div class="alert alert-info alert-with-icon" data-notify="container"><button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close"><i class="tim-icons icon-simple-remove"></i> </button> <span data-notify="icon" class="tim-icons icon-bell-55"></span> <span data-notify="message">your price submited by ID : '.$last_idd.'</span> </div>';
			
	  
		}
	}else if($_POST['action']=='updateform'){ //check if form was submitted
		$sql="UPDATE `orders` SET `buy`='".$_POST['buy']."',`top`='".$_POST['top']."',`percentage`='".$_POST['qty']."',`price`='".$_POST['price']."',`delta`='".$_POST['delta']."',`after`='".$_POST['after']."',`gafter`='".$_POST['gafter']."',`comment`='".$_POST['comment']."' WHERE `id`='".$_POST['id']."'";
		$conn->query($sql);
		echo '<div class="alert alert-info alert-with-icon" data-notify="container"><button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close"><i class="tim-icons icon-simple-remove"></i> </button> <span data-notify="icon" class="tim-icons icon-bell-55"></span> <span data-notify="message">your order update</span> </div>';

	}else if ($_POST['action']=='del'){
		if ($_POST['submit']=='Delete Last'){
			//$sql = "DELETE FROM `orders` WHERE `symbol` = '$symbol' ORDER BY `orders`.`id` DESC  LIMIT 1";
			$sql = "DELETE FROM `orders` WHERE `id` = '".$_POST['id']."' ;";
			$conn->query($sql);
			echo "delet last order";
		}
		if ($_POST['submit']=='find'){
			$sql = "SELECT * FROM `orders` WHERE `id` = '".$_POST['id']."' ;";
			$findResult = $conn->query($sql)->fetch_assoc();
			echo "hi";
			echo $findResult['delta'];
			echo "-";
		}
		echo "bye";
	}else if ($_POST['action']=='delall'){
		$sql = "DELETE FROM `orders` WHERE `symbol` = '$symbol' AND (`status` = '0'  OR `status` = '-1');" ;
		$conn->query($sql);
		echo "delet All orders";
	}else if ($_POST['action']=='edite'){
		$sql = "DELETE FROM `orders` WHERE `symbol` = '$symbol' AND (`status` = '0'  OR `status` = '-1');" ;
		$conn->query($sql);
		echo "edit orders";
	}else if ($_POST['action']=='afterform'){
		if ($_POST['buy']==1){
			if($_POST['pricet'] != 0 or $_POST['deltaOfPricet'] !=0){
			$sql = "INSERT INTO `orders`(`id_create`,`percentage`,`symbol`,`buy`,`top`, `price`, `pricedelta`, `delta`, `comment`,`status`, `after`, `gafter`) VALUES ('".$lastidid."','".$_POST['qty']."','$symbol','0','1','".$_POST['pricet']."','".$_POST['deltaOfPricet']."','".$_POST['delta']."','".$_POST['comment']."','-1','".$_POST['after']."','".$_POST['gafter']."')";
			$conn->query($sql);
			}
			if($_POST['priced'] != 0 or $_POST['deltaOfPriced'] !=0){
			$sql = "INSERT INTO `orders`(`id_create`,`percentage`,`symbol`,`buy`,`top`, `price`, `pricedelta`, `delta`, `comment`,`status`, `after`, `gafter`) VALUES ('".$lastidid."','".$_POST['qty']."','$symbol','0','0','".$_POST['priced']."','".$_POST['deltaOfPriced']."','0','".$_POST['comment']."','-1','".$_POST['after']."','".$_POST['gafter']."')";
			$conn->query($sql);
			}	
		}else{
			if($_POST['pricet'] != 0 or $_POST['deltaOfPricet'] !=0){
			$sql = "INSERT INTO `orders`(`id_create`,`percentage`,`symbol`,`buy`,`top`, `price`, `pricedelta`, `delta`, `comment`,`status`, `after`, `gafter`) VALUES ('".$lastidid."','".$_POST['qty']."','$symbol','1','1','".$_POST['pricet']."','".$_POST['deltaOfPricet']."','0','".$_POST['comment']."','-1','".$_POST['after']."','".$_POST['gafter']."')";
			$conn->query($sql);
			}
			if($_POST['priced'] != 0 or $_POST['deltaOfPriced'] !=0){
			$sql = "INSERT INTO `orders`(`id_create`,`percentage`,`symbol`,`buy`,`top`, `price`, `pricedelta`, `delta`, `comment`,`status`, `after`, `gafter`) VALUES ('".$lastidid."','".$_POST['qty']."','$symbol','1','0','".$_POST['priced']."','".$_POST['deltaOfPriced']."','".$_POST['delta']."','".$_POST['comment']."','-1','".$_POST['after']."','".$_POST['gafter']."')";
			$conn->query($sql);
			}
		}
		
	}else if ($_POST['action']=='delafter'){
		if($_POST['id']>0){
		$sql = "DELETE FROM `orders` WHERE `symbol` = '$symbol' AND `after` = '".$_POST['id']."';" ;
		$conn->query($sql);
		echo "delet after orders".$_POST['id'];
		}
	}else if ($_POST['action']=='order_with_profit'){
		if ( ($_POST['buy'] == 0 or $_POST['buy'] == 1) and ($_POST['top'] == 0 or $_POST['top'] == 1)){
			
			if($_POST['after'] == 0 ){
				$sql = "INSERT INTO `orders`(`id_create`,`percentage`,`symbol`,`buy`, `top`, `price`, `delta`, `comment`) VALUES ('".$lastidid."','".$_POST['qty']."','$symbol','".$_POST['buy']."','".$_POST['top']."','".$_POST['price']."','".$_POST['delta']."','".$_POST['comment']."')";
				$conn->query($sql);
			}else{
				$sql = "INSERT INTO `orders`(`id_create`,`percentage`,`symbol`,`buy`, `top`, `pricedelta`, `delta`, `comment`,`status`, `after`, `gafter`) VALUES ('".$lastidid."','".$_POST['qty']."','$symbol','".$_POST['buy']."','".$_POST['top']."','".$_POST['price']."','".$_POST['delta']."','".$_POST['comment']."','-1','".$_POST['after']."','".$_POST['gafter']."')";
				$conn->query($sql);
			}
			$last_idd = $conn->insert_id;
			echo '<div class="alert alert-info alert-with-icon" data-notify="container"><button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close"><i class="tim-icons icon-simple-remove"></i> </button> <span data-notify="icon" class="tim-icons icon-bell-55"></span> <span data-notify="message">your price submited by ID : '.$last_idd.'</span> </div>';
			
			
			if ($_POST['buy']==1){
			$sql = "INSERT INTO `orders`(`id_create`,`percentage`,`symbol`,`buy`,`top`, `price`, `pricedelta`, `delta`, `comment`,`status`, `after`, `gafter`) VALUES ('".$lastidid."','".$_POST['qty']."','$symbol','0','1','".$_POST['pricet']."','".$_POST['deltaOfPricet']."','".$_POST['delta2']."','".$_POST['comment']."','-1','".$last_idd."','".$_POST['gafter2']."')";
			$conn->query($sql);
			$sql = "INSERT INTO `orders`(`id_create`,`percentage`,`symbol`,`buy`,`top`, `price`, `pricedelta`, `delta`, `comment`,`status`, `after`, `gafter`) VALUES ('".$lastidid."','".$_POST['qty']."','$symbol','0','0','".$_POST['priced']."','".$_POST['deltaOfPriced']."','0','".$_POST['comment']."','-1','".$last_idd."','".$_POST['gafter2']."')";
			$conn->query($sql);
			}else{
				$sql = "INSERT INTO `orders`(`id_create`,`percentage`,`symbol`,`buy`,`top`, `price`, `pricedelta`, `delta`, `comment`,`status`, `after`, `gafter`) VALUES ('".$lastidid."','".$_POST['qty']."','$symbol','1','1','".$_POST['pricet']."','".$_POST['deltaOfPricet']."','0','".$_POST['comment']."','-1','".$last_idd."','".$_POST['gafter2']."')";
				$conn->query($sql);
				$sql = "INSERT INTO `orders`(`id_create`,`percentage`,`symbol`,`buy`,`top`, `price`, `pricedelta`, `delta`, `comment`,`status`, `after`, `gafter`) VALUES ('".$lastidid."','".$_POST['qty']."','$symbol','1','0','".$_POST['priced']."','".$_POST['deltaOfPriced']."','".$_POST['delta2']."','".$_POST['comment']."','-1','".$last_idd."','".$_POST['gafter2']."')";
				$conn->query($sql);
			}
			
		}
		
	}
	$conn->close();	
}

?>





<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="assets/img/favicon.png">
  <title>
    Hi Milad
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,600,700,800" rel="stylesheet" />
  <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
  <!-- Nucleo Icons -->
  <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link href="assets/css/black-dashboard.css?v=1.0.0" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="assets/demo/demo.css" rel="stylesheet" />
</head>

<body class="">
  <div class="wrapper">
       
	<?php include("sidebar.php"); ?>

   <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-absolute navbar-transparent">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <div class="navbar-toggle d-inline">
              <button type="button" class="navbar-toggler">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </button>
            </div>
            <a class="navbar-brand" href="javascript:void(0)">User Profile</a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
          </button>
          <div class="collapse navbar-collapse" id="navigation">
            <ul class="navbar-nav ml-auto">
              <li class="search-bar input-group">
                <button class="btn btn-link" id="search-button" data-toggle="modal" data-target="#searchModal"><i class="tim-icons icon-zoom-split" ></i>
                  <span class="d-lg-none d-md-block">Search</span>
                </button>
              </li>
              <li class="dropdown nav-item">
                <a href="javascript:void(0)" class="dropdown-toggle nav-link" data-toggle="dropdown">
                  <div class="notification d-none d-lg-block d-xl-block"></div>
                  <i class="tim-icons icon-sound-wave"></i>
                  <p class="d-lg-none">
                    Notifications
                  </p>
                </a>
                <ul class="dropdown-menu dropdown-menu-right dropdown-navbar">
                  <li class="nav-link"><a href="#" class="nav-item dropdown-item">Mike John responded to your email</a></li>
                  <li class="nav-link"><a href="javascript:void(0)" class="nav-item dropdown-item">You have 5 more tasks</a></li>
                  <li class="nav-link"><a href="javascript:void(0)" class="nav-item dropdown-item">Your friend Michael is in town</a></li>
                  <li class="nav-link"><a href="javascript:void(0)" class="nav-item dropdown-item">Another notification</a></li>
                  <li class="nav-link"><a href="javascript:void(0)" class="nav-item dropdown-item">Another one</a></li>
                </ul>
              </li>
              <li class="dropdown nav-item">
                <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                  <div class="photo">
                    <img src="assets/img/anime3.png" alt="Profile Photo">
                  </div>
                  <b class="caret d-none d-lg-block d-xl-block"></b>
                  <p class="d-lg-none">
                    Log out
                  </p>
                </a>
                <ul class="dropdown-menu dropdown-navbar">
                  <li class="nav-link"><a href="profile.php" class="nav-item dropdown-item">Profile</a></li>
                  <li class="nav-link"><a href="javascript:void(0)" class="nav-item dropdown-item">Settings</a></li>
                  <li class="dropdown-divider"></li>
                  <li class="nav-link"><a href="logout.php" class="nav-item dropdown-item">Log out</a></li>
                </ul>
              </li>
              <li class="separator d-lg-none"></li>
            </ul>
          </div>
        </div>
      </nav>
      <div class="modal modal-search fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <input type="text" class="form-control" id="inlineFormInputGroup" placeholder="SEARCH">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i class="tim-icons icon-simple-remove"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
      <!-- End Navbar -->
      <div class="content">
        <div class="row">
          <div class="col-md-8">
            <div class="card">
              <div class="card-header">
                <h5 class="title">Insert Order   |   last Price =  <?php echo $lastPrice; ?></h5>
              </div>
              <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
				<div class="card-body">
                
                  <div class="row">
                    <div class="col-md-5 pr-md-1">
                      <div class="form-group">
                        <label>Symbol</label>
                        <input type="text" name="symbol" class="form-control" disabled="" placeholder="Company" value="ATOMUSDT">
                      </div>
                    </div>
                    <div class="col-md-3 px-md-1">
                      <div class="form-group">
                        <label>Buy</label>
                        <input type="number" name="buy" class="form-control" placeholder="1-buy | 0-Sell"  required>
                      </div>
                    </div>
                    <div class="col-md-4 pl-md-1">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Top</label>
                        <input type="number" name="top" class="form-control" placeholder="1-top | 0-down" required>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 pr-md-1">
                      <div class="form-group">
                        <label>price</label>
                        <input type="number" step="0.0001" name="price" class="form-control" placeholder="" required>
                      </div>
                    </div>
                    <div class="col-md-6 pl-md-1">
                      <div class="form-group">
                        <label>delta</label>
                        <input type="number" step="0.001" name="delta" class="form-control" value="0" required>
                      </div>
                    </div>
                  </div>
				  <div class="row">
                    <div class="col-md-4 pr-md-1">
                      <div class="form-group">
                        <label>Quantity %</label>
                        <input type="number" step="0.001" name="qty" class="form-control" placeholder="--%" value="100" required>
                      </div>
                    </div>
                    <div class="col-md-4 pr-md-1">
                      <div class="form-group">
                        <label>After</label>
                        <input type="number" step="1" name="after" class="form-control" placeholder="<?php echo $lastidorder; ?>" value="0" required>
                      </div>
                    </div>
                    <div class="col-md-4 pr-md-1">
                      <div class="form-group">
                        <label>Group after</label>
                        <input type="number" step="1" name="gafter" class="form-control" placeholder="--%" value="0" required>
                      </div>
                    </div>
				  </div>
				  
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Comment</label>
                        <input type="text" name="comment" class="form-control" placeholder="توضیحات" value="">
                      </div>
                    </div>
                  </div>
				  

					<!--
                    <div class="col-md-4 px-md-1">
                      <div class="form-group">
                        <label>Country</label>
                        <input type="text" class="form-control" placeholder="Country" value="Andrew">
                      </div>
                    </div>
                    <div class="col-md-4 pl-md-1">
                      <div class="form-group">
                        <label>Postal Code</label>
                        <input type="number" class="form-control" placeholder="ZIP Code">
                      </div>
                    </div>
                  </div>
				  
                  <div class="row">
                    <div class="col-md-8">
                      <div class="form-group">
                        <label>Comment</label>
                        <textarea rows="4" cols="80" class="form-control" placeholder="Here can be your description" value="Mike"></textarea>
                      </div>
                    </div>
                  </div>
				  -->
                
				</div>
              <div class="card-footer">
				<input type="hidden" name="action" value="form">
                <input type="submit" class="btn btn-fill btn-primary">
              </div>
			 </form>
            </div>
          </div>
		  
		  
		            <div class="col-md-8">
            <div class="card">
              <div class="card-header">
                <h5 class="title">Insert after   |   last Price =  <?php echo $lastPrice; ?></h5>
              </div>
              <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
				<div class="card-body">
                
                  <div class="row">
                    <div class="col-md-5 pr-md-1">
                      <div class="form-group">
                        <label>Symbol</label>
                        <input type="text" name="symbol" class="form-control" disabled="" placeholder="Company" value="ATOMUSDT">
                      </div>
                    </div>
                    <div class="col-md-3 px-md-1">
                      <div class="form-group">
                        <label>after</label>
                        <input type="number" step="1" name="after" class="form-control" placeholder="<?php echo $lastidorder; ?>" value="0" required>
                      </div>
                    </div>
                    <div class="col-md-4 pl-md-1">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Buy</label>
                        <input type="number" name="buy" class="form-control" placeholder="1-buy | 0-Sell" required>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 pr-md-1">
                      <div class="form-group">
                        <label>price Top</label>
                        <input type="number" step="0.001" name="pricet" class="form-control" placeholder=""  value="0" required>
                      </div>
                    </div>
                    <div class="col-md-6 pl-md-1">
                      <div class="form-group">
                        <label>OR delta of price</label>
                        <input type="number" step="0.001" name="deltaOfPricet" class="form-control" value="0" required>
                      </div>
                    </div>
                  </div>
				                    <div class="row">
                    <div class="col-md-6 pr-md-1">
                      <div class="form-group">
                        <label>price down</label>
                        <input type="number" step="0.001" name="priced" class="form-control" placeholder=""  value="0" required>
                      </div>
                    </div>
                    <div class="col-md-6 pl-md-1">
                      <div class="form-group">
                        <label>OR delta of price</label>
                        <input type="number" step="0.001" name="deltaOfPriced" class="form-control" value="0" required>
                      </div>
                    </div>
                  </div>
				  <div class="row">
                    <div class="col-md-4 pr-md-1">
                      <div class="form-group">
                        <label>Quantity %</label>
                        <input type="number" step="0.001" name="qty" class="form-control" placeholder="--%" value="100" required>
                      </div>
                    </div>
                    <div class="col-md-4 pr-md-1">
                      <div class="form-group">
                        <label>delta for profit</label>
                        <input type="number" step="1" name="delta" class="form-control" placeholder="<?php echo $lastidorder; ?>" value="0" required>
                      </div>
                    </div>
                    <div class="col-md-4 pr-md-1">
                      <div class="form-group">
                        <label>Group after</label>
                        <input type="number" step="1" name="gafter" class="form-control" placeholder="--%" value="1" required>
                      </div>
                    </div>
				  </div>
				  
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Comment</label>
                        <input type="text" name="comment" class="form-control" placeholder="توضیحات" value="">
                      </div>
                    </div>
                  </div>
				  
                
				</div>
              <div class="card-footer">
				<input type="hidden" name="action" value="afterform">
                <input type="submit" class="btn btn-fill btn-primary">
              </div>
			 </form>
            </div>
          </div>

			
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
							echo "<tr><td>TotalBTC</td><td>".($rowbtc['free']+$rowusdt['free']/$lastPrice)."</td></tr>";
						?>
                      
                      
                    </tbody>
                  </table>

                <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></div>
              </div>
            </div>
			</div>
        </div>
		
		<div class="row">
				
				</div>
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
				
				 <div class="row">
				<input type="hidden" name="action" value="del">
				<div class="col-md-1 pr-md-4">
				<label>Order ID</label>
				</div>
				<div class="col-md-2 pr-md-1">
				<input type="number" step="1" name="id" class="form-control" value="<?php echo $lastidorder; ?>" required>
				</div>
				<div class="col-md-4 pr-md-1">
                <input type="submit" name="submit" value="Delete Last" class="btn btn-fill btn-primary">
                <input type="submit" name="submit" value="find" class="btn btn-fill btn-primary">
				
              </div>
              </div>
			 </form>
				<div class="row">
				</div>

				<div class="row">
				  
				  </div>

				<div class="row">
				</div>	
				<div class="col-md-8">
				<div class="card">
				<div class="card-header">
                <h5 class="title">update ID number :  <?php echo $findResult['id']; ?></h5>
				</div>				
			    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
				<div class="card-body">
                
                  <div class="row">
                    <div class="col-md-5 pr-md-1">
                      <div class="form-group">
                        <label>Symbol</label>
                        <input type="text" name="symbol" class="form-control" disabled="" placeholder="Company" value="ATOMUSDT">
                      </div>
                    </div>
                    <div class="col-md-3 px-md-1">
                      <div class="form-group">
                        <label>Buy</label>
                        <input type="number" name="buy" class="form-control" placeholder="1-buy | 0-Sell" value="<?php echo $findResult['buy']; ?>" required>
                      </div>
                    </div>
                    <div class="col-md-4 pl-md-1">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Top</label>
                        <input type="number" name="top" class="form-control" placeholder="1-top | 0-down" value="<?php echo $findResult['top']; ?>" required>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 pr-md-1">
                      <div class="form-group">
                        <label>price</label>
                        <input type="number" step="0.001" name="price" class="form-control" placeholder="" value="<?php echo $findResult['price']; ?>" required>
                      </div>
                    </div>
                    <div class="col-md-6 pl-md-1">
                      <div class="form-group">
                        <label>delta</label>
                        <input type="number" step="0.001" name="delta" class="form-control" value="<?php echo $findResult['delta']; ?>" required>
                      </div>
                    </div>
                  </div>
				  <div class="row">
                    <div class="col-md-4 pr-md-1">
                      <div class="form-group">
                        <label>Quantity %</label>
                        <input type="number" step="0.001" name="qty" class="form-control" placeholder="--%" value="<?php echo $findResult['percentage']; ?>" required>
                      </div>
                    </div>
                    <div class="col-md-4 pr-md-1">
                      <div class="form-group">
                        <label>After</label>
                        <input type="number" step="1" name="after" class="form-control" placeholder="<?php echo $lastidorder; ?>" value="<?php echo $findResult['after']; ?>" required>
                      </div>
                    </div>
                    <div class="col-md-4 pr-md-1">
                      <div class="form-group">
                        <label>Group after</label>
                        <input type="number" step="1" name="gafter" class="form-control" placeholder="--%" value="<?php echo $findResult['gafter']; ?>" required>
                      </div>
                    </div>
				  </div>
				  
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Comment</label>
                        <input type="text" name="comment" class="form-control" placeholder="توضیحات" value="<?php echo $findResult['comment']; ?>">
                      </div>
                    </div>
                  </div>
                
				</div>
              <div class="card-footer">
				<input type="hidden" name="action" value="updateform">
				<input type="hidden" name="id" value="<?php echo $findResult['id']; ?>">
                <input type="submit" value="update" class="btn btn-fill btn-primary">
              </div>
			 </form>
			 </div>	
			 </div>	
			
		
		
		<div class="col-md-8">
            <div class="card">
              <div class="card-header">
                <h5 class="title">Insert Order   |   last Price =  <?php echo $lastPrice; ?></h5>
              </div>
              <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
				<div class="card-body">
                
                  <div class="row">
                    <div class="col-md-5 pr-md-1">
                      <div class="form-group">
                        <label>Symbol</label>
                        <input type="text" name="symbol" class="form-control" disabled="" placeholder="Company" value="ATOMUSDT">
                      </div>
                    </div>
                    <div class="col-md-3 px-md-1">
                      <div class="form-group">
                        <label>Buy</label>
                        <input type="number" name="buy" class="form-control" placeholder="1-buy | 0-Sell"  required>
                      </div>
                    </div>
                    <div class="col-md-4 pl-md-1">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Top</label>
                        <input type="number" name="top" class="form-control" placeholder="1-top | 0-down" required>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 pr-md-1">
                      <div class="form-group">
                        <label>price</label>
                        <input type="number" step="0.001" name="price" class="form-control" placeholder="" required>
                      </div>
                    </div>
                    <div class="col-md-6 pl-md-1">
                      <div class="form-group">
                        <label>delta</label>
                        <input type="number" step="0.001" name="delta" class="form-control" value="0" required>
                      </div>
                    </div>
                  </div>
				  <div class="row">
                    <div class="col-md-4 pr-md-1">
                      <div class="form-group">
                        <label>Quantity %</label>
                        <input type="number" step="0.001" name="qty" class="form-control" placeholder="--%" value="100" required>
                      </div>
                    </div>
                    <div class="col-md-4 pr-md-1">
                      <div class="form-group">
                        <label>After</label>
                        <input type="number" step="1" name="after" class="form-control" placeholder="<?php echo $lastidorder; ?>" value="0" required>
                      </div>
                    </div>
                    <div class="col-md-4 pr-md-1">
                      <div class="form-group">
                        <label>Group after</label>
                        <input type="number" step="1" name="gafter" class="form-control" placeholder="--%" value="0" required>
                      </div>
                    </div>
				  </div>
				  
                  <div class="row">
                    <div class="col-md-6 pr-md-1">
                      <div class="form-group">
                        <label>price Top</label>
                        <input type="number" step="0.001" name="pricet" class="form-control" placeholder=""  value="0" required>
                      </div>
                    </div>
                    <div class="col-md-6 pl-md-1">
                      <div class="form-group">
                        <label>OR delta of price</label>
                        <input type="number" step="0.001" name="deltaOfPricet" class="form-control" value="0" required>
                      </div>
                    </div>
                  </div>
				                    <div class="row">
                    <div class="col-md-6 pr-md-1">
                      <div class="form-group">
                        <label>price down</label>
                        <input type="number" step="0.001" name="priced" class="form-control" placeholder=""  value="0" required>
                      </div>
                    </div>
                    <div class="col-md-6 pl-md-1">
                      <div class="form-group">
                        <label>OR delta of price</label>
                        <input type="number" step="0.001" name="deltaOfPriced" class="form-control" value="0" required>
                      </div>
                    </div>
                  </div>
				  <div class="row">
                    
                    <div class="col-md-4 pr-md-1">
                      <div class="form-group">
                        <label>delta for profit</label>
                        <input type="number" step="1" name="delta2" class="form-control" placeholder="<?php echo $lastidorder; ?>" value="0" required>
                      </div>
                    </div>
                    <div class="col-md-4 pr-md-1">
                      <div class="form-group">
                        <label>Group after</label>
                        <input type="number" step="1" name="gafter2" class="form-control" placeholder="--%" value="1" required>
                      </div>
                    </div>
				  </div>
				  
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Comment</label>
                        <input type="text" name="comment" class="form-control" placeholder="توضیحات" value="">
                      </div>
                    </div>
                  </div>
				  
                
				</div>
              <div class="card-footer">
				<input type="hidden" name="action" value="order_with_profit">
                <input type="submit" class="btn btn-fill btn-primary">
              </div>
			 </form>
            </div>
          </div>
		
		
		<div class="row">
		  <div class="card ">
              <div class="card-header">
                <h4 class="card-title"> Simple Table</h4>
              </div>
              <div class="card-body" style="overflow-x:auto;">
                <div class="table-responsive ps" style="overflow-x:scroll;">
                  <table class="table tablesorter " id="">
                    <thead class=" text-primary">
                      <tr>
						<th>
                          id
                        </th>
                        <th>
                          stat
                        </th>
						<th>
                          Buy
                        </th>
                        <th>
                          price
                        </th>
                        <th>
                          Top
                        </th>
                        <th >
                          delta
                        </th>
						<th >
                          %
                        </th>
						<th >
                          fill
                        </th>
						<th >
                          comment
                        </th>
                      </tr>
                    </thead>
                    <tbody>
						<?php
							include "config.php";
							$conn = new mysqli($servername, $username, $password, $dbname);
							//$sql = "SELECT * FROM `orders` WHERE symbol = '$symbol'";
							$sql = "SELECT * FROM `orders` WHERE symbol = '$symbol' ORDER BY `status` DESC ,`id_fill` DESC ";
							$results = $conn->query($sql);
							while ( $row = $results->fetch_assoc()){
								if ($row['status'] == 0){
									if ($row['buy'] ==0){
										$color = '#330000';
									}else{
										$color = '#003300';
									}
									
								}else{
									if ($row['buy'] ==0){
										$color = '#800000';
									}else{
										$color = '#006600';
									}
								}
			
								echo "<tr style='background-color:".$color."'><td>".$row['id']."</td><td>".$row['status']."</td><td>".$row['buy']."</td><td>".$row['price']."</td><td>".$row['top']."</td><td>".$row['delta']."</td><td>".$row['percentage']."</td><td>".$row['fillsprice']."</td><td>".$row['comment']."</td></tr>";
                          
                        
                      
							}
						?>
                      
                      
                    </tbody>
                  </table>
				  
				 <div class="row">
				<div class="card-header">
                <h5 class="title">Del after ID</h5>
				</div>
				</div>
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
				
				 <div class="row">
				<input type="hidden" name="action" value="delafter">
				<div class="col-md-1 pr-md-4">
				<label>Order after ID</label>
				</div>
				<div class="col-md-2 pr-md-1">
				<input type="number" step="1" name="id" class="form-control" value="<?php echo $lastidorder; ?>" required>
				</div>
				<div class="col-md-4 pr-md-1">
                <input type="submit" name="submit" value="Delete after" class="btn btn-fill btn-primary">
              
				
              </div>
              </div>
			  </form>
				
			 	<div class="row">
				<div class="card-header">
                <h5 class="title">Del All Open Order !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!</h5>
				</div>
				</div>	
			 
			 <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
				
				 <div class="card-footer">
				<input type="hidden" name="action" value="delall">
                <input type="submit" value="Delete All" class="btn btn-fill btn-primary">
              </div>
			 </form>
                <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></div>
              </div>
            </div>
			</div>
      </div>
      <footer class="footer">
        <div class="container-fluid">
          <ul class="nav">
            <li class="nav-item">
              <a href="javascript:void(0)" class="nav-link">
                Creative Tim
              </a>
            </li>
            <li class="nav-item">
              <a href="javascript:void(0)" class="nav-link">
                About Us
              </a>
            </li>
            <li class="nav-item">
              <a href="javascript:void(0)" class="nav-link">
                Blog
              </a>
            </li>
          </ul>
          <div class="copyright">
            ©
            <script>
              document.write(new Date().getFullYear())
            </script>2018 made with <i class="tim-icons icon-heart-2"></i> by
            <a href="javascript:void(0)" target="_blank">Creative Tim</a> for a better web.
          </div>
        </div>
      </footer>
    </div>
  </div>
  <div class="fixed-plugin">
    <div class="dropdown show-dropdown">
      <a href="#" data-toggle="dropdown">
        <i class="fa fa-cog fa-2x"> </i>
      </a>
      <ul class="dropdown-menu">
        <li class="header-title"> Sidebar Background</li>
        <li class="adjustments-line">
          <a href="javascript:void(0)" class="switch-trigger background-color">
            <div class="badge-colors text-center">
              <span class="badge filter badge-primary active" data-color="primary"></span>
              <span class="badge filter badge-info" data-color="blue"></span>
              <span class="badge filter badge-success" data-color="green"></span>
            </div>
            <div class="clearfix"></div>
          </a>
        </li>
        <li class="adjustments-line text-center color-change">
          <span class="color-label">LIGHT MODE</span>
          <span class="badge light-badge mr-2"></span>
          <span class="badge dark-badge ml-2"></span>
          <span class="color-label">DARK MODE</span>
        </li>
        <li class="button-container">
          <a href="https://www.creative-tim.com/product/black-dashboard" target="_blank" class="btn btn-primary btn-block btn-round">Download Now</a>
          <a href="https://demos.creative-tim.com/black-dashboard/docs/1.0/getting-started/introduction.html" target="_blank" class="btn btn-default btn-block btn-round">
            Documentation
          </a>
        </li>
        <li class="header-title">Thank you for 95 shares!</li>
        <li class="button-container text-center">
          <button id="twitter" class="btn btn-round btn-info"><i class="fab fa-twitter"></i> &middot; 45</button>
          <button id="facebook" class="btn btn-round btn-info"><i class="fab fa-facebook-f"></i> &middot; 50</button>
          <br>
          <br>
          <a class="github-button" href="https://github.com/creativetimofficial/black-dashboard" data-icon="octicon-star" data-size="large" data-show-count="true" aria-label="Star ntkme/github-buttons on GitHub">Star</a>
        </li>
      </ul>
    </div>
  </div>
  <!--   Core JS Files   -->
  <script src="assets/js/core/jquery.min.js"></script>
  <script src="assets/js/core/popper.min.js"></script>
  <script src="assets/js/core/bootstrap.min.js"></script>
  <script src="assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <!--  Google Maps Plugin    -->
  <!-- Place this tag in your head or just before your close body tag. -->
  <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
  <!-- Chart JS -->
  <script src="assets/js/plugins/chartjs.min.js"></script>
  <!--  Notifications Plugin    -->
  <script src="assets/js/plugins/bootstrap-notify.js"></script>
  <!-- Control Center for Black Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="assets/js/black-dashboard.min.js?v=1.0.0"></script><!-- Black Dashboard DEMO methods, don't include it in your project! -->
  <script src="assets/demo/demo.js"></script>
  <script>
    $(document).ready(function() {
      $().ready(function() {
        $sidebar = $('.sidebar');
        $navbar = $('.navbar');
        $main_panel = $('.main-panel');

        $full_page = $('.full-page');

        $sidebar_responsive = $('body > .navbar-collapse');
        sidebar_mini_active = true;
        white_color = false;

        window_width = $(window).width();

        fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();



        $('.fixed-plugin a').click(function(event) {
          if ($(this).hasClass('switch-trigger')) {
            if (event.stopPropagation) {
              event.stopPropagation();
            } else if (window.event) {
              window.event.cancelBubble = true;
            }
          }
        });

        $('.fixed-plugin .background-color span').click(function() {
          $(this).siblings().removeClass('active');
          $(this).addClass('active');

          var new_color = $(this).data('color');

          if ($sidebar.length != 0) {
            $sidebar.attr('data', new_color);
          }

          if ($main_panel.length != 0) {
            $main_panel.attr('data', new_color);
          }

          if ($full_page.length != 0) {
            $full_page.attr('filter-color', new_color);
          }

          if ($sidebar_responsive.length != 0) {
            $sidebar_responsive.attr('data', new_color);
          }
        });

        $('.switch-sidebar-mini input').on("switchChange.bootstrapSwitch", function() {
          var $btn = $(this);

          if (sidebar_mini_active == true) {
            $('body').removeClass('sidebar-mini');
            sidebar_mini_active = false;
            blackDashboard.showSidebarMessage('Sidebar mini deactivated...');
          } else {
            $('body').addClass('sidebar-mini');
            sidebar_mini_active = true;
            blackDashboard.showSidebarMessage('Sidebar mini activated...');
          }

          // we simulate the window Resize so the charts will get updated in realtime.
          var simulateWindowResize = setInterval(function() {
            window.dispatchEvent(new Event('resize'));
          }, 180);

          // we stop the simulation of Window Resize after the animations are completed
          setTimeout(function() {
            clearInterval(simulateWindowResize);
          }, 1000);
        });

        $('.switch-change-color input').on("switchChange.bootstrapSwitch", function() {
          var $btn = $(this);

          if (white_color == true) {

            $('body').addClass('change-background');
            setTimeout(function() {
              $('body').removeClass('change-background');
              $('body').removeClass('white-content');
            }, 900);
            white_color = false;
          } else {

            $('body').addClass('change-background');
            setTimeout(function() {
              $('body').removeClass('change-background');
              $('body').addClass('white-content');
            }, 900);

            white_color = true;
          }


        });

        $('.light-badge').click(function() {
          $('body').addClass('white-content');
        });

        $('.dark-badge').click(function() {
          $('body').removeClass('white-content');
        });
      });
    });
  </script>
  <script src="https://cdn.trackjs.com/agent/v3/latest/t.js"></script>
  <script>
    window.TrackJS &&
      TrackJS.install({
        token: "ee6fab19c5a04ac1a32a645abde4613a",
        application: "black-dashboard-free"
      });
  </script>
</body>

</html>