<?php
session_start();

date_default_timezone_set("America/New_York");

if(idate("w")==0||idate("w")==6){//check if weekend
	die("Can't sell! It's the weekend. <a href=\"javascript:load_portfolio()\">Return to safety!</a>");
}
//https://www.reddit.com/r/tifu/comments/2livoo/tifu_my_whole_life_my_regrets_as_a_46_year_old/
if(idate("H")*60+idate("i")<540){ //check if time is from 00:00 - 09:30
	die("Can't sell! Market still closed. Market opens in ". (540 -(idate("H")*60+idate("i")))."minutes . <a href=\"javascript:load_portfolio()\">Return to safety!</a>");
}

if(idate("H")*60+idate("i")>960){ //check if time is from 16:00 - 24:00
	die("Can't sell! Market has closed. Market closed ". ((idate("H")*60+idate("i")) - 960) ." minutes ago. <a href=\"javascript:load_portfolio()\">Return to safety!</a>");
} 

define('MyConst', TRUE);
$symbol=filter_var(strtoupper($_GET['s']),FILTER_SANITIZE_SPECIAL_CHARS);
$exchange=filter_var($_GET['e'],FILTER_SANITIZE_SPECIAL_CHARS);
$quantity=filter_var($_GET['q'],FILTER_SANITIZE_SPECIAL_CHARS);

include_once("../functions/stockquote.php");

$price = getStockInfo("price", $symbol);
$value = $price*$quantity-25;

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
        echo "Error: Nothing";
    }
    
	$sql = "SELECT * FROM stock_portfolio_".$_SESSION['login']."
	WHERE symbol = '$symbol'
	AND exchange = '$exchange';";
	
	$checkquantity=0;
    
    $result = $conn->query($sql);
	
	$rownumber = -1;
	$totalamount = $quantity;

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {   
		
			$checkquantity += $row['quantity'];
			
			$rownumber++;
			$stockid[$rownumber]=$row['id'];// keep track of id for future
			if($row['quantity']>$totalamount){
				$finalrow = $rownumber;
				$leftover = $row['quantity']-$totalamount;
			}
			$totalamount -= $row['quantity'];
	   }
    }else{

        die("You don't have that stock??! WTF?!! contact Liang you hacker. <a href=\"javascript:load_portfolio()\">Return to safety!</a>");
    }
	
	if($checkquantity < $quantity){
		die("You can't sell that much stock! WTF?!! contact Liang you hacker. <a href=\"javascript:load_portfolio()\">Return to safety!</a>");
	}
	
	

for($i=0;$i<=$rownumber;$i++){
	if($leftover==0||$i<$rownumber){
		$sql = "DELETE FROM stock_portfolio_".$_SESSION['login']." WHERE id=$stockid[$i]";
		
		if ($conn->query($sql) === TRUE) {
		} else {
			echo "Error deleting record: " . $conn->error;
		}
	}else{
		$tempstockid = $stockid[$i];
		$sql = "UPDATE stock_portfolio_".$_SESSION['login']."
		SET quantity=$leftover
		WHERE id=$tempstockid";
		
		if ($conn->query($sql) === TRUE) {
		} else {
			echo "Error updating record: " . $conn->error;
		}
	}
}

	
	$temp = 50000 - $loan;
	
	if($value <= $temp){
		
		$finalloan = $loan + $value;

		$sql = "UPDATE stock_account_".$_SESSION['login']." SET value=$finalloan WHERE name='loan'";

		if ($conn->query($sql) === TRUE) {
		} else {
			echo "Error updating record: " . $conn->error;
		}
	}else{
		
		$finalcash = $cash + $value - $temp;
	
		
		$sql = "UPDATE stock_account_".$_SESSION['login']." SET value=50000 WHERE name='loan'";

		if ($conn->query($sql) === TRUE) {
		} else {
			echo "Error updating record: " . $conn->error;
		}
		
		$sql = "UPDATE stock_account_".$_SESSION['login']." SET value=$finalcash WHERE name='cash'";

		if ($conn->query($sql) === TRUE) {
		} else {
			echo "Error updating record: " . $conn->error;
		}
		
	}


$now = date("Y-m-d H:i:s");
	
	$sql = "INSERT INTO stock_history_".$_SESSION['login']." (date, symbol, exchange, action, quantity, price)
	 VALUES ('$now', '$symbol', '$exchange', 'sell', $quantity, $price)";

	if ($conn->query($sql) === TRUE) {
		echo "<script id=\"load_account\">load_account()</script>Sucessful transaction. <a href=\"javascript:load_portfolio()\">Return to portfolio!</a>";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}

    $conn->close();

