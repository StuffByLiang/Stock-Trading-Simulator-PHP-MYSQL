<?php

if(!defined('MyConst')) {
   die('Direct access not permitted');
}


if(isset($_GET['action'])){
    $action = $_GET['action'];
    if($action=='update'){

        if(!isset($_SESSION['login']) && isset($_POST['assignment']) ){
            header("Location: http://stuffbyliang.com/homework/login/");    
        }
        if(isset($_POST['assignment'])){
                
            $assignment = addslashes($_POST['assignment']);
            $subject = addslashes($_POST['subject']);
            $date = addslashes($_POST['date']);
            $description= addslashes($_POST['description']);

            $servername = "";
            $username = "";
            $password = "\$";
            $dbname = "";
            // Create connection
            $conn = mysqli_connect($servername, $username, $password, $dbname);
            // Check connection
            if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
            }
            date_default_timezone_set("America/Los_Angeles");
            $date = strtotime($date);
            $date = date('Y-m-d H:i:s', $date);

            $sql = "UPDATE ".$_SESSION['login']."_assignments (name, subject, description, date)
            VALUES ('$assignment','$subject','$description','$date')";

            if (mysqli_query($conn, $sql)) {
            } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
            mysqli_close($conn);

    }
        
        
}
    
}else{
    include_once 'portfolio.php'; 
}