<?php
session_start();
//require('check_admin.php');
require('bin/connectdb.php');
if(!empty($_POST['btOrderSave'])){//กดปุ่มบันทึการจัดเรียง
 foreach($_POST['cg_order'] as $cg_id => $cg_order){
  //Update การจัดเรียงลำดับของหมวดกระทู้
    mysqli_query($connectdb ,"UPDATE tbl_category SET cg_order='$cg_order' WHERE cg_id='$cg_id'");
 } 
}
if(!empty($_GET['del'])){//กดลบหมวดกระทู้
 mysqli_query($connectdb ,'DELETE FROM tbl_category  WHERE cg_id='.$_GET['del']);
 header('category.php');
}
//แสดงหมวดหมุ่กระทู้ทั้งหมดโดยเรียงตามลำดับ cg_order จากน้อยไปมาก
$rs_category=mysqli_query($connectdb ,"SELECT cg_id,cg_name,mem_id FROM tbl_category ORDER BY cg_order ASC"); 
$chk_rows_category=mysqli_num_rows($rs_category);//นับจำนวนแถวของหมวดกระทู้
?>
<html>
    <head>
        <?php require('head.php'); ?>
        <title>จัดการหมวดสายงาน</title>
    </head>
    <body>
        <?php require('menu.php'); ?>
               <form id="categoryForm" name="categoryForm" method="post" action="" >
                 <table class="table table-bordered table-hover" align="center" bgcolor="#efe0b8" margin-top="2%" border="0">
                   <thead>
                     <tr>
                       <th colspan="4">
                       <div style="float:right">
                       <span class="btn btn-default" ><a href="category_add.php">เพิ่มหมวดสายงาน</a></span>
                       <?php if ($_SESSION['mem_level'] == 1 ) {?>
                       <input type="submit" name="btOrderSave" id="btOrderSave" class="btn btn-primary" value="บันทึก" >
                       <?php }
                                        
                                        ?>
                       </div>
                       </th>
                     </tr>
                     <tr><br></tr>
                     <tr>
                       <th style="text-align:center">ลำดับ</th>
                       <th>ชื่อหมวดสายงาน</th>
                       <?php if ($_SESSION['mem_level'] == 1) {?>
                       <th>เรียงลำดับ</th><?php }
                                        
                                        ?>
                       <th>จัดการ</th>
                     </tr>
                   </thead>
                   <tbody>
                   <?php if($chk_rows_category>0) {//จำนวนแถวมากกว่า 0 แสดงว่ามีข้อมูล
        $order_i=1;
        while($show_category=mysqli_fetch_assoc($rs_category)){ //นำ array ไปแสดงข้อมูล
         $cg_id=$show_category['cg_id'];
         $mem_id = $show_category['mem_id'];
        ?>  
                     <tr>
                       <td style="width:10%;text-align:center"><?php echo  $order_i;?></td>
                       <td style="width:70%"><?php echo $show_category['cg_name'];?></td>
                       <?php if ($_SESSION['mem_level'] == 1 ) {?>
                       <td style="width:10%"><input type="text" name="cg_order[<?php echo  $cg_id;?>]"  class="form-control cg_order" value="<?php echo  $order_i;?>" ></td>
                       <?php }
                                        
                                        ?>
                       <td style="width:10%">
                       
                                        <?php
                                        if (isset($_SESSION['mem_id'])) {
                                            if ($_SESSION['mem_level'] == 1 || $mem_id == $_SESSION['mem_id']) {
                                                ?>
                                                ( <a href="category_edit.php?edit=<?php echo  $cg_id;?>">แก้ไข</a> /
                                                <?php if ($_SESSION['mem_level'] == 1|| $mem_id == $_SESSION['mem_id']) {//ลบได้เฉพาะ admin เท่านั้นหรือ เจ้าของโพส?>
                                                    /
                       <a href="category.php?del=<?php echo  $cg_id;?>" onClick="return confirm('ยืนยันการลบหมวดกระทู้นี้')">ลบ</a>
                                                    
                                                <?php } ?>)</td>
                                                <?php }
                                        }
                                        ?>
                     </tr>
                     <?php 
      $order_i++;
        }
      }else{ //ไม่มีข้อมูลหมวดกระทู้ แถววเป็น 0
      ?>
                     <tr>
                       <td colspan="4" align="center">ไม่พบข้อมูล</td>
                     </tr>
                     <?php } ?>
                   </tbody>
                 </table>
               </form>
                 
    </body>
</html>