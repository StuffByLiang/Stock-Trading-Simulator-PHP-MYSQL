<?php
session_start();
define('MyConst', TRUE);
date_default_timezone_set("America/Los_Angeles");

if(!isset($_SESSION['login'])){
    header("Location: http://stockgame.ca/login");   
}
?>
<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Stockgame.ca - Portfolio</title>

    <!-- Bootstrap core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>

    <!-- Custom styles -->
    <link href="../css/portfolio.css" rel="stylesheet">

  </head>
  
  <body id="page-top">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-md navbar-dark" id="mainNav">
      <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="/portfolio/">Stockgame.ca</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav text-uppercase ml-auto">
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="/rankings">Rankings</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="/portfolio">Portfolio</a>
            </li>
           <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="/login/logout.php">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    
    <div id="content" class="container">
        <div class="portfoliocenter">
            
            <div class="portfolio">
            
                <div class="row">
                
                <div class="col-sm-8" id="portfolio">
				
				<h1>LOADING... IF IT TAKES A WHILE YUR INTERNET SUCKS</h1>
				
                </div><!-- End div .col-sm-8 -->
        
                <div class="col-sm-4">
     
                    <div class="portfoliobox" id="account">
					
					<h1>LOADING... IF IT TAKES A WHILE YUR INTERNET SUCKS</h1>
					
                    </div><!-- End div .portfoliobbox -->
                    
                    <div class="portfoliobox">
                        
					
                     <?php include_once "stockbuybox.php" ?>
                        
                    </div><!-- End div .portfoliobbox -->
                </div><!-- End div .col-sm-4 -->
                
                </div><!-- End div .row -->
            
            </div><!-- End div .portfolio -->
         </div><!-- End div .portfoliocenter -->
        
        
    </div><!-- End div #content -->
    
    <!-- ajax start !-->
    <script>
    function stocks_buy() {
      var xmlhttp;
      var symbol = document.forms["buy_form"].symbol.value;
      var quantity = document.forms["buy_form"].quantity.value;
      var worth = document.forms["buy_form"].worth.value;
      
        if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
      } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp.onreadystatechange = function() {[]
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          document.getElementById("portfolio").innerHTML=xmlhttp.responseText;
          eval(document.getElementById("stocks_buy_confirm").innerHTML);
        }
      };
      xmlhttp.open("GET","portfoliobuy.php?s="+symbol+"&q="+quantity+"&w="+worth,true);
        xmlhttp.send();
    }
    function stocks_sell(symbol, exchange) {
      var xmlhttp;
      
        if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
      } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          document.getElementById("portfolio").innerHTML=xmlhttp.responseText;
          eval(document.getElementById("stocks_sell_confirm").innerHTML);
        }
      };
      xmlhttp.open("GET","portfoliosell.php?s="+symbol,true);
        xmlhttp.send();
    }
    function load_portfolio() {
      var xmlhttp;
        if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
      } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          document.getElementById("portfolio").innerHTML=xmlhttp.responseText;
        }
      };
      xmlhttp.open("GET","portfolio.php",true);
        xmlhttp.send();
    }
    function load_account() {
      var xmlhttp;
        if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
      } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          document.getElementById("account").innerHTML=xmlhttp.responseText;
        }
      };
      xmlhttp.open("GET","accountsummarybox.php",true);
        xmlhttp.send();
    }
    load_account();
    load_portfolio();
    </script>

    <!-- Bootstrap core JavaScript -->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Contact form JavaScript -->
    <script src="../js/jqBootstrapValidation.js"></script>
    <script src="../js/contact_me.js"></script>

    <!-- Custom scripts -->
    <script src="../js/main.js"></script>

  </body>

</html>