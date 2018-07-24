<?php
define('MyConst', TRUE);
include_once("../functions/stockquote.php");

echo getStockInfo("symbol","GOOG");
echo getStockInfo("name","GOOG");
echo getStockInfo("exchange","GOOG");
echo getStockInfo("price","GOOG");

?>