<?php
session_start();
if (empty($_SESSION['mem_id'])) {//ไม่พบค่าเซสชั่น mem_id แสดงว่าไม่ใช่สมาชิก จึงใช้งานไม่ได้
    header('Location:http://webtech2562.96.lt/s1g12/');
}
require('bin/connectdb.php'); //เรียกไฟล์เชื่อมต่อกับฐานข้อมูล




// php select option value from database



?>





<!DOCTYPE html>

<html>

    <head>


    <?php require('head.php'); ?>
        <title> Search </title>
        <style>
            table{
                margin-top:3%
            }
        </style>
        

    </head>

    <body>
    <?php require('menu.php'); ?>
    
        
<?php
	ini_set('display_errors', 1);
	error_reporting(~0);

    $strKeyword = null;
    $strKeyword2 = null;
//นำค่าที่ได้จากการกรอกใน ฟอร์มเงินเดือน มาใส่ในตัวแปร strKeyword
	if(isset($_POST["txtKeyword"]))
	{
		$strKeyword = $_POST["txtKeyword"];
    }
    if(isset($_POST["txtKeyword2"]))
	{
		$strKeyword2 = $_POST["txtKeyword2"];
	}
?>
<form name="frmSearch" method="post" action="<?php echo $_SERVER['SCRIPT_NAME'];?>">
  <table width="599" border="0" align="center">
    <tr>
        
      <th><i class="far fa-money-bill-alt"></i>&nbsp;&nbsp;เงินเดือนที่ต้องการ
      <input name="txtKeyword" type="text" id="txtKeyword" value="<?php echo $strKeyword;?>">
      -
      <input name="txtKeyword2" type="text" id="txtKeyword2" value="<?php echo $strKeyword2;?>">
      <input type="submit" value="Search"></th>
    </tr>
  </table>
</form>

<?php

   $serverName = "mysql.hostinger.com";
   $userName = "u742683457_g12";
   $userPassword = "s1g1s1g1";
   $dbName = "u742683457_g12";

   $conn = mysqli_connect($serverName,$userName,$userPassword,$dbName);
   if(empty($strKeyword)&&empty($strKeyword2)) // ไม่กรอกเลย ให้แสดงข้อมูลที่เลือกทั้งหมดหลังจากกดปุ่ม โดยเรียงตามตัวอักษร
   {
    $sql = "SELECT * FROM tbl_board  LEFT JOIN tbl_category ON tbl_board.cg_id =tbl_category.cg_id WHERE Board_salary LIKE '%".$strKeyword."%'ORDER BY Board_topic ASC  ";
   }
  else if(!empty($strKeyword)&&empty($strKeyword2)) { $sql = "SELECT * FROM tbl_board LEFT JOIN tbl_category ON tbl_board.cg_id =tbl_category.cg_id WHERE Board_salary LIKE '$strKeyword'ORDER BY Board_topic ASC ";} //กรอกช่องแรกช่องเดียวก็จะแสดงค่าที่ต้องการออกมาเท่านั้น
  else if (empty($strKeyword)&&!empty($strKeyword2)) { $sql = "SELECT * FROM tbl_board LEFT JOIN tbl_category ON tbl_board.cg_id =tbl_category.cg_id WHERE Board_salary LIKE '$strKeyword2'ORDER BY Board_topic ASC ";}//กรอกช่องสองช่องเดียวก็จะแสดงค่าที่ต้องการออกมาเท่านั้น
  else {$sql = "SELECT * FROM tbl_board LEFT JOIN tbl_category ON tbl_board.cg_id =tbl_category.cg_id WHERE Board_salary BETWEEN '$strKeyword' AND '$strKeyword2'ORDER BY Board_salary,Board_topic ASC ";} //เลือกค่าที่มาจากค่าระหว่างทั้งสองช่อง
  mysqli_set_charset($conn, "utf8");//ใช้ LEFT JOIN เพื่อได้ข้อมูลจาก table นึงด้วย
   $query = mysqli_query($conn,$sql)or die( mysqli_error($conn));;
   
?>
<table width="600" border="1" align="center">
  <tr>
    <th width="91"> <div align="center">อาชีพ </div></th>
    <th width="98"> <div align="center">บริษัท</div></th>
    <th width="198"> <div align="center">สาขาบริษัท </div></th>
    <th width="97"> <div align="center">เงินเดือน </div></th>
    <th width="97"> <div align="center">สายงาน </div></th>
 
  </tr>
<?php
while($result=mysqli_fetch_array($query,MYSQLI_ASSOC))//ได้ Array มามี key เป็นชื่อคอมลัมน์ (จาก ASSOC)
{ $board_id = $result['board_id'];
    $board_topic = $result['board_topic'];
?>
  <tr>
    <td><div align="center"> <a href="viewboard.php?id=<?php echo $board_id; ?>"><?php echo $board_topic; ?></a></div></td><!--สำหรับ link ไปหน้าของอาชีพนั้นได้มาจาก id เดียวกัน -->
    <td><?php echo $result["board_company"];?></td>
    <td><?php echo $result["board_subcompany"];?></td>
    <td><div align="center"><?php if($result["board_salary"]!=0) echo $result["board_salary"];else echo "ไม่ระบุ"; ?></div></td>
    <td><div align="center"><?php echo $result["cg_name"];?></div></td>
    
  </tr>
<?php
}
?>
</table>
<?php
mysqli_close($conn); //ปิด database
?>
    </body>

</html>
