<?php
session_start();
if (empty($_SESSION['mem_id'])) {//ไม่พบค่าเซสชั่น mem_id แสดงว่าไม่ใช่สมาชิก จึงไม่สามารถตั้งกระทู้ได้
    header('Location:http://webtech2562.96.lt/s1g12/');
}
require('bin/connectdb.php'); //เรียกไฟล์เชื่อมต่อกับฐานข้อมูล
if (!empty($_POST['btSave'])) {//มีการคลิกที่ปุ่มบันทึกตั้งกระทู้
    $msgError = '';
    if (!empty($_POST['board_topic']) || !empty($_POST['board_detail'])) {
        $cg_id = $_GET['id']; //รหัสหมวดกระทู้
        $board_topic = trim($_POST['board_topic']); //หัวข้อกระทู้
        $board_detail = nl2br($_POST['board_detail']); //รายละเอียดกระทู้
        $board_company = $_POST['board_company'];
        $board_salary = $_POST['board_salary'];
        $board_subcompany = $_POST['board_subcompany'];
        $board_spec = $_POST['board_spec'];
        $board_tel = $_POST['board_tel'];
        mysqli_query($connectdb ,"INSERT INTO tbl_board(cg_id,board_topic,board_detail,board_time_add,board_time_update,mem_id,
board_company,board_salary,board_subcompany,board_spec,board_tel) 
  VALUES($cg_id,'$board_topic','$board_detail',SYSDATE(),SYSDATE(),". $_SESSION['mem_id'] .",
   '$board_company','$board_salary','$board_subcompany','$board_spec','$board_tel'    
  )") or die(mysqli_error());
        mysqli_query($connectdb ,"UPDATE tbl_category SET cg_topic_totals=cg_topic_totals+1 WHERE cg_id=$cg_id");
        header("Location:showboard.php?id=" . $_GET['id'] . '&notview=1');
    } else {
        $msgError.='กรุณากรอกอาชีพและรายละเอียดของรีวิวด้วย<br />';
    }
    if (empty($msgError)) {
        //หากสมาชิกพิมพ์ข้อมูลถูกต้อง ให้Redirect หน้าไปที่ไฟล์ category.php
        header("Location:showboard.php?id=" . $_GET['id']);
    } else {
        //หากกรอกข้อมูลไม่ถูกต้อง ให้สร้างตัวแปร session มารับค่าเพื่อแจ้งให้ทราบถึงปัญหาที่เกิดขึ้น
        $_SESSION['message_error'] = $msgError;
    }
}
$show_board = '';
if (!empty($_GET['id'])) {
    $rs_cg = mysqli_query($connectdb ,'SELECT cg_name,cg_id FROM tbl_category WHERE cg_id=' . $_GET['id']);
    $show_board = mysqli_fetch_assoc($rs_cg); //นับจำนวนแถวของหมวดกระทู้
    if (empty($show_board['cg_name'])) {
        header('Location:http://webtech2562.96.lt/s1g12/');
    }
} else {//ไม่พบพารามิเตอร์ $_GET['id'] .ให้กลับไปหน้าแรก
    header('Location:http://webtech2562.96.lt/s1g12/');
}
?>
<html>
    <head>
        <?php require('head.php'); ?>
        
        <title>สร้างข้อมูล <?php echo $show_board['cg_name']; ?></title>
        <style>
            table{
                
                width: 400px;
                margin-top:2%
            }
        </style>
    </head>
    <body>
        <?php require('menu.php'); ?>
                <ol class="breadcrumb">
                    <li><a href="http://webtech2562.96.lt/s1g12/">Home</a></li>
                    <li><a href="showboard.php?id=<?php echo $show_board['cg_id']; ?>"><?php echo $show_board['cg_name']; ?></a></li>
                    <li class="active" >สร้างข้อมูล</li>
                </ol>
                <table align="center" bgcolor="#efe0b8" margin-top="2%" border="0">
                    <tr><td COLSPAN=2>
                    <h1 align="center">สร้างข้อมูล</h1>
                    </td>
                    </tr>
                    <tr>
                    <td style="width:60px"></td>
                        <td>
                    <?php
                    if (!empty($_SESSION['message_error'])) {
                        //แสดงปัญที่เกิดขึ้นจากการไม่กรอกชื่อหมวดกระทู้
                        ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $_SESSION['message_error']; ?>
                        </div>
                        <?php
                        $_SESSION['message_error'] = '';
                    }
                    ?>
                    <form  method="post" enctype="multipart/form-data" id="boardForm" name="boardForm" action="">
                        <div class="form-group">
                            <label for="Category Name">อาชีพ</label>
                            <input type="text" class="form-control" id="board_topic" name="board_topic" placeholder="ชื่ออาชีพ" >
                        </div>
                        <div class="form-group">
                            <label for="Category Name">บริษัท</label>
                            <input type="text" class="form-control" id="board_company" name="board_company" placeholder="ชื่อบริษัท">
                        </div>
                        <div class="form-group">
                            <label for="Category Name">สาขา/ที่ตั้ง</label>
                            <input type="text" class="form-control" id="board_subcompany" name="board_subcompany" placeholder="สาขา">
                        </div>
                        
                        
                        <div class="form-group">
                            <label for="Category Name">เงินเดือน</label>
                            <input type="int" class="form-control" id="board_salary" name="board_salary" placeholder="เงินเดือน"><p style="font-size:80%">(กรอกเฉพาะตัวเลขและต้องไม่เกิน8หลัก ไม่ระบุให้กรอก0)</p>
                        </div>
                        <div class="form-group">
                            <label for="Category Name">คุณสมบัติ</label>
                            <input type="text" class="form-control" id="board_spec" name="board_spec" placeholder="คุณสมบัติ">
                        </div>
                        <div class="form-group">
                            <label for="Category Description">รีวิว</label>
                            <textarea class="form-control" id="board_detail"  name="board_detail" placeholder="รีวิว" rows="10"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="Category Name">ติดต่อ</label>
                            <input type="text" class="form-control" id="board_tel" name="board_tel" placeholder="ติดต่อ">
                        </div>
                        <div class="form-group">
                            สร้างข้อมูลโดย : <b><?php echo $_SESSION['mem_name']; ?></b>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" name="btSave" value="บันทึกตั้งกระทู้" >
                        </div>
                    </form>
                </td></tr>
                </table>
    </body>
</html>