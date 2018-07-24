<?php

if(!defined('MyConst')) {
	die('Direct access not permitted');
}

/* Type eg. symbol, companyName, primaryExchange, sector, open, close, latestPrice*/
function getStockInfo($fn, $symbol){
	$type = $fn;
	switch($type){
		case "exchange":
		case "longexchange":
			$type = "primaryExchange";
			break;
		case "name":
			$type = "companyName";
			break;
		case "price":
			$type = "latestPrice";
	}
    $info = json_decode(file_get_contents("https://api.iextrading.com/1.0/stock/$symbol/quote"), true);
	
	if($fn=="exchange"){
		switch($info[$type]){
			case "New York Stock Exchange":
				return "NYSE";
				break;
			case "Nasdaq Global Select":
				return "NASDAQ";
		}
	}
	
	return $info[$type];
}

