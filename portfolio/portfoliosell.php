<?php
session_start();
define('MyConst', TRUE);
$symbol=strtoupper($_GET['s']);

include_once("../functions/stockquote.php");

$name = getStockInfo("name", $symbol);
$quote = getStockInfo("price", $symbol);
$exchange = getStockInfo("exchange", $symbol);
$exchangename = getStockInfo("longexchange", $symbol);

echo "<h1>Hi, ".$_SESSION['login'].". <small>You are currently selling a stock.</small></h1>";

$servername = "";
$username = "";
$password = "\$";
$dbname = "";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    $sql = "SELECT * FROM stock_portfolio_".$_SESSION['login']."
			WHERE symbol='$symbol'"; //get amount of stock sellable
			
	$amount = 0;
    
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {     
			$amount += $row['quantity'];
	   }
    }else{
        die("You do not have the stock. How did you even manage to get to this page? contact Liang. Cheater.");
    }
	
	

?>
<h3>You have <span style="color:#ffff00"><?php echo $amount; ?></span> stock(s) of <span style="color:#9933ff"><?php echo $name; ?></span> <span style="color:#ff5050">(<?php echo strtoupper($symbol); ?>)</span></h3> 
<h3>How much would you want to sell?</h3><br />
<input onchange="update()" type="text" id="amt" value="<?php echo $amount; ?>" size="20"><br/ >
<div id="update"><h3>You are currently selling <span style="color:#398b3e"><?php echo $amount; ?></span> stock(s) at $<span style="color:#669900"><?php echo $quote; ?></span>
 each for a total of $<span style="color:#000"><?php echo $quote*$amount; ?></span> 
 from the <?php echo $exchangename;  ?><br></h3></div>
<br><button name="Confirm" onclick="stocks_sell_confirm()">Confirm</button><button name="cancel" onclick="load_portfolio()">Cancel</button>
<script type="text/javascript" id="stocks_sell_confirm">
stocks_sell_confirm = function() {
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
			eval(document.getElementById("load_account").innerHTML);
		}
	};
	xmlhttp.open("GET","upload_stocksell.php?q=" + document.getElementById('amt').value + "<?php echo "&s=".$symbol."&e=".$exchange; ?>",true);
    xmlhttp.send();
}
update = function(symbol,exchange) {
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
			document.getElementById("update").innerHTML=xmlhttp.responseText;
		}
	};
	xmlhttp.open("GET","updatesell.php?q=" + document.getElementById('amt').value + "<?php echo "&s=".$symbol."&e=".$exchange."&a=".$amount; ?>",true);
    xmlhttp.send();
}
</script>