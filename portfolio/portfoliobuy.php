<?php
session_start();
define('MyConst', TRUE);
$symbol=strtoupper($_GET['s']);
$quantity=$_GET['q'];
$worth=$_GET['w'];

if($worth >0 && $quantity >0){
	die('Please only enter the amount of stock you want to buy, or the money you want to spend. <a href="javascript:load_portfolio()">Return to safety!</a>');
}

include_once("../functions/stockquote.php");

$name = getStockInfo("name", $symbol);
$quote = getStockInfo("price", $symbol);
$exchange = getStockInfo("exchange", $symbol);
$exchangename = getStockInfo("longexchange", $symbol);

if($quantity==0){
	$quantity = floor((($worth-25)/$quote));
}
if($quantity <=0){
	die('Dummy you kinda can\'t buy anything with that amount of money. <a href="javascript:load_portfolio()">Return to safety!</a>');
}
switch($exchange){
	case "TSE":
	$exchangename="Toronto Stock Exchange(TSX)";
	break;
	case "NASDAQ":
	$exchangename="NASDAQ Stock Market(NASDAQ)";
	break;
	case "NYSE":
	$exchangename="New York Stock Exchange(NYSE)";
	break;
}
if($symbol==""){
	die('Enter a valid stock ticker. <a href="javascript:load_portfolio()">Return to safety!</a>');
}
if($quantity==0||floor($quantity) != $quantity){
	die('Enter a valid quantity. <a href="javascript:load_portfolio()">Return to safety!</a>');
}
if($exchange==""){
	die('Enter a valid exchange. <a href="javascript:load_portfolio()">Return to safety!</a>');
}
if($name=="N/A"||$name==""){
	die('Stock not found. <a href="javascript:load_portfolio()">Return to safety!</a>');
}
echo "<h1>Hi, ".$_SESSION['login'].". <small>You are currently buying a stock.</small></h1>";
?>
<h3>Buying <span style="color:#ffff00"><?php echo $quantity; ?></span> of <span style="color:#9933ff"><?php echo $name; ?></span> <span style="color:#ff5050">(<?php echo strtoupper($symbol); ?>)</span> at $<span style="color:#669900"><?php echo $quote; ?></span> each for a total of $<span style="color:#000"><?php echo $quote*$quantity; ?></span> from the <?php echo $exchangename;  ?><br></h3>
<br><button name="Confirm" onclick="stocks_buy_confirm()">Confirm</button><button name="cancel" onclick="load_portfolio()">Cancel</button>
<script type="text/javascript" id="stocks_buy_confirm">
stocks_buy_confirm = function() {
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
	xmlhttp.open("GET","upload_stockbuy.php?<?php echo "q=".$quantity."&s=".$symbol."&e=".$exchange; ?>",true);
    xmlhttp.send();
}
</script>