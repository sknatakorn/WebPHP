<?php
session_start();
//require('check_admin.php');
 require('bin/connectdb.php');   //เรียกไฟล์เชื่อมต่อกับฐานข้อมูล
 if (empty($_SESSION['mem_id'])) {//ไม่พบค่าเซสชั่น mem_id แสดงว่าไม่ใช่สมาชิก จึงไม่สามารถตั้งกระทู้ได้
    header('Location:http://webtech2562.96.lt/s1g12/');
}
if(!empty($_POST['btSaveEdit'])){//มีการคลิกที่ปุ่มแก้ไข
    $msgError='';
    if(!empty($_POST['cg_name'])){
  $cg_id=$_GET['edit'];
        $cg_name=$_POST['cg_name'];//ชื่อหมวด
        $cg_des=$_POST['cg_des'];//คำอธิบายหมวด
        if ($_SESSION['mem_level'] == 1) $cg_order=$_POST['cg_order']; else $cg_order = 1;//เรียงลำดับการแสดงผล
  mysqli_query($connectdb ,"UPDATE tbl_category SET cg_name='$cg_name',cg_des='$cg_des',cg_order='$cg_order' WHERE cg_id='$cg_id'");              
    }else{
        $msgError.='กรุณากรอกชื่อหมวดกระทู้ด้วย<br />';
    }
    if(empty($msgError)){
  //หากสมาชิกพิมพ์ข้อมูลถูกต้อง ให้Redirect หน้าไปที่ไฟล์ category.php
        header("Location:category.php");
    }else{
  //หากกรอกข้อมูลไม่ถูกต้อง ให้สร้างตัวแปร session มารับค่าเพื่อแจ้งให้ทราบถึงปัญหาที่เกิดขึ้น
        $_SESSION['message_error']=$msgError;
    }
}
$show_category_edit='';
if(!empty($_GET['edit'])){//พบพารามิเตอร์แบบ get ชื่อ edit แสดงว่าต้องการแก้ไขข้อมูล
 $cg_id=$_GET['edit'];
 $rs_category_edit=mysqli_query($connectdb ,"SELECT * FROM tbl_category WHERE cg_id='$cg_id'") or die(mysqli_error());
 $rows_category_edit=mysqli_num_rows($rs_category_edit);
 
 if($rows_category_edit>0){//มีข้อมูลของหมวดกระทู้ที่ต้องการแก้ไขในฐานข้อมูล
  $show_category_edit=mysqli_fetch_assoc($rs_category_edit);
 }else{//ไม่พบข้อมูลของหมวดกระทู้
 header('category.php'); 
 }
}else{//ไม่พบ ให้กลับไปหน้าเดิม
header('category.php'); 
}
if ($_SESSION['mem_level'] != 1 && $show_category_edit['mem_id'] != $_SESSION['mem_id']) {  //ไม่ใช่Admin และไม่ใช่เจ้าของกระทู้ แสดงว่าไม่มีสิทธิ์จัดการในส่วนนี้
    header('Location:http://webtech2562.96.lt/s1g12/'); //เด้งกลับไปหน้าหลัก
    exit();
}
?>
<html>
<head>
        <?php require('head.php'); ?>
        <title>เพิ่มหมวดวายงาน</title>
    </head>
    <body>
        <?php require('menu.php'); ?>
                    <h1>แก้ไขหมวดสายงาน</h1>
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
                    <form  method="post" enctype="multipart/form-data" id="categoryForm" name="categoryForm" action="">
                        <div class="form-group">
                            <label for="Category Name">ชื่อหมวดสายงาน</label>
                            <input type="text" class="form-control" id="cg_name" name="cg_name" placeholder="ชื่อหมวดกระทู้" value="<?php echo $show_category_edit['cg_name']?>">
                        </div>
                        <div class="form-group">
                            <label for="Category Description">คำอธิบาย</label>
                            <textarea class="form-control" id="cg_des"  name="cg_des" placeholder="คำอธิบายหมวดกระทู้" rows="5"><?php echo $show_category_edit['cg_des']?></textarea>
                        </div>
                        <?php if ($_SESSION['mem_level'] == 1) {?>
                        <div class="form-group">
                            <label for="Category Order">เรียงลำดับ</label>
                            <input type="text" class="form-control" id="cg_order" name="cg_order" style="width:20%;" value="<?php echo $show_category_edit['cg_order']?>">
                        </div>
                        <?php }
                                        
                                        ?>
                        
                        <div class="form-group">
                        <input type="submit" class="btn btn-primary" name="btSaveEdit" value="แก้ไข" >
                        </div>
                    </form>
            
    </body>
</html>
