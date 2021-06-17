<?php
  ini_set('display_errors', 1);
?>
<?php
session_start(); // คำสั่งเริ่มใช้เซสชั่น เป็นตัวแปรที่ค่าไม่ถูกเปลี่ยนไม่ว่าจะเปลี่ยนไปหน้าไหน เหมาะกับการเก็บ username และ password
require('bin/connectdb.php');//เรียกไฟล์ภายนอก ซึ่งคือไฟล์เชื่อมต่อฐานข้อมูล หากไม่พบโปรแกรมจะหยุดทำงาน
//แสดงหมวดหมุ่กระทู้ทั้งหมดโดยเรียงตามลำดับ cg_order จากน้อยไปมาก
$rs_category = mysqli_query($connectdb,"SELECT * FROM tbl_category ORDER BY cg_order ASC");
$chk_rows_category = mysqli_num_rows($rs_category); //นับจำนวนแถวของหมวดกระทู้
$connect = mysqli_connect("mysql.hostinger.com", "u742683457_g12", "s1g1s1g1", "u742683457_g12");  
 $query = "SELECT cg_name,cg_topic_totals FROM tbl_category ";  //เลือกใช้กับ PIE Chart
 $result = mysqli_query($connect, $query); 
?>
<html>
    <head>
        <?php require('head.php'); ?> <!-- เรียกไฟล์ head ซึ่งมีคำสั่ง meta name -->
        <title>ITE Future Archievement</title>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>  
           <script type="text/javascript">  
           google.charts.load('current', {'packages':['corechart']});  
           google.charts.setOnLoadCallback(drawChart);  
           function drawChart()  
           {  
                var data = google.visualization.arrayToDataTable([  
                          ['Cg_name', 'Cg_topic_totals'],  
                          <?php  
                          while($row = mysqli_fetch_array($result))  
                          {  
                               echo "['".$row["cg_name"]."', ".$row["cg_topic_totals"]."],";  
                          }  
                          ?>  
                     ]);  
                var options = {  
                      title: 'สายงาน',  
                      //is3D:true,  
                      pieHole: 0.4  
                     };  
                var chart = new google.visualization.PieChart(document.getElementById('piechart'));  
                chart.draw(data, options);  
           } 
           </script>  <!-- คำสั่งใน script คือคำสั่งที่ใช้ในการ สร้าง PIE Chart โดยนำ cg_name(ชื่อสายงาน) cg_topic_totals(จำนวนงาน)มาใช้ -->
           
    </head>
    <body>
        <?php require('menu.php'); ?> <!--เรียกเมนู -->
        <center><h1>เว็บแนะนำอาชีพที่สามารถทำได้หลังจากเรียนจบวิศวกรรมสารสนเทศ</h1></center>
        <center><div id="piechart" style="width: 900px; height: 500px;"></div> </center> <!--เรียก PIE Chart -->
                <table class="table table-bordered table-hover" align="center"> <!--ตารางสายอาชีพต่าง ๆ  -->
                    <thead>
                        <tr>
                            <th style="font-size:160%">สายงาน</th><th class="hidden-xs" style="font-size:160%">จำนวนงาน</th>
                            </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($chk_rows_category > 0) {//จำนวนแถวมากกว่า 0 แสดงว่ามีข้อมูล
                            while ($show_category = mysqli_fetch_assoc($rs_category)) { //คืนค่า ของ $rs_category ในแถวที่ชี้อยู่ ไปเก็บใน Array แล้วเลื่อนไปแถวถัดไป
                                $cg_id = $show_category['cg_id']; //Array ที่ได้จะมีชื่อ key คือ ชื่อฟิลด์
                                $cg_name = $show_category['cg_name'];
                                $cg_des = $show_category['cg_des'];
                                
                                $cg_tp_total = $show_category['cg_topic_totals'];
                                ?>
                                <tr>
                                    <td style="width:80%">
                                        <a href="showboard.php?id=<?php echo $cg_id; ?>" style="font-size:130%"><?php echo $cg_name; ?></a>
                                        <br /><?php echo $cg_des; ?>
                                    </td>
                                    <td style="width:10%" class="hidden-xs" align="center"><?php echo $cg_tp_total; ?></td>
                                    
                                </tr>
                                
                                <?php
                            }
                        } else { //ไม่มีข้อมูลหมวดกระทู้
                            ?>
                            <tr>
                                <td colspan="3" align="center"><strong>ไม่พบข้อมูล</strong></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                
    </body>
</html>