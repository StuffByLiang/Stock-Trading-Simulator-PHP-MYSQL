<?php
$servername = "";
$username = "";
$password = "\$";
$dbname = "";
    
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
$totalpeople =-1;
    
$sql = "SELECT id, username FROM stock_userlist"; //get all rows from portfolio
    
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {     
        $totalpeople++;
        $people[$totalpeople]=$row['username'];
    
    }
}
for($i=0;$i<=$totalpeople;$i++){
    $sql = "SELECT * FROM stock_account_".$people[$i]; //get cash and loan value
        
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
                    break;
                }
            }
        }
       
        $interest += ((0.085 * (50000 - $loan))/365);
        $loan -= ((0.085 * (50000 - $loan))/365);
        
         $sql = "UPDATE stock_account_".$people[$i]."
SET value=$loan
WHERE name='loan';";
$result = $conn->query($sql);
        $sql = "UPDATE stock_account_".$people[$i]."
SET value=$interest
WHERE name='interest';";
$result = $conn->query($sql);
        
}