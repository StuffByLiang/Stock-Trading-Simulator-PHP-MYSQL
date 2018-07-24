<?php
session_start();

echo "<h3>Welcome back, ".$_SESSION['login']."<small> Get Investing!</small></h3>";

if(isset($_SESSION['login'])){
?>

<h5>Your Portfolio</h5>

<a href="javascript:load_portfolio()">Reload Portfolio</a>

<div style="overflow: auto;">
<table class="rwd-table">
<!-- Header Row: ******** -->
<thead>
	<tr>
		<th style="padding-left: 10px;">Market</th>
		<th>Ticker Symbol</th>
		<th>Quantity</th>
		<th>Purchase Price</th>
		<th>Current Price</th>
		<th>Total Value</th>
		<th>Gain/Loss</th>
		<th style="padding-right: 10px;"></th>
	</tr>
</thead>
<tbody>

<?php
// Stock Module ********
define('MyConst', TRUE);

include_once("../functions/stockquote.php");

// Login Module ********	
$servername = "";
$username = "";
$password = "\$";
$dbname = "";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    $sql = "SELECT * FROM stock_portfolio_".$_SESSION['login']; //get all rows from portfolio
    
    $result = $conn->query($sql);
	
	$rownumber = 0; //to keep track of rows
	$totalvalue = 0; //to keep track of rows
	$totalgain = 0; //to keep track of rows
	
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {          
		$quote = getStockInfo("price", $row['symbol']);
		?>
		
		<!-- Table Row: <?php echo $rownumber; ?> ******** -->
	<tr class="<?php if($rownumber % 2 == 0){echo "even"; }else{echo "odd"; } ?>">
		<td style="padding-left: 10px;" data-th="Market"><?php echo $row['exchange']; ?></td>
		<td data-th="Ticker Symbol"><?php echo $row['symbol']; ?></td>
		<td data-th="Quantity"><?php echo $row['quantity']; ?></td>
		<td data-th="Purchase Price"><?php if((float)$row['buy']!=number_format($row['buy'],2)){echo "$".(float)$row['buy'];}else{echo "$".number_format($row['buy'],2);} ?></td>
		<td data-th="Current Price"><?php echo "$".$quote; ?></td>
		<td data-th="Total Value"><?php echo "$".number_format( $row['quantity']*$quote , 2 );?></td>
		<td data-th="Gain/Loss" class="<?php if($row['buy'] > $quote ){echo "gain"; }else if($row['buy'] < $quote ){echo "loss"; } ?>"><?php echo "$".number_format( ($row['quantity']*$quote)-($row['quantity'] * $row['buy']) , 2 ); ?></td>
		<td style="padding-right: 10px;"><a href="javascript:stocks_sell('<?php echo $row['symbol'] . "','". $row['exchange']; ?>')">Sell</a></td>
	</tr>
		
		<?php
		$rownumber++;
		$totalvalue += $row['quantity']*$quote;
		$totalgain += ($row['quantity']*$quote)-($row['quantity'] * $row['buy']);
	    }
    }else{
        echo "You have nothing here yet!";
    }
?>
	<!-- Table Row: <?php echo $rownumber; ?> ******** -->
	<tr class="<?php if($rownumber % 2 == 0){echo "even"; }else{echo "odd"; } ?>">
		<td style="padding-left: 10px;"></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td data-th="Total Value"><?php echo "$".number_format($totalvalue,2); ?></td>
		<td data-th="Gain/Loss"><?php echo "$".number_format($totalgain,2); ?></td>
		<td style="padding-right: 10px;"></td>
	</tr>
</tbody>
</table>
</div>
<?php
	}

?>

<h5>Your Transactions</h5>

<div style="overflow: auto;">
<table class="rwd-table">
<!-- Header Row: ******** -->
<thead>
	<tr>
		<th style="padding-left: 10px;">Date</th>
		<th>Action</th>
		<th>Market</th>
		<th>Ticker Symbol</th>
		<th>Quantity</th>
		<th>Price</th>
		<th>Total Value</th>
		<th style="padding-right: 10px;">Fee</th>
	</tr>
</thead>
<tbody>

<?php

    $sql = "SELECT * FROM stock_history_".$_SESSION['login']; //get all rows from portfolio
    
    $result = $conn->query($sql);
	
	$rownumber = 0; //to keep track of rows
	
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {          
		?>
		
		<!-- Table Row: <?php echo $rownumber; ?> ******** -->
	<tr class="<?php if($rownumber % 2 == 0){echo "even"; }else{echo "odd"; } ?>">
		<td style="padding-left: 10px;" data-th="Date"><?php echo $row['date']; ?></td>
		<td data-th="Action"><?php echo $row['action']; ?></td>
		<td data-th="Market"><?php echo $row['exchange']; ?></td>
		<td data-th="Ticker Symbol"><?php echo $row['symbol']; ?></td>
		<td data-th="Quantity"><?php echo $row['quantity']; ?></td>
		<td data-th="Price"><?php if((float)$row['price']!=number_format($row['price'],2)){echo "$".(float)$row['price'];}else{echo "$".number_format($row['price'],2);}?></td>
		<td data-th="Total Value"><?php echo "$".number_format($row['quantity']*$row['price'], 2 ); ?></td>
		<td style="padding-right: 10px;" data-th="Fee">$25</td>
	</tr>
		
		<?php
    }
	}else{
        echo "You have nothing here yet!";
    }
?>

</tbody>
</table>
</div>
<?php

    $conn->close();


	

?>

