<?php
session_start();
session_destroy();//ล้างค่าในตัวแปร session ทิ้งทั้งหมด
header('Location:http://webtech2562.96.lt/s1g12/'); // สั่ง redirect (การใช้งานคือห้ามมี echo,printมาก่อน)
?>