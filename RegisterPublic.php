<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<?php
$_SESSION["TrackingURL"]=$_SERVER["PHP_SELF"];
//Confirm_Login();

 ?>
<?php

if(isset($_POST["Submit"])){
  $UserName = $_POST["Username"];
  $Name = $_POST["Name"];
  $Password = $_POST["Password"];
  $ConfirmPassword = $_POST["ConfirmPassword"];
  //$Admin = "Kahlil"; //temp admin
  $Admin =  $_SESSION["UserName"];
  date_default_timezone_set("America/Los_Angeles");
  $CurrentTime=time();
  $DateTime=strftime("%B-%d-%Y %H:%M:%S",$CurrentTime);

  if(empty($UserName) || empty($Password) || empty($ConfirmPassword)){
    $_SESSION["ErrorMessage"]="All fields must be filled out";
    Redirect_to("RegisterPublic.php");
  }
  elseif(strlen($Password)<6){
    $_SESSION["ErrorMessage"]="Password should be greater than 5 characters";
    Redirect_to("RegisterPublic.php");
  }
  elseif(($Password !== $ConfirmPassword)){
    $_SESSION["ErrorMessage"]="Passwords do not match.";
    Redirect_to("RegisterPublic.php");
  }
  elseif(CheckUserNameExistsOrNot($UserName)){
    $_SESSION["ErrorMessage"] = "Username Exists. Try another one.";
    Redirect_to("RegisterPublic.php");
  }
  else
  {
    //query to insert new admin in db when it is good
    global $ConnectingDB;
    $sql="INSERT INTO admins(datetime,username,password,aname,permissions)";
    $sql .="VALUES(:dateTime,:userName,:password,:aName,:permissionNum)";
    $stmt = $ConnectingDB->prepare($sql);//from DB.php
    $stmt->bindValue(':dateTime', $DateTime);
    $stmt->bindValue(':userName',$UserName);
    $stmt->bindValue(':password', $Password);
    $stmt->bindValue(':aName', $Name);
    $stmt->bindValue(':permissionNum', 0);
    $Execute=$stmt->execute();
    if($Execute){
      $_SESSION["SuccessMessage"]="New Admin Added Successfully";
      Redirect_to("RegisterPublic.php");
    }//end if execute
    else{
      $_SESSION["ErrorMessage"]="Something went wrong. Try Again.";
      Redirect_to("RegisterPublic.php");
    }// end else
  }//end else of set

}//End submit button if condition
 ?>
<!DOCTYPE html>
<html lang="en" >
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="Css/styles.css">
    <title>Register</title>
  </head>
  <body>
    <!-- NAVBAR -->
    <div style="height:10px; background:#1F618D;"></div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container" >
        <a href="Blog.php?page=1" class="navbar-brand"> CROSSUPON.COM</a>

        </div>
      </div>
    </nav>
      <div style="height:10px; background:#1F618D;"></div>
      <!-- NAVBAR END -->
      <!-- HEADER -->
      <header class="bg-dark text-white py-3">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
            <h1><i class="fas fa-user" style="color: orange;"></i> Registration</h1>
            </div>
          </div>
        </div>
      </header>
      <!-- HEADER END -->


      <!-- Main Area -->
      <section class="container py-2 mb-4">
        <div class="row">
          <div class="offset-lg-1 col-lg-10" style="min-height: 400px;">
            <?php
              echo ErrorMessage();
              echo SuccessMessage();
            ?>
            <form class="" action="RegisterPublic.php" method="post">
              <div class="card bg-secondary text-light mb-3">
                <div class="card-header">
                  <h1>Register</h1>
                </div>
                <div class="card-body bg-dark">
                  <div class="form-group">
                    <label for="username"> <span class="FieldInfo">  Username: </span></label>
                    <input class="form-control" type="text" name="Username" id="username"  value="">
                  </div>
                  <div class="form-group">
                    <label for="Name"> <span class="FieldInfo">  Name: </span></label>
                    <input class="form-control" type="text" name="Name" id="Name"  value="">
                    <small class="text-muted">Optional</small>
                  </div>
                  <div class="form-group">
                    <label for="Password"> <span class="FieldInfo">  Password: </span></label>
                    <input class="form-control" type="password" name="Password" id="Password"  value="">
                  </div>
                  <div class="form-group">
                    <label for="ConfirmPassword"> <span class="FieldInfo">  Confirm Password: </span></label>
                    <input class="form-control" type="password" name="ConfirmPassword" id="ConfirmPassword"  value="">
                  </div>



                  <div class="row" >
                    <div class="col-lg-6 mb-2">
                      <a href="Blog.php?page=1" class="btn btn-warning btn-block"><i class="fas fa-chevron-circle-left"></i> Back To Homepage </a>
                    </div>
                    <div class="col-lg-6 mb-2">
                      <button type="submit" name="Submit" class="btn btn-success btn-block">
                        <i class="fas fa-check-circle"></i> Submit
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </form>



      </section>

      <!-- End Main Area -->

      <!-- FOOTER -->
      <footer class="bg-dark text-white">
        <div class="container">
          <div class="row">
            <div class="col">
              <p class="lead text-center">Project By Kahlil <span id="year"></span> &copy; ----- </p>
              <p class="text-center small">
                <a style="color: white; text-decoration: none; cursor: pointer;" href="https://www.linkedin.com/in/kahlil-bello-4a6485123/">
                  This site is used for a senior project.<?php echo $ThePermission; ?></a>
              </p>
            </div>
          </div>
        </div>
      </footer>
      <div style="height:10px; background:#1F618D;"></div>
      <!-- FOOTER END -->

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <!-- fontawesome script -->
    <script src="https://kit.fontawesome.com/013d75b213.js" crossorigin="anonymous"></script>
    <script>
    $('#year').text(new Date().getFullYear());
    </script>
  </body>
</html>
