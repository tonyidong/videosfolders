<?php
    require 'database.php';
    
    session_start();
    
    if (!isset($_SESSION['username'])||empty($_SESSION['username']))
    {
      	header('Location: index.php');
      
    }
    
    if(isset($_POST['folder_id'])&&!empty($_POST['folder_id'])){
        
    $_SESSION['folder_id']= $_POST['folder_id'];
    header('Location: folder_content.php');
    
    }
        
    
    if(isset($_POST['folder_name'])&&!empty($_POST['folder_name'])){
    
    $_SESSION['folder_name']= $_POST['folder_name'];
    }
    
    
    $folder_id= $_SESSION['folder_id'];
    $folder_name= $_SESSION['folder_name'];
    ?>
<?php
    if(isset($_SESSION) && !empty($_SESSION)){
        $user_id=$_SESSION['user_id'];
        $username=$_SESSION['username'];
    
        
        if(isset($_POST) && !empty($_POST['title'])){
    
            $new_video_link=$_POST['new_video_link'];
            $title= $_POST['title'];
    
            $stmt= $mysqli->prepare("INSERT INTO video (`id`, `folder_id`, `title`, `link`, `last_edit`) VALUES (NULL, ?, ?, ?, CURRENT_TIMESTAMP);");
            
            if(!$stmt){
                    printf("Query Prep Failed: %s\n", $mysqli->error);
                    exit;
            }
              
            $stmt->bind_param('sss', $folder_id, $title, $new_video_link ); 
            $stmt->execute();
    
        }
        
    ?>
<!DOCTYPE HTML>
<head>
    <meta charset="utf-8">
    <link rel="icon" href="img/logo_icon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="img/logo_icon.ico" type="image/x-icon" />
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-theme.min.css" rel="stylesheet">
    <script src="js/jquery.min.js"></script>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <link href="main.css" rel="stylesheet" type="text/css">
    <title> View Files </title>
</head>
<?php  $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
    if(stripos($ua,'android') !== false) { // && stripos($ua,'mobile') !== false) {
    $background="background: #4C8DAE;";
    }
    else{
    
    $background="";
    }
    
    ?>
<body style="<?php echo $background; ?>";>
    <br/>
    <br/>
    <div style="text-align: center;">
        <p style="color: yellow; font-size: 250%; font-weight:bolder;">Here Is The Content In Folder:<span style="color: yellow; font-size: 180%; font-weight: bolder;"> <?php echo $folder_name; ?> </span></p>
    </div>
    <div class="container">
        <div class="row" style=" background-color:  rgba(0,0,0,0.6);">
            <br/>
            <div class="col-lg-9 col-md-9" style="text-align: center">
                <br/>

                <form class="form-inline" role="form" action="folder_content.php" method="POST">
                <div class= "row">
                <div class= "col-lg-4 col-md-4" style="padding: 5px; text-align: center;">
                    <div class="form-group">
                        <p style="color:green; font-weight: bold; font-size: 130%;">Title:</p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="title" placeholder="New Video Title" required>
                        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
                    </div>
                </div>
                <div class="col-lg-4 col-md-4" style="padding: 5px; text-align:center;">

                    <div class="form-group">
                        <p style="color:green; font-weight: bold; font-size: 130%;">Link:</p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="new_video_link" placeholder="Youtube Link" required>
                    </div>
                </div>
                <div "col-lg-1 col-md-1" style="text-align: center;">
                    <button type="submit" class="btn btn-success">Upload New Video</button>
                </div>
                </div>
                </form>
            </div>
            <div class="col-lg-3 col-md-3" style="text-align: right">
                <br/>
                <form action="folder_page.php" method="POST">
                    <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
                    <button type="submit" class="btn btn-primary">Folders Page</button>
                </form>
                <br/>
            </div>
        </div>
    </div>
    <br/>
    <br/>
    <div class="container">
        <div class="row">
            <?php
                $query = "SELECT id, title, link FROM video where folder_id=?";
                $stmt2 = $mysqli->prepare($query);
                $stmt2->bind_param('s', $folder_id);
                
                if ($stmt2) {
                
                    /* execute statement */
                    $stmt2->execute();
                
                    /* bind result variables */
                    $stmt2->bind_result($video_id, $title, $link);
                
                    /* fetch values */
                    while ($stmt2->fetch()) {
                
                
                $pieces= explode("watch?v=",$link);
                $short_link=$pieces[1];
                ?>
            <div class="col-lg-6">
                <h3> Video Title: <?php echo htmlentities($title); ?></h3>
                <div class="flex-video widescreen"><iframe src="<?php echo "http://www.youtube.com/embed/".$short_link."?enablejsapi=1" ?>" height="315" width="560" allowfullscreen="" frameborder="0"></iframe></div>
                <form action="delete_video.php" method="POST">
                    <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
                    <input type="hidden" name="video_id" value= "<?php echo $video_id; ?>" >    
                    <input type="hidden" name="link" value= "<?php echo htmlentities($link); ?>" >
                    <button type="submit" class="btn btn-danger">Delete This Video</button>
                </form>
                <br/>
                <br/>
            </div>
            <?php
                }
                
                /* close statement */
                $stmt2->close();
                }
                /* close connection */
                $mysqli->close();
                
                }
                
                
                ?>    
        </div>
    </div>
</body>
</html>