<?php
session_start();
if (empty($_SESSION['mem_id'])) {//ไม่พบค่าเซสชั่น mem_id แสดงว่าไม่ใช่สมาชิก จึงไม่สามารถตั้งกระทู้ได้
    header('Location:http://webtech2562.96.lt/s1g12/');
}
require('bin/connectdb.php'); //เรียกไฟล์เชื่อมต่อกับฐานข้อมูล
if (!empty($_POST['btSaveEdit'])) {//มีการคลิกที่ปุ่มบันทึกแก้ไขกระทู้
    $msgError = '';
    if (!empty($_POST['board_topic']) || !empty($_POST['board_detail'])) {
        if (!empty($_GET['id'])) {
            $id = $_GET['id']; //รหัสกระทู้
            $board_topic = trim($_POST['board_topic']); //หัวข้อกระทู้
            $board_detail = nl2br($_POST['board_detail']); //รายละเอียดกระทู้
            $board_company = $_POST['board_company'];
        $board_salary = $_POST['board_salary'];
        $board_subcompany = $_POST['board_subcompany'];
        $board_spec = $_POST['board_spec'];
        $board_tel = $_POST['board_tel'];
            mysqli_query($connectdb ,"UPDATE tbl_board SET board_topic='$board_topic',board_detail='$board_detail',board_time_update=SYSDATE() 
 , board_company ='$board_company',board_salary='$board_salary',board_subcompany='$board_subcompany',board_spec='$board_spec',
  board_tel ='$board_tel' WHERE board_id=$id") or die(mysqli_error());
            header("Location:viewboard.php?id=$id");
            exit();
        }
    } else {
        $msgError.='กรุณากรอกอาชีพและรายละเอียดของรีวิวด้วย<br />';
    }
    if (empty($msgError)) {
        //หากสมาชิกพิมพ์ข้อมูลถูกต้อง ให้Redirect หน้าไปที่ไฟล์ category.php
        header("Location:showboard.php?id=" . $_GET['cg_id']);
        exit();
    } else {
        //หากกรอกข้อมูลไม่ถูกต้อง ให้สร้างตัวแปร session มารับค่าเพื่อแจ้งให้ทราบถึงปัญหาที่เกิดขึ้น
        $_SESSION['message_error'] = $msgError;
    }
}
$show_board = '';
if (!empty($_GET['cg_id']) && !empty($_GET['id'])) {
    $rs_cg = mysqli_query($connectdb ,'SELECT cg_name,cg_id FROM tbl_category WHERE cg_id=' . $_GET['cg_id']);
    $show_cg = mysqli_fetch_assoc($rs_cg); //นับจำนวนแถวของหมวดกระทู้
    if (empty($show_cg['cg_name'])) {
        header('Location:http://webtech2562.96.lt/s1g12/');
        exit();
    }
    $rs_board = mysqli_query($connectdb ,'SELECT b.board_id,b.mem_id,b.board_topic,b.board_detail,b.board_company,b.board_subcompany,b.board_salary,b.board_spec,b.board_detail,b.board_tel
  FROM tbl_board As b   
  LEFT JOIN tbl_member As m ON b.mem_id=m.mem_id 
  WHERE b.board_id=' . $_GET['id']);
    $show_board = mysqli_fetch_assoc($rs_board);
    if ($_SESSION['mem_level'] != 1 && $show_board['mem_id'] != $_SESSION['mem_id']) {  //ไม่ใช่Admin และไม่ใช่เจ้าของกระทู้ แสดงว่าไม่มีสิทธิ์จัดการในส่วนนี้
        header('Location:http://webtech2562.96.lt/s1g12/'); //เด้งกลับไปหน้าหลัก
        exit();
    }
} else {//ไม่พบพารามิเตอร์ $_GET['id'] .ให้กลับไปหน้าแรก
    header('Location:http://webtech2562.96.lt/s1g12/');
    exit();
}
?>
<html>
    <head>
        <?php require('head.php'); ?>
        <title>แก้ไขข้อมูล <?php echo $show_cg['cg_name']; ?></title>
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
                    <li><a href="showboard.php?id=<?php echo $show_cg['cg_id']; ?>"><?php echo $show_cg['cg_name']; ?></a></li>
                    <li class="active">แก้ไขข้อมูล</li>
                </ol>
                <table align="center" bgcolor="#efe0b8" margin-top="2%" border="0">
                    <tr><td COLSPAN=2 >
                    <h1 align="center">แก้ไขข้อมูล</h1>
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
                            <input type="text" class="form-control" id="board_topic" name="board_topic" placeholder="ชื่ออาชีพ"value="<?php echo  $show_board['board_topic']?>">
                        </div>
                        <div class="form-group">
                            <label for="Category Name">บริษัท</label>
                            <input type="text" class="form-control" id="board_company" name="board_company" placeholder="ชื่อบริษัท"value="<?php echo  $show_board['board_company']?>">
                        </div>
                        <div class="form-group">
                            <label for="Category Name">สาขา/ที่ตั้ง</label>
                            <input type="text" class="form-control" id="board_subcompany" name="board_subcompany" placeholder="สาขา"value="<?php echo  $show_board['board_subcompany']?>">
                        
                        
                        <div class="form-group">
                            <label for="Category Name">เงินเดือน</label>
                            <input type="int" class="form-control" id="board_salary" name="board_salary" placeholder="เงินเดือน"value="<?php echo  $show_board['board_salary']?>"><p style="font-size:80%">(กรอกเฉพาะตัวเลขและต้องไม่เกิน8หลัก ไม่ระบุให้กรอก0)</p>
                        </div>
                        <div class="form-group">
                            <label for="Category Name">คุณสมบัติ</label>
                            <input type="text" class="form-control" id="board_spec" name="board_spec" placeholder="คุณสมบัติ"value="<?php echo  $show_board['board_spec']?>">
                        </div>
                        <div class="form-group">
                            <label for="Category Description">รีวิว</label>
                            <textarea class="form-control" id="board_detail"  name="board_detail" placeholder="รีวิว" rows="10"value="<?php echo  $show_board['board_detail']?>"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="Category Name">ติดต่อ</label>
                            <input type="text" class="form-control" id="board_tel" name="board_tel" placeholder="ติดต่อ"value="<?php echo  $show_board['detail']?>">
                        </div>
                        <div class="form-group">
                            แก้ไขข้อมูลโดย : <b><?php echo $_SESSION['mem_name']; ?></b>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" name="btSaveEdit" value="บันทึกกระทู้" >
                        </div>
                    </form>
                    </td></tr>
                </table>
           
    </body>
</html>