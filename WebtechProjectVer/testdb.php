<?php
include("bin/connectdb.php");
// Check connection
if (!$connectdb) {
die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";
?>