<?php
if(isset($_GET['u'])){
$user = filter_var($_GET['u'],FILTER_SANITIZE_SPECIAL_CHARS);
$servername = "";
$username = "";
$password = "\$";
$dbname = "";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
$sql = "SELECT username FROM stock_userlist WHERE username='$user'"; //check if user exists
    
$result = $conn->query($sql);
if ($result->num_rows > 0) {
}else{
    die("<p>There is no one with the username of '".$user."'</p>");
}
?>
<h3>Transaction History of <small><?php echo $user?></small></h3>
<div style="overflow: auto;">
<table class="rwd-table">
<thead>
	<tr>
		<th style="padding-left: 10px;">Action</th>
		<th>Date</th>
		<th>Symbol</th>
		<th>Market</th>
		<th>Quantity</th>
		<th>Price</th>
		<th style="text-align: right; padding-right: 10px;">Total Value</th>
	</tr>
</thead>
<tbody>
<?php
$sql = "SELECT * FROM stock_history_".$user."
	ORDER BY id DESC"; //get history
    
    $result = $conn->query($sql);
	
    if ($result->num_rows > 0) {
      	  	while($row = $result->fetch_assoc()) {
?>
			<tr>
				<td style="padding-left: 10px;"><?php echo $row['action']; ?></td>
				<td><?php echo date_format(new DateTime($row['date']),'M d'); ?></td>
				<td><?php echo $row['symbol']; ?></td>
				<td><?php echo $row['exchange']; ?></td>
				<td><?php echo $row['quantity']; ?></td>
				<td><?php if((float)$row['price']!=number_format($row['price'],2)){echo "$".(float)$row['price'];}else{echo "$".number_format($row['price'],2);} ?></td>
				<td style="text-align: right; padding-right: 10px;"><?php echo "$".number_format($row['quantity']*$row['price'],2); ?></td>
			</tr>
<?php
		}
	}else{
        echo "You have nothing here yet!";
    }
}else{
include_once("../functions/stockquote.php");
?>
<h3>Rankings <small>Be proud! .. or not.</small></h3>
<h5>P.S. (Click on a name to see transaction history)</h5>
<?php if(isset($_SESSION['login'])){ ?>
<a href="/portfolio/">Return back to portfolio</a>
<?php } ?>
<div style="overflow: auto;">
<table class="rwd-table">
<thead>
	<tr>
		<th style="padding-left: 10px;">Rank</th>
		<th>Name</th>
		<th style="text-align: right; padding-right: 10px;">Net Worth</th>
	</tr>
</thead>
<tbody>
<?php
$servername = "";
$username = "";
$password = "\$";
$dbname = "";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
$totalpeople =-1;
	
$sql = "SELECT id,username FROM stock_userlist"; //get all rows from portfolio
    
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {     
		$totalpeople++;
		$people[$totalpeople]=$row['username'];
	
	}
}
for($i=0;$i<=$totalpeople;$i++){
$sql = "SELECT * FROM stock_portfolio_".$people[$i]; //get portfolio
    
    $result = $conn->query($sql);
	
	$networth = 0;
	
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
			$networth += getStockInfo("price", $row['symbol'])*$row['quantity'];
		}
	}
	
	$sql = "SELECT * FROM stock_account_".$people[$i].""; //get cash and loan value
    
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {          
            date_default_timezone_set("America/Los_Angeles");
           switch($row['name']){
			case "cash":
			$cash=$row['value'];
			break;
case "loan":
			$loan=$row['value'];
		}
	    }
    }else{
        echo "Nothing lol";
    }
	
			$networth -= (50000 - $loan);
			$networth += $cash;
	
	$peopleportfolio[$people[$i]] = $networth;
	
}
arsort($peopleportfolio);
$rank = 0;
foreach($peopleportfolio as $name => $networth) {
	$rank++;
	?>
	
	<tr>
		<td style="padding-left: 10px;" data-th="Rank"><?php echo $rank; ?></td>
		<td data-th="Name"><?php echo "<a href=\"/rankings/?u=".$name."\">".$name."</a>"; ?></td>
		<td style="text-align: right; padding-right: 10px;" data-th="Net Worth"><?php echo "$".number_format($networth,2); ?></td>
	</tr>
	<?php
}
}
?>
</tbody>
</table>
</div>