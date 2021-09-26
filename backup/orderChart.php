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

		<div id="chartContainer" style="height: 600px; width: 100%;"></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    
	  
	  
	  </div>
	  
	  
	   <!-- start footer -->
	   
	   
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