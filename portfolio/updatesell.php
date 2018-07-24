<?php
define('MyConst', TRUE);
$symbol=strtoupper($_GET['s']);
$quantity=$_GET['q'];
$amount = $_GET['a'];
if ($quantity > $amount){
	die("<h3>You don't have enough stocks to do that!</h3>");
}
include_once("../functions/stockquote.php");

$name = getStockInfo("name", $symbol);
$quote = getStockInfo("price", $symbol);
$exchange = getStockInfo("exchange", $symbol);
$exchangename = getStockInfo("longexchange", $symbol);

?>
<h3>You are currently selling <span style="color:#398b3e"><?php echo $quantity; ?></span> stock(s) at $<span style="color:#669900"><?php echo $quote; ?></span>
 each for a total of $<span style="color:#000"><?php echo $quote*$quantity; ?></span> 
 from the <?php echo $exchangename;  ?><br></h3>