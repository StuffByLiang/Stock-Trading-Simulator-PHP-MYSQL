<?php
if(!defined('MyConst')) {
die('Direct access not permitted');
}
?>
<h5>Buy stocks</h5>
<form id="buy_form" name="buy">
    <label for="ticker">Ticker Symbol:</label>
    <input type="text" id="symbol" name="symbol" value="" size="20"><br/ >
    <label for="quantity">Quantity:</label>
    <input type="text" id="quantity" name="quantity" value="0" size="20"><br/ >
	or<br>
	<label for="worth">Amount in $:</label><input type="text" id="worth" name="worth" value="0" size="20"><br/ >
</form>
<button name="Buy" onclick="stocks_buy()">Buy</button>