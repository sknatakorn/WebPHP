<?php
session_start();
//require('check_admin.php');
if (empty($_SESSION['mem_id'])) {//ไม่พบค่าเซสชั่น mem_id แสดงว่าไม่ใช่สมาชิก จึงไม่สามารถตั้งกระทู้ได้
    header('Location:http://webtech2562.96.lt/s1g12/');
}
if(!empty($_POST['btSave'])){//มีการคลิกที่ปุ่มบันทึก
    require('bin/connectdb.php');//เรียกไฟล์เชื่อมต่อกับฐานข้อมูล
    $msgError='';
    if(!empty($_POST['cg_name'])){
        $cg_name=$_POST['cg_name'];//ชื่อหมวด
        $cg_des=$_POST['cg_des'];//คำอธิบายหมวด
 if ($_SESSION['mem_level'] == 1) $cg_order=$_POST['cg_order']; else $cg_order = 1; //เรียงลำดับการแสดงผล
  mysqli_query($connectdb ,"INSERT INTO tbl_category(cg_name,cg_des,cg_order,mem_id) VALUES('$cg_name','$cg_des','$cg_order',". $_SESSION['mem_id'] .")");              
    }else{
        $msgError.="กรุณากรอกชื่อหมวดกระทู้ด้วย <br>";
    }
    if(empty($msgError)){
  //หากสมาชิกพิมพ์ข้อมูลถูกต้อง ให้Redirect หน้าไปที่ไฟล์ category.php
        header("Location:category.php");
    }else{
  //หากกรอกข้อมูลไม่ถูกต้อง ให้สร้างตัวแปร session มารับค่าเพื่อแจ้งให้ทราบถึงปัญหาที่เกิดขึ้น
        $_SESSION['message_error']=$msgError;
    }
}
?>
<html>
<head>
        <?php require('head.php'); ?>
        <title>เพิ่มหมวดสายงาน</title>
    </head>
    <body>
        <?php require('menu.php'); ?>
        
                    <h1>เพิ่มหมวดสายงาน</h1>
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
                            <input type="text" class="form-control" id="cg_name" name="cg_name" placeholder="ชื่อหมวดกระทู้">
                        </div>
                        <div class="form-group">
                            <label for="Category Description">คำอธิบาย</label>
                            <textarea class="form-control" id="cg_des"  name="cg_des" placeholder="คำอธิบายหมวดกระทู้" rows="5"></textarea>
                        </div>
                        <?php if ($_SESSION['mem_level'] == 1) {?>
                        <div class="form-group">
                            <label for="Category Order">เรียงลำดับ</label>
                            <input type="text" class="form-control" id="cg_order" name="cg_order" style="width:20%;" value="1">
                        </div>
                        <?php }
                                        
                                        ?>
                        
                        <div class="form-group">
                        <input type="submit" class="btn btn-primary" name="btSave" value="บันทึก" >
                        </div>
                    </form>
                
            
    </body>
</html>