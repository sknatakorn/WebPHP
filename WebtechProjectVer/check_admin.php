<?php
if($_SESSION['mem_level']!=1){//หากไม่ใช่ admin
header('Location:http://webtech2562.96.lt/s1g12/'); //ให้รีไดเร็คไปหน้า index.php
exit();
}
?>