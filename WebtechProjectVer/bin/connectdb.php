<?php
$hostname = "************";//ชื่อHost
$database = "************";//ชื่อฐานข้อมูล
$username = "************";//username ของฐานข้อมูลของท่าน
$password = "************";//รหัสผ่านของฐานข้อมูลของท่าน
$connectdb = mysqli_connect("************", "************", "************", "************");  
mysqli_query($connectdb ,"SET character_set_results=utf8");
mysqli_query($connectdb ,"SET collation_connection=utf8");
mysqli_query($connectdb ,"SET NAMES 'utf8'");
?>