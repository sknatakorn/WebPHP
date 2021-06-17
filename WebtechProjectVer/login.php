<?php
session_start();
if(!empty($_POST['btLogin'])){//มีการคลิกที่ปุ่ม เข้าสู่ระบบ
    require('bin/connectdb.php');//เรียกไฟล์เชื่อมต่อกับฐานข้อมูล
    $msgError='';
 //ค่า username ,password ไม่เป็นค่าว่าง
    if(!empty($_POST['mem_user'])&& !empty($_POST['mem_pass'])){
        $username=$_POST['mem_user'];
        $password=$_POST['mem_pass'];
  //ตรวจสอบ username,password ว่ามีตรงกับฐานข้อมูลหรือไม่
        $rs_chk_mb=mysqli_query($connectdb ,"SELECT mem_name,mem_id,mem_level FROM tbl_member WHERE mem_user='$username' AND mem_pass='$password'");
        $show_chk_mb=  mysqli_fetch_assoc($rs_chk_mb);
        if(empty($show_chk_mb['mem_name'])){//หากไม่พบข้อมูล username,password ในฐานข้อมูล ให้แสดงข้อความแจ้งเตือนดังนี้
            $msgError.='กรอกข้อมูล Username หรือ Password ไม่ถูกต้อง<br />';
        }else{//หากพบว่ากรอกข้อมูลถูกต้อง ให้สร้างตัวแปรแบบ session มารับค่าดังนี้
            $_SESSION['mem_id']=$show_chk_mb['mem_id'];//รับค่า id สมาชิก
            $_SESSION['mem_name']=$show_chk_mb['mem_name'];//รับค่าชื่อของสมาชิก
   $_SESSION['mem_level']=$show_chk_mb['mem_level'];//รับค่าระดับผู้ใช้งานของสมาชิก 1 = admin ,2=สมาชิก
        }        
    }else{//กรณีที่สมาชิกไม่กรอกข้อมูล แล้วดันทะลึ่งกดปุ่ม เข้าสู่ระบบ ให้แจ้งข้อความดังนี้
        $msgError.='กรุณากรอก Username และ Password ด้วย<br />';
    }
    if(empty($msgError)){
  //หากสมาชิกพิมพ์รหัสผ่านถูกต้อง ให้Redirect หน้าไปที่ไฟล์ index.php ซึ่งก็คือหน้าโฮมนั่นเอง
        header("Location:http://webtech2562.96.lt/s1g12/");
    }else{
  //หากกรอกรหัสผ่านไม่ถูกต้อง ให้สร้างตัวแปร session มารับค่าเพื่อแจ้งให้ทราบถึงปัญหาที่เกิดขึ้น
        $_SESSION['message_error']=$msgError;
    }
}
?>
<html>
    <head>
        <?php require('head.php'); ?>
        <title>Login</title>
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
        <link href='https://fonts.googleapis.com/css?family=Delius' rel='stylesheet'>
        <style>
            table{
                
                width: 350px;
                height: 200px;
                margin-top:15%
            }
        </style>
    </head>
    <body>
        <?php require('menu.php'); ?>
                <table align="center" bgcolor="#efe0b8" margin-top="2%" border="0" >
                    <td>
                    <h1 align="center"><i class="far fa-address-card"></i>&nbsp;Login</h1>
                    <?php
                    if (!empty($_SESSION['message_error'])) {
      //แสดงปัญที่เกิดขึ้นจากการกรอกรหัสผ่านเข้าสู่ระบบ
                        ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $_SESSION['message_error']; ?>
                        </div>
                        <?php
                        $_SESSION['message_error'] = '';
                    }
                    ?>
                    <form  method="post" enctype="multipart/form-data" id="registrationForm" name="registrationForm" action="">
                        <div class="form-group" align="center">
                        <label for="username">Username</label>
                            <input type="text" class="form-control" id="mem_user" name="mem_user" placeholder="Username">
                        </div>
                        <div class="form-group" align="center">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="mem_pass"  name="mem_pass" placeholder="Password">
                        </div>
                        <br>
                        <div class="form-group" align="center">
                        <input type="submit" class="btn btn-primary" name="btLogin" value="Login" >
                        </div>
                    </form>
                </td>
                </table>
        
    </body>
</html>