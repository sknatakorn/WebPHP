<html>
<head>
    <title>PROFILE</title>
        <meta charset="UTF-8">
        <meta name="viewport" contact="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge"
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
        <link href='https://fonts.googleapis.com/css?family=Sriracha' rel='stylesheet'>
    
        <style>
        body{
                margin: 0;
                padding: 0;
                font-family: 'Sriracha';
                
            }
            button {
                background-color: #FFC31F;
                border: none;
                color: black;
                padding: 5px 18px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                margin: 4px 4px;
                cursor: pointer;
                }
            .nav ul {
                list-style: none;
                background-color: #FFC31F;
                background-repeat: no-repeat;
                background-size: 100%;
                text-align: right;
                padding: 0;
                margin: 0;
            }
            .nav li {
                font-size: 1.1em;
                line-height: 45px;
                text-align: center;
            }
            .nav a {
                text-decoration: none;
                color: #fff;
                display: block;
                padding-left: 20px;
                border-bottom: 1px solid #888;
                transition: .3s;
            }
            .nav a:hover {
                background-color:#F5B400;
            }
            .nav a:active {
                background-color: #aaa;
                color: #444;
                cursor: default;
            }
            .nav li li {
                font-size: .8em;
            }
            table#table1 {
                width: 30%;
                margin-left: 35%;
                margin-top: 2%;
            }
            /*Style menu for larger screens*/
            @media screen and (min-width: 650px) {
                .nav li {
                    width: 180px;
                    border-bottom: none;
                    height: 45px;
                    line-height: 45px;
                    font-size: 1.2em;
                    display: inline-block;
                    margin-right: -4px;
                }
                .nav a {
                    border-bottom: none;
                }
                .nav > ul > li > a {
                    padding-left: 0;
                }
                /*Sub Menu*/
                .nav li ul {
                    position: absolute;
                    display: none;
                    width: inherit;
                }
                .nav li:hover ul {
                    display: block;
                }
                .nav li ul li {
                    display: block;
                }
                table#table1 {
                    font-size: 1.2em;
            }
            }
        </style>
</head>
    <body  bgcolor="#FFF5DA">
            <header>
                <div class="nav">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="http://webtech2562.96.lt/s1g12/">&nbsp;<i class="fas fa-home fa-1x">&nbsp;</i></a></li>
 
                    <?php
                    if (empty($_SESSION['mem_id'])) {
                        ?>
                        <li><a href="login.php">Login</a></li>    
                        <li><a href="register.php">Register</a></li>   
                        <?php
                    } else {
                        ?>   
                        <li> <a href="#">wellcome : <b><?php echo $_SESSION['mem_name']; ?></b></a></li>
                        
                            <li> <a href="category.php">จัดการหมวดสายงาน</a></li>
                            <li><a href="search.php">ค้นหาสายงาน</a></li>
                        
                        <li><a href="logout.php">Logout</a></li>
                        <?php
                    }
                    ?>             
                
                </ul>
                </div>
            </header>
            </body>
</html>
         
