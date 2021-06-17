<?php
session_start();
require('bin/connectdb.php'); //เรียกไฟล์เชื่อมต่อกับฐานข้อมูล
$show_topic_view = '';
$rs_board = '';
if (isset($_GET['delTopicID'])) {//ต้องการลบกระทู้
    require('check_admin.php'); //ตรวจสอบว่าเป็นadminกดลบหรือป่าว ถ้าไม่ใช่ เราจะไม่ให้ลบกระทู้ได้
    $id = $_GET['delTopicID'];
    mysqli_query($connectdb,'DELETE FROM tbl_board WHERE board_id=' . $id); //ลบกระทู้หลัก
    
    header('Location:viewboard.php?id=' . $id);
    exit();
}
if (isset($_GET['delAnsID']) && isset($_GET['topic_id'])) {//ต้องการลบกระทู้ความคิดเห็น
    $id = $_GET['delAnsID'];
    $topic_id = $_GET['topic_id'];
    mysqli_query($connectdb,'DELETE FROM tbl_board WHERE board_id=' . $id); //ลบความคิดเห็น
    header('Location:viewboard.php?id=' . $topic_id);
    exit();
}
 

if (isset($_GET['id'])) {//พบว่ามีส่งเมธอดชื่อ id เข้ามา 
    $rs_topic_view = mysqli_query($connectdb,'SELECT b.board_id,b.board_topic,b.board_detail,b.board_time_add,c.cg_id,c.cg_name
  FROM tbl_board As b 
  LEFT JOIN tbl_category As c ON b.cg_id=c.cg_id 
  WHERE b.board_id=' . $_GET['id']);
    $show_topic_view = mysqli_fetch_assoc($rs_topic_view);
    if (empty($show_topic_view['board_id'])) {//ฟิลด์ board_id เป็นค่าว่างแสดงว่าไม่มีกระทู้นี้อยู่ในฐานข้อมูล
        header('Location:http://webtech2562.96.lt/s1g12/'); //ให้กลับไปหน้าหลัก
    } else {
        if (empty($_GET['notview'])) {//ค่า empty (ว่าง) แสดงว่าให้updateจำนวนผู้เข้าชมได้ ถ้าไม่ empty แสดงว่าห้ามupdateจำนวน
            mysqli_query($connectdb,'UPDATE tbl_board SET board_views=board_views+1 WHERE board_id=' . $_GET['id']); //Update จำนวนผู้เข้าชมของกระทู้นั้น
        }
    }
} else {//ไม่พบค่า id ที่ส่งมา
    header('Location:http://webtech2562.96.lt/s1g12/'); //กลับไปหน้าหลัก
}
?>
<html>
    <head>
        <?php require('head.php'); ?>
        
        <title><?php echo $show_topic_view['board_topic']; ?></title>
    </head>
    <body>
        <?php require('menu.php'); ?>
       
                <ol class="breadcrumb"><!-- ตัวแสดงว่าอยู่หน้าไหน-->
                    <li><a href="http://webtech2562.96.lt/s1g12/">Home</a></li>
                    <li><a href="showboard.php?id=<?php echo $show_topic_view['cg_id']; ?>"><?php echo $show_topic_view['cg_name']; ?></a></li>
                    <li class="active"><?php echo $show_topic_view['board_topic']; ?></li>
                </ol>
                <div>
                    <h1><?php echo $show_topic_view['board_topic']; ?></h1>
                    <?php //เลือกข้อมูลที่ต้องการมาแสดง
                    $rs_board = mysqli_query($connectdb,'SELECT b.board_id,b.mem_id,b.board_topic,b.board_detail,b.board_time_add,
                    b.board_company,b.board_salary,b.board_subcompany,b.board_spec,b.board_tel,
                    c.cg_id,c.cg_name,m.mem_name,m.mem_fname,m.mem_lname,m.mem_gen
  FROM tbl_board As b 
  LEFT JOIN tbl_category As c ON b.cg_id=c.cg_id 
  LEFT JOIN tbl_member As m ON b.mem_id=m.mem_id 
  WHERE b.board_id=' . $_GET['id'] . '  ORDER BY b.board_time_add ASC');
                   
                    $show_board = mysqli_fetch_assoc($rs_board); //นำข้อมูลมาเก็บใน array แล้วนำไปแสดง
                        $board_id = $show_board['board_id'];
                        $cg_id = $show_board['cg_id'];
                        ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                
                                <div style="display:table-cell;vertical-align:top;width:100%;"> 
                                    <div style="text-align:right;color:#C8C8C8;border-bottom:1px dashed #C8C8C8;padding-bottom:4px;">
                                        <?php
                                        $linkEdit = "board_edit.php?id=$board_id&cg_id=$cg_id";
                                        $linkDel = 'viewboard.php?delTopicID=' . $board_id;
                                        ?>
                                            
                                    
                                            กระทู้หลัก
                                       
                                        By : <span style="color:#060"><?php echo $show_board['mem_name'] ?></span>
                                        Date : <?php echo $show_board['board_time_add']; ?>
                                        <span style="color:#999">    </span>
                                    </div>
                                    <div style="padding-top:4px;">
                                    สายงาน :<?php echo $show_board['cg_name']; ?><br>
                                    บริษัท:<?php echo $show_board['board_company']; ?><br>
                                    สาขา:<?php echo $show_board['board_subcompany']; ?><br>
                                    เงินเดือน:<?php if($show_board["board_salary"]!=0) echo $show_board["board_salary"];else echo "ไม่ระบุ"; ?><br>
                                    คุณสมบัติ:<?php echo $show_board['board_spec']; ?><br>
    รีวิว:<?php echo $show_board['board_detail']; ?><br>
    ติดต่อ:<?php echo $show_board['board_tel']; ?><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        ข้อมูลสมาชิก <br>
                        ชื่อ-นามสกุล :<?php echo $show_board['mem_fname'];echo " ";echo $show_board['mem_lname'];  ?><br>
                        รุ่น : <?php echo $show_board['mem_gen']; ?><br>
                        
 

               
      
    </body>
</html>