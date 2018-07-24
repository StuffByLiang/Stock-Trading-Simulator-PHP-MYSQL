<?php
session_start();

date_default_timezone_set("America/New_York");

if(idate("w")==0||idate("w")==6){//check if weekend
	die("Can't buy! It's the weekend. <a href=\"javascript:load_portfolio()\">Return to safety!</a>");
}

if(idate("H")*60+idate("i")<540){ //check if time is from 00:00 - 09:30
	die("Can't buy! Market still closed. Market opens in ". (540 -(idate("H")*60+idate("i")))."minutes . <a href=\"javascript:load_portfolio()\">Return to safety!</a>");
}

if(idate("H")*60+idate("i")>960){ //check if time is from 16:00 - 24:00
	die("Can't buy! Market has closed. Market closed ". ((idate("H")*60+idate("i")) - 960) ." minutes ago. <a href=\"javascript:load_portfolio()\">Return to safety!</a>");
} 

define('MyConst', TRUE);
$symbol=filter_var(strtoupper($_GET['s']),FILTER_SANITIZE_SPECIAL_CHARS);
$exchange=filter_var($_GET['e'],FILTER_SANITIZE_SPECIAL_CHARS);
$quantity=filter_var($_GET['q'],FILTER_SANITIZE_SPECIAL_CHARS);

include_once("../functions/stockquote.php");

$price = getStockInfo("price", $symbol);
$value = $price*$quantity+25;

$servername = "";
$username = "";
$password = "\$";
$dbname = "";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
	$sql = "SELECT * FROM stock_account_".$_SESSION['login'];
    
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {          
			switch($row['name']){
				case 'cash':
				$cash=$row['value'];
				break;
				case 'loan':
				$loan=$row['value'];
			}
	   }
    }else{
        echo "Error: No cash or loan";
    }

	if($cash+$loan>=$value){
		if($cash<$value){
			$sql = "UPDATE stock_account_".$_SESSION['login']." SET value=0 WHERE name='cash'";

			if ($conn->query($sql) === TRUE) {
			} else {
				echo "Error updating record: " . $conn->error;
			}
			
			$a = $loan - ($value-$cash);
			
			$sql = "UPDATE stock_account_".$_SESSION['login']." SET value=$a WHERE name='loan'";

			if ($conn->query($sql) === TRUE) {
			} else {
				echo "Error updating record: " . $conn->error;
			}

		}else{
			$a = $cash - $value;
			
			$sql = "UPDATE stock_account_".$_SESSION['login']." SET value=$a WHERE name='cash'";

			if ($conn->query($sql) === TRUE) {
			} else {
				echo "Error updating record: " . $conn->error;
			}
		}
		
	}else{
		die("Not enough funds! <a href=\"javascript:load_portfolio()\">Return to safety!</a>");
	}

$now = date("Y-m-d H:i:s");
 
	
	$sql = "INSERT INTO stock_history_".$_SESSION['login']." (date, symbol, exchange, action, quantity, price)
	 VALUES ('$now', '$symbol', '$exchange', 'buy', $quantity, $price)";

	if ($conn->query($sql) === TRUE) {
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
	
	$sql = "INSERT INTO stock_portfolio_".$_SESSION['login']." (symbol, exchange, buy, quantity)
	 VALUES ('$symbol', '$exchange', $price, $quantity)";

	if ($conn->query($sql) === TRUE) {
		echo "<script id=\"load_account\">load_account()</script>Sucessful transaction. <a href=\"javascript:load_portfolio()\">Return to portfolio!</a>";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}

    $conn->close();

