<?php
    session_start();
    require 'database.php';
    $_SESSION['token'] = substr(md5(rand()), 0, 10); // generate a 10-character random string
    
    if (isset($_SESSION['username'])&&!(empty($_SESSION['username'])))
    {
        header("Location: folder_page.php");
      
    }

    if(isset($_POST)&&!empty($_POST)){
      $username = $_POST['username'];
      $password = $_POST['password'];
      $crypted_password = crypt($password);
       if(isset($_POST['confirm_password'])&&!empty($_POST['confirm_password'])){
       $confirm_password=$_POST['confirm_password'];
       } 
      
      
      // check if a user already exist
      if($_POST["submit"]=="Register"){
      
            $_SESSION['user_created']="created";
            $result0= $mysqli->query("select * from users where username= '$username'");
            
            $row_cnt= $result0->num_rows;
            if($row_cnt!==0||(strcmp($password,$confirm_password))||(!preg_match('/^[\w_\.\-]+$/', $username))||(!preg_match('/^[\w_\.\-]+$/', $password))){
              $_SESSION['user_created']="failed";
             
            }
            else{
              $stmt = $mysqli->prepare("insert into users (username, crypted_password) values (?, ?)");
              if(!$stmt){
                $_SESSION['user_created']="failed";
    
              }
              
              $stmt->bind_param('ss', $username, $crypted_password);
              $stmt->execute();
              $stmt->close();
            }
    
      }
      
      
      if($_POST["submit"]=="Login"){
          $stmt = $mysqli->prepare("SELECT COUNT(*), id, username, crypted_password FROM users WHERE username=?");
           
          // Bind the parameter
          $stmt->bind_param('s', $user);
          $user = $_POST['username'];
          $stmt->execute();
           
          // Bind the results
          $stmt->bind_result($cnt, $id, $username, $pwd_hash);
          $stmt->fetch();
           
          $pwd_guess = $_POST['password'];
          
          
          if( !preg_match('/^[\w_\.\-]+$/', $user) ){
              $_SESSION['login_status']="failed";
          }
          
          if( !preg_match('/^[\w_\.\-]+$/', $pwd_guess) ){
              $_SESSION['login_status']='failed';
          }
          
          // Compare the submitted password to the actual password hash
          if( $cnt == 1 && crypt($pwd_guess, $pwd_hash)==$pwd_hash){
                  header("Location: folder_page.php");
                  echo('login success');
                  $_SESSION['user_id'] = $id;
                  $_SESSION['username'] = $username;
    
                  // Redirect to your target page
          }else{
                  $_SESSION['login_status']="failed";
          }
      }
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
    <title> Home Page </title>
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
    <p></p>
    <p></p>
    <div class="container">
        <div id="loginbox" style="margin-top:150px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
            <div class="panel panel-info" >
                <div class="panel-heading">
                    <div class="panel-title">Sign In</div>
                    <!--                        <div style="float:right; font-size: 80%; position: relative; top:-10px"><a href="#">Forgot password?</a></div>-->
                </div>
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="loginform" class="form-horizontal" action="index.php" method="POST">
                        <div style="margin-bottom: 25px" class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input id="login-username" type="text" class="form-control" name="username" value="" placeholder="username or email" required>                                        
                        </div>
                        <div style="margin-bottom: 25px" class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input id="login-password" type="password" class="form-control" name="password" placeholder="password" required>
                        </div>
                        <div style="margin-top:10px" class="form-group">
                            <!-- Button -->
                            <?php
                                if (isset($_SESSION['user_created'])&&!empty($_SESSION['user_created']))
                                {
                                  if($_SESSION['user_created']=="created"){
                                    echo '<p style="color:white;">'.'User is successfully created. Now log in'.'</p>';
                                  }
                                  if($_SESSION['user_created']=="failed"){
                                    echo '<p style="color:yellow;">'.'User creation failed'.'</p>';
                                  }

                                        if( !preg_match('/^[\w_\.\-]+$/', $username) ){
                                                echo '<p style="color:yellow;">'.'Invalid Usename. Cannot contain "/^[\w_\.\-]+$/"'.'</p>';
                                        }

                                        if( !preg_match('/^[\w_\.\-]+$/', $password) ){
                                                 echo '<p style="color:yellow;">'.'Invalid Password. Cannot contain "/^[\w_\.\-]+$/"'.'</p>';

                                        }
                                                                    
                                }
                                
                                if (isset($_SESSION['login_status'])&&!empty($_SESSION['login_status'])){
                                  echo '<p style="color:yellow;">'.'Login failed. Try again.'.'</p>';
                                }
                                
                                ?>
                            <div class="col-sm-12 controls">
                                <button type="submit" name="submit" value="Login" class="btn btn-primary">Login</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12 control">
                                <div style="border-top: 1px solid#888; padding-top:15px; font-size:85%;color:white;" >
                                    Don't have an account! 
                                    <a href="#" onClick="$('#loginbox').hide(); $('#signupbox').show()" style="font-size: 130%;">
                                    Sign Up Here
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div id="signupbox" style="display:none; margin-top:150px" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">Sign Up</div>
                    <div style="float:right; font-size: 85%; position: relative; top:-10px"><a id="signinlink" href="#" onclick="$('#signupbox').hide(); $('#loginbox').show()" style="font-size: 130%;">Sign In</a></div>
                </div>
                <div class="panel-body" >
                    <form id="signupform" class="form-horizontal" role="form" action="index.php" method="POST">
                        <div id="signupalert" style="display:none" class="alert alert-danger">
                            <p>Error:</p>
                            <span></span>
                        </div>
                        <div class="form-group">
                            <label for="username" class="col-md-3 control-label" style="color:yellow;">Username</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="username" placeholder="Username" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-md-3 control-label" style="color:yellow;">Password</label>
                            <div class="col-md-9">
                                <input type="password" class="form-control" name="password" placeholder="Password" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-md-3 control-label" style="color:yellow;">Confirm</label>
                            <div class="col-md-9">
                                <input type="password" class="form-control" name="confirm_password" placeholder="Confrim Password" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <!-- Button -->                                        
                            <div class="col-sm-12 controls">
                                <button type="submit" name="submit" value="Register" class="btn btn-primary">Register</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
<?php
    unset($_SESSION['user_created']);
    unset($_SESSION['login_status']);
    unset($_SESSION['folder_created']); 
    
    ?>
</html>