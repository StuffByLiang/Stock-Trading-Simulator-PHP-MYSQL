<?php
session_start();

if(isset($_SESSION['login'])){
	
define('MyConst', TRUE);
include_once("../functions/stockquote.php");
	
$servername = "";
$username = "";
$password = "\$";
$dbname = "";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    $sql = "SELECT * FROM stock_account_".$_SESSION['login']; //get cash and loan value
    
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
			break;
			case "interest":
			$interest=$row['value'];	
		}
	   }
    }else{
        echo "Nothing lol";
    }
	
	$sql = "SELECT * FROM stock_portfolio_".$_SESSION['login']; //get portfolio
    
    $result = $conn->query($sql);
	
	$portfolio = 0;
	
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
			$portfolio += getStockInfo("price", $row['symbol'])*$row['quantity'];
		}
	}
	
	$sql = "SELECT id FROM stock_history_".$_SESSION['login']."
    WHERE quantity > 0;"; // get fees
    
    $result = $conn->query($sql);
    $totalfee = ($result->num_rows) * 25;

	?>
<h5>Account Summary</h5>
                        <table border="0" cellspacing="0" cellpadding="0" class="summary">
                            <tr>
                                <td>Cash:</td>
                                <td align="right"><?php echo "$".number_format($cash,2); ?></td>
                            </tr>
                            <tr>
                                <td>Portfolio:</td>
                                <td align="right"><?php echo "$".number_format($portfolio,2); ?></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td align="right">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>Loan:</td>
                                <td align="right"><?php echo "$".number_format((50000-$loan),2); ?></a></td>
                            </tr>
			    <tr>
                                <td>Loan Limit:</td>
                                <td align="right"><?php echo "$".number_format($loan,2); ?></a></td>
                            </tr>
				<tr>
                                <td>Interest Rate:</td>
                                <td align="right">7.5%</a></td>
                            </tr>
			    <tr>
                                <td>Total Interest Paid:</td>
                                <td align="right"><?php echo "$".$interest; ?></a></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td align="right">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>Net Worth:</td>
                                <td align="right"><?php echo "$".number_format($portfolio + $cash - (50000-$loan), 2); ?></a></td>
                            </tr>
                            <tr>
                                <td>Transaction Fee Amount:</td>
                                <td align="right">$25</td>
                            </tr>
                            <tr>
                                <td>Transaction Fees Paid:</td>
                                <td align="right"><?php if($totalfee>0){echo "$".$totalfee;}else{echo "N/A";} ?></td>
                            </tr>
                        </table>
<?php


    $conn->close();
}
