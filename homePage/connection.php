<?php
    $servername = "localhost";
    $username = "root";
    $password = ""; 
    $dbname = "cethirdyearmembers";
    
    // Create
    $conn = new mysqli($servername, $username, $password, $dbname, 3309);
    
    if ($conn->connect_error) {
        die("Connection failed: ".$conn->connect_error);
    }
    echo "";
    
?>