<?php
    require 'database.php';
    
    session_start();
    
    //var_dump($_SESSION);
    //var_dump($_POST);
    
    if (!isset($_SESSION['username'])||empty($_SESSION['username']))
    {
      	header('Location: index.php');
      
    }

    ?>
<!DOCTYPE HTML>
<html>
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
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    </head>
    <?php  $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
        if(stripos($ua,'android') !== false) { // && stripos($ua,'mobile') !== false) {
        $background="background: #41555D;";
        }
        else{
        
        $background="";
        }
        
        ?>
    <body style="<?php echo $background; ?>">
        <div style="text-align: center;">
            <h1>Here Are The Folders Of User: <?php echo htmlentities($_SESSION['username']); ?></h1>
        </div>
        <br/>
        <br/>
        <div class="container">
            <div class="row" style=" background-color:  rgba(0,0,0,0.6);">
                <br/>
                <div class="col-md-8 col-sm-9" style="text-align: center">
                    <br/>
                    <form class="form-inline" role="form" action="folder_page.php" method="POST">
                        <div class="form-group">
                            <p style="color:white; font-size: 125%;">Create folder with name:</p>
                        </div>
                        <div class="form-group" style="padding-left: 20px; padding-right: 20px;">
                            <input type="text" class="form-control" name="new_folder_name" placeholder="New Folder Name" required>
                            <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
                        </div>
                        <button type="submit" class="btn btn-success">Create</button>
                        <?php
                            if (isset($_SESSION['folder_created'])&&!empty($_SESSION['folder_created'])){
                            
                                                       if($_SESSION['folderr_created']="failed"){
                                                         echo '<p>'.'folder creation failed'.'</p>';
                                                       }
                                                       
                                                 }
                                                
                            unset($_SESSION['folder_created']);  
                            ?>
                    </form>
                </div>
                <div class="col-md-4 col-sm-3" style="text-align: right">
                    <br/>
                    <form action="logout.php" method="POST">
                        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
                        <button type="submit" class="btn btn-primary">Log Out</button>
                    </form>
                    <br/>
                </div>
            </div>
        </div>
        <br/>
        <br/>
        <div class="container">
            <div class="row marketing">
                <?php
                    if(isset($_SESSION) && !empty($_SESSION)){
                    $user_id=$_SESSION['user_id'];
                    $username=$_SESSION['username'];
                    
                    if(isset($_POST['new_folder_name']) && !empty($_POST['new_folder_name'])){
                    
                    $new_folder_name=$_POST['new_folder_name'];
                    
                    $result0= $mysqli->query("select * from folders where folder_name= '$new_folder_name' and user_id='$user_id'");
                         
                    $row_cnt= $result0->num_rows;
                    if($row_cnt!==0||$new_folder_name==""){
                    $_SESSION['folder_created']="failed";
                          
                    }
                    else{
                    
                     $stmt= $mysqli->prepare("INSERT INTO folders (`id`, `user_id`, `folder_name`, `status`, `last_edit`) VALUES (NULL, ?, ?, 'A', CURRENT_TIMESTAMP);");
                     
                     if(!$stmt){
                      printf("Query Prep Failed: %s\n", $mysqli->error);
                      exit;
                     }
                       
                     $stmt->bind_param('ss', $user_id, $new_folder_name ); 
                     $stmt->execute();
                    }
                     
                    
                    }
                    
                    
                     $query = "SELECT id, folder_name, last_edit FROM folders where user_id=?";
                     $stmt2 = $mysqli->prepare($query);
                     $stmt2->bind_param('s', $user_id);
                     
                     if ($stmt2) {
                     
                    /* execute statement */
                    $stmt2->execute();
                     
                    /* bind result variables */
                    $stmt2->bind_result($folder_id,$folder_name,$last_edit);
                     
                    /* fetch values */
                    while ($stmt2->fetch()) {
                    ?>
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="panel panel-info" style="background-color: rgba(255, 255, 255, 0.5);">
                        <div class="panel-heading">
                            <h4 class="text-center folder-title">
                                Folder:<?php echo htmlentities($folder_name) ?>
                            </h4>
                        </div>
                        <div class="panel-footer" style=" background-color: rgba(0,0,0,0);">
                            <form action="folder_content.php" method="POST">
                                <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
                                <input type="hidden" value="<?php echo htmlentities($folder_name) ?>" name="folder_name">
                                <input type="hidden" value="<?php echo htmlentities($folder_id) ?>" name="folder_id">
                                <button class="btn btn-lg btn-block btn-primary" type="submit">Choose Folder</button>
                            </form>
                            <br/>
                            <form action="delete_folder.php" method="POST">	
                                <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
                                <input type="hidden" value="<?php echo htmlentities($folder_name) ?>" name="folder_name">
                                <input type="hidden" value="<?php echo htmlentities($folder_id) ?>" name="folder_id">
                                <button class="btn btn-lg btn-block btn-danger" type="submit">Delete Folder</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
                    }
                       
                    /* close statement */
                    $stmt2->close();
                       }
                       
                       /* close connection */
                       $mysqli->close();
                       
                      }
                      else{
                    echo "You are not logged in";
                      }
                      
                      ?>
            </div>
        </div>
        </div>
        </div>
    </body>
</html>