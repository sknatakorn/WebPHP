<?php
session_start();
if (!empty($_POST['btRegister'])) { //เมื่อกดสมัครสมาชิก
    require('bin/connectdb.php'); //ไฟล์เก็บคำสั่งเชื่อมต่อกับฐานข้อมูล 
    $msgError='';
    $username = '';
    $pass = '';
    $email = '';
    $name = '';
    $mem_image = '';
    $fileType = '';
    $filename = '';
    $mem_fname = '';
    $mem_lname ='';
    $mem_gen ='';
  //ตรวจสอบ Username ว่ามีค่าว่างหรือไม่
    if (!empty($_POST['mem_user'])) {
        $username = $_POST['mem_user'];
  //Patternตรวจสอบการกรอกข้อมูลรองรับ a-z,A-Z,ตัวเลข ตั้งแต่ 4-20 ตัวอักษร
 
        $chkInputUser = '/^[a-zA-Z0-9]{4,20}$/';        
        if (!preg_match($chkInputUser, $username, $regs)) {  //ตรวจสอบการกรอกข้อมูลของ Username ผ่าน
            $msgError .= 'Username ต้องมีขนาดตัวอักษร  4-20 ตัวอักษรภาษาอังกฤษและตัวเลขเท่านั้น<br />';
        }
        //ตรวจสอบ Username ว่าซ้ำหรือไม่
        $rs_username = mysqli_query($connectdb ,"SELECT COUNT(*) As cUsername FROM tbl_member WHERE mem_user='$username' ");
        $show_rs_username = mysqli_fetch_assoc($rs_username);
        if ($show_rs_username['cUsername'] > 0) {
            $msgError .= 'Username นี้มีผู้ใช้งานแล้ว<br />';
        }
    } else {//ถ้ามีค่าว่างให้แจ้งเออเร่อดังนี้
        $msgError .= 'กรุณากรอก Username ด้วย<br />';
    }
 //ตรวจสอบรหัสผ่านว่ามีค่าว่างหรือไม่
    if (!empty($_POST['mem_pass']) && !empty($_POST['repass'])) {
        $pass = $_POST['mem_pass'];
        $repass = $_POST['repass'];
        if ($pass != $repass) {//ตรวจสอบรหัสผ่านว่าตรงกันทั้งสองช่องหรือไม่
            $msgError .= 'รหัสผ่านทั้งสองช่องไม่ตรงกัน<br />';
        }
    } else {//ถ้ามีค่าว่างให้แจ้งเออเร่อดังนี้
       $msgError .= 'กรุณากรอกรหัสผ่านทั้งสองช่องด้วย<br />';
    }
    if (!empty($_POST['mem_name'])) {
        $name = $_POST['mem_name'];
        //ตรวจสอบชื่อเรียกในเว็บว่าซ้ำหรือไม่
        $rs_name = mysqli_query($connectdb ,"SELECT COUNT(*) As cName FROM tbl_member WHERE mem_name='$name' ");
        $show_rs_name = mysqli_fetch_assoc($rs_name);
        if ($show_rs_name['cName'] > 0) {
           $msgError .= 'ชื่อที่แสดงในเว็บนี้มีผู้ใช้งานแล้ว<br />';
        }
    } else {//ถ้ามีค่าว่างให้แจ้งเออเร่อดังนี้
        $msgError .= 'กรุณากรอกชื่อ ชื่อแสดงในเว็บ ด้วย<br />';
    }
  
     //ตรวจสอบ ชื่อเรียกในเว็บ ว่ามีค่าว่างหรือไม่
    if (!empty($_POST['mem_fname'])) {
        $mem_fname = $_POST['mem_fname'];
        }
     else {//ถ้ามีค่าว่างให้แจ้งเออเร่อดังนี้
        $msgError .= 'กรุณากรอกชื่อด้วย<br />';
    }
    if (!empty($_POST['mem_lname'])) {
        $mem_lname = $_POST['mem_lname'];
        }
     else {//ถ้ามีค่าว่างให้แจ้งเออเร่อดังนี้
        $msgError .= 'กรุณากรอกนามสกุลด้วย<br />';
    }
    if (!empty($_POST['mem_gen'])) {
        $mem_gen = $_POST['mem_gen'];
        }
     else {//ถ้ามีค่าว่างให้แจ้งเออเร่อดังนี้
        $msgError .= 'กรุณากรอกรุ่นด้วย<br />';
    }

 
    if (empty($msgError)) {//หากไม่มีข้อความเออเร่อ แสดงว่ากรอกข้อมูลถูกต้องหมดแล้ว
 //ให้บันทึกลงฐานข้อมูล
        mysqli_query($connectdb ,"INSERT INTO tbl_member(mem_user,mem_pass,mem_name
            ,mem_fname,mem_lname,mem_gen)  VALUE('$username','$pass','$name','$mem_fname','$mem_lname','$mem_gen')");
    
        
  //สร้างตัวแปร session มารับค่าเพื่อแจ้งใหสมาชิกทราบว่า ลงทะเบียนเสร็จแล้ว
        $_SESSION['message_success'] = 'ลงทะเบียนเสร็จสมบูรณ์แล้ว';
    } else {//หากมีข้อความเออเร่อ 
 //ให้สร้างตัวแปร sessiion มารับค่าเพื่อแจ้งให้สมาชิกถึงปัญหาที่เกิดขึ้น
         $_SESSION['message_error']= $msgError;
    }
}
?>
<html>
    <head>
        <?php require('head.php'); ?> <!--เรียก head -->
        
        <title>สมัครสมาชิก</title>
        <style>
            table{
                
                width: 400px;
                height: 200px;
                margin-top:10%
            }
        </style>
    </head>
    <body>
        <?php require('menu.php'); ?> <!--เรียกเมนู -->
        <table align="center" bgcolor="#efe0b8" margin-top="2%" border="0" >
        <tr><td COLSPAN=2>
                    <h1 align="center">สมัครสมาชิก</h1>
                    </td>
                    </tr>
                    <tr>
                    <td style="width:70px"></td>
                        <td>
                    <?php
     //พบตัวแปร session ชื่อ message_success  แสดงว่าลงทะเบียนเสร็จสมบูรณ์แล้ว                    
                    if (!empty($_SESSION['message_success'])) {
                        ?>
                        
                            <?php 
       //ให้แสดงข้อความแจ้งให้สมาชิกทราบดังนี้
       echo $_SESSION['message_success']; 
       ?><br />
                            <span>คลิก <a href="login.php">ที่นี้</a> เพื่อเข้าสู่ระบบ</span>
                        
                        <?php
                        $_SESSION['message_success'] = ''; //เปลี่ยนกลับเป็นว่าง
                    }
                    ?>
                    <?php
     //พบตัวแปร session ชื่อ message_error  แสดงว่ามีปัญหาเกิดขึ้น จากการกรอกข้อมูลของสมาชิก
                    if (!empty($_SESSION['message_error'])) {
                        ?>
                        
                            <?php
       //ให้แสดงข้อความแจ้งให้สมาชิกทราบดังนี้ 
       echo $_SESSION['message_error']; 
       ?>
                        
                        <?php
                        $_SESSION['message_error'] = '';
                    }
                    ?>
                    <form  method="post" enctype="multipart/form-data" id="registrationForm" name="registrationForm" action="">
                        
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="mem_user" name="mem_user" placeholder="Username"> <br>
                        
                            <label for="password">รหัสผ่าน</label>
                            <input type="password" class="form-control" id="mem_pass"  name="mem_pass" placeholder="รหัสผ่าน"><br>
                        
                            <label for="repassword">ยืนยันรหัสผ่าน</label>
                            <input type="password" class="form-control" id="repass" name="repass" placeholder="ยืนยันรหัสผ่าน"><br>
                        
                            <label for="name">ชื่อแสดงในเว็บ</label>
                            <input type="text" class="form-control" id="mem_name"  name="mem_name" placeholder="ชื่อแสดงในเว็บ"><br>
                            <label for="fname">ชื่อผู้สมัคร</label>
                            <input type="text" class="form-control" id="mem_fname"  name="mem_fname" placeholder="ชื่อผู้สมัคร"><br>
                            <label for="lname">นามสกุล</label>
                            <input type="text" class="form-control" id="mem_lname"  name="mem_lname" placeholder="นามสกุล"><br>
                            <label for="gen">รุ่น</label>
                            <input type="text" class="form-control" id="mem_gen"  name="mem_gen" placeholder="รุ่น"><br>
                        
                       
                        
                        <input type="submit" class="btn btn-primary" name="btRegister" value="ลงทะเบียน" >
                        <input type="reset" class="btn btn-primary" name="reset" value="Reset">
                      
                    </form>
                    </td>
                </table>
        
    </body>
</html>